<?php

namespace Siciarek\ChatBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Siciarek\ChatBundle\Entity\ChatChannel as Channel;
use Siciarek\ChatBundle\Model\ChatMessageException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Route("/api")
 */
class ChatController extends CommonController
{

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


            $name = $request->get('name', $this->getUser()->getUsername());
            $type = $request->get('type', Channel::TYPE_PRIVATE);
            $assigneesIds = json_decode($request->get('assignees', '[]'));

            // Validate and sanitize input:
            $name = trim($name);
            if (strlen($name) === 0) {
                $name = $this->getUser()->getUsername();
            }
            $name = substr($name, 0, Channel::NAME_MAX_LENGTH);
            $name = trim($name);

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
                    $name, $this->getUser(), $type, $assignees
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
            $items = $this->get('chat.message')->getList($channel, $this->getUser());

            return $this->getFrame()->getDataFrame('Messages', $items);
        };

        return $this->handleJsonAction($run);
    }

    /**
     * @Route("/channel/{channel}/message/append", defaults={"_format":"json"}, name="chat.message.append")
     */
    public function messageAppendAction(Request $request, $channel)
    {
        $run = function() use ($request, $channel) {

            $message = $request->get('message');
            $message = trim($message);

            if (strlen($message) === 0) {
                throw new ChatMessageException('Message can not be empty.');
            }

            $this->get('chat.message')->send($message, $channel);

            return $this->getFrame()->getInfoFrame();
        };

        return $this->handleJsonAction($run);
    }

}
