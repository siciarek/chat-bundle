<?php

namespace Siciarek\ChatBundle\Controller;

use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Siciarek\ChatBundle\Entity\ChatChannel as Channel;
use Siciarek\ChatBundle\Model\ChatMessageException;
use utilphp\util;

/**
 * @Route("/api")
 */
class ChatController extends CommonController
{

    protected function getActiveUsersIds()
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $userClass = $this->container->get('fos_user.user_manager')->getClass();
        $sessionTable = $this->container->getParameter('pdo.db_options')['db_table'];

        $sql = sprintf('SELECT * FROM %s WHERE data LIKE :like', $sessionTable);
        $stmt = $em->getConnection()->prepare($sql);
        $like = '%' . preg_replace('/\\\/', '\\\\\\', $userClass) . '%';
        $stmt->execute([ 'like' => $like ]);
        $result = $stmt->fetchAll();

        $ids = [];

        foreach ($result as $r) {
            $temp = explode('_sf2_', $r['data']);
            array_shift($temp);
            $data = array_shift($temp);
            $data = preg_replace('/^\w+\|/', '', $data);
            $data = unserialize($data);

            $lifetime = $r['lifetime'];
            $expired = time() - $r['time'] >= $lifetime;

            foreach ($data as $key => $str) {
                if (strpos($str, $userClass) !== false and $expired === false) {
                    $obj = unserialize($str);
                    $ids[] = $obj->getUser()->getId();
                }
            }
        }

        $ids = array_unique($ids);

        return $ids;
    }

    /**
     * @Route("/user/list", name="chat.user.list")
     */
    public function userListAction()
    {

        $run = function() {
            $em = $this->get('doctrine.orm.entity_manager');

            $userClass = $this->container->get('fos_user.user_manager')->getClass();

            $query = $em
                    ->getRepository($userClass)
                    ->createQueryBuilder('u')
                    ->select('u.id, u.username, u.email')
                    ->andWhere('u.enabled = true')
                    ->andWhere('u.username NOT IN (:excluded)')
                    ->setParameters([
                        'excluded' => ['system'],
                    ])
                    ->getQuery()
            ;

            $data = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
            $ids = $this->getActiveUsersIds();

            $data = array_map(function($e) use ($ids) {
                $e['online'] = in_array($e['id'], $ids);
                $e['image'] = util::get_gravatar($e['email']);
                return $e;
            }, $data);

            usort($data, function($a, $b) {
                return $a['online'] < $b['online'];
            });
            
            return $this->getFrame()->getDataFrame('Users', $data);
        };
        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/{channel}/assignees", defaults={"_format":"json"}, name="chat.channel.assignee.list")
     */
    public function channelAssigneesAction(Request $request, $channel)
    {
        $run = function() use ($request, $channel) {
            $channel = $this->get('chat.channel')->find($channel);
            $items = [];

            if ($channel instanceof Channel) {
                $class = null;

                $ids = array_map(function($e) use (&$class) {
                    $class = $e->getAssigneeClass();
                    return $e->getAssigneeId();
                }, $channel->getAssignees()->toArray());

                $qb = $this->get('doctrine.orm.entity_manager')
                        ->getRepository($class)
                        ->createQueryBuilder('u')
                        ->select('u.id, u.usernameCanonical, u.emailCanonical')
                        ->andWhere('u.id IN (:ids)')
                        ->setParameters(['ids' => $ids])
                ;

                $items = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
            }

            return $this->getFrame()->getDataFrame('Assignees', $items);
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/{channel}/join", defaults={"_format":"json"}, name="chat.channel.join")
     */
    public function channelJoinAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];

        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

    /**
     * @Route("/channel/{channel}/leave", defaults={"_format":"json"}, name="chat.channel.leave")
     */
    public function channelLeaveAction(Request $request, $channel)
    {
        $data = [__FUNCTION__, $channel];

        return new Response(json_encode($data, JSON_PRETTY_PRINT));
    }

# ------------------------------------------------------------------------------

    /**
     * @Route("/channel/list", defaults={"_format":"json"}, name="chat.channel.list")
     */
    public function channelListAction(Request $request)
    {
        $run = function() use ($request) {
            $items = $this->get('chat.channel')->getList($this->getUser());

            return $this->getFrame()->getDataFrame('Channels', $items);
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/create", defaults={"_format":"json"}, name="chat.channel.create")
     */
    public function channelCreateAction(Request $request)
    {

        $run = function() use ($request) {

            $name = $request->get('name', null);
            $type = $request->get('type', Channel::TYPE_PRIVATE);
            $assigneesIds = json_decode($request->get('assignees', '[]'));

            if (!in_array($type, Channel::$types)) {
                throw $this->createNotFoundException();
            }

            if (!is_array($assigneesIds)) {
                throw $this->createNotFoundException();
            }
            $assigneesIds = array_map('intval', $assigneesIds);
            $assigneesIds = array_unique($assigneesIds);
            $assigneesIds = array_values(array_filter($assigneesIds, function($e) {
                        return intval($e) > 0;
                    }));

            // Convert ids to entities:
            $class = get_class($this->getUser());
            $assignees = array_map(function($id) use ($class) {
                return $this->get('doctrine.orm.entity_manager')
                                ->getRepository($class)
                                ->findOneById(intval($id))
                ;
            }, $assigneesIds);

            // Sanitize entities list:
            $assignees = array_values(array_filter($assignees, function($e) {
                        return $e != null;
                    }));

            $item = $this->get('chat.channel')->create(
                    $this->getUser(), $assignees, $type, $name
            );

            return $this->getFrame()->getInfoFrame('OK', $item);
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/{channel}/close", defaults={"_format":"json"}, name="chat.channel.close")
     */
    public function channelCloseAction(Request $request, $channel)
    {
        $run = function() use ($channel) {
            $this->get('chat.channel')->close($channel, $this->getUser());

            return $this->getFrame()->getInfoFrame();
        };

        return $this->handleJsonAction($run);
    }

# ------------------------------------------------------------------------------

    /**
     * @Route("/channel/{channel}/message/list", defaults={"_format":"json"}, name="chat.message.list")
     */
    public function messageListAction(Request $request, $channel)
    {   
        
        $run = function() use ($request, $channel) {
            $page = $request->query->getInt('page', 1);
        
            /**
             * @var \Knp\Component\Pager
             */
            $pager = $this->get('chat.message')->getList($channel, $this->getUser(), $page);

            $frame = $this->getFrame()->getDataFrame('Messages', $pager);
            
            return $frame;
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/{channel}/message/send", defaults={"_format":"json"}, name="chat.message.send")
     */
    public function messageAppendAction(Request $request, $channel)
    {
        $run = function() use ($request, $channel) {

            $message = $request->get('message');
            $message = trim($message);

            if (strlen($message) === 0) {
                throw new ChatMessageException('Message can not be empty.');
            }

            $message = $this->get('chat.message')->send($message, $channel);

            return $this->getFrame()->getInfoFrame('Message', $message);
        };

        return $this->handleJsonAction($run);
    }

}
