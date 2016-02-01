<?php

namespace Siciarek\ChatBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Siciarek\ChatBundle\Entity\ChatChannel as Channel;
use Siciarek\ChatBundle\Entity\ChatChannelAssignee as Assignee;

class ChatChannel implements ContainerAwareInterface
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerAwareInterface
     */
    protected $container;

    public function leave($id, UserInterface $user, $farewellMessage = 'Elvis has left the building.')
    {

        $channel = $this->find($id);

        $repo = $this->em->getRepository('SiciarekChatBundle:ChatChannelAssignee');
        $qb = $repo->createQueryBuilder('a')
                ->leftJoin('a.channel', 'c')
                ->andWhere('c.id = :channel')
                ->andWhere('a.assigneeId = :id')
                ->andWhere('a.assigneeClass = :class')
                ->setParameters([
            'channel' => $id,
            'id' => $user->getId(),
            'class' => get_class($user),
        ]);

        $query = $qb->getQuery();
        $assignee = $query->getSingleResult();

        $channel->removeAssignee($assignee);
        $this->em->remove($assignee);
        $this->em->flush();
        $this->em->refresh($channel);

        if ($channel->getAssignees()->count() === 0) {
            $this->em->remove($channel);
            $this->em->flush();
        }
        
        $this->getContainer()->get('chat.message')->send($farewellMessage, $id);

        return true;
    }

    /**
     * Creates new channel
     * 
     * @param UserInterface $creator
     * @param array $assignees
     * @param string $type
     * @param string $name
     * @return type
     */
    public function create(UserInterface $creator, $assignees = [], $type = Channel::TYPE_PUBLIC, $name = null)
    {

        $channel = new Channel();
        $channel->setName($name);
        $channel->setType($type);

        array_unshift($assignees, $creator);

        $names = [];

        foreach ($assignees as $assignee) {
            $a = new Assignee();
            $a->setAssigneeClass(get_class($assignee));
            $a->setAssigneeId($assignee->getId());

            $channel->addAssignee($a);

// Private chanel is only for two persons
            
            $names[] = $assignee->getUsername();

            if ($type === Channel::TYPE_PRIVATE and $assignee === $assignees[1]) {
                break;
            }
        }

        $count = 0;

        if ($name === null) {

            $created = true;

            if (count($names) > 3) {
                $temp = [];
                $temp[] = array_shift($names);
                $temp[] = array_shift($names);
                $temp[] = array_shift($names);

                $count = count($names);

                $names = $temp;
            }

            sort($names);

            $name = implode(', ', $names);
        } else {
            // Validate and sanitize input:
            $name = trim($name);

            if (strlen($name) === 0) {
                $name = $this->getUser()->getUsername();
            }
        }

        $name = substr($name, 0, Channel::NAME_MAX_LENGTH);
        $name = trim($name);

        if ($count > 0) {
            $name .= ' + ' . $count;
        }

        $result = $this->getRepo()
                ->createQueryBuilder('o')
                ->andWhere('o.name = :name')
                ->setParameters(['name' => $name])
                ->orderBy('o.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
        ;

        if (count($result) > 0) {
            $channel = $result[0];
            if ($channel->isDeleted()) {
                $channel->restore();
            }
            foreach ($channel->getAssignees(true) as $a) {
                $a->restore();
            }
        } else {
            $channel->setName($name);

            $this->em->persist($channel);
        }
        $this->em->flush();

        return $this->getDetails($channel->getId());
    }

    protected function getDetails($id)
    {

        $qb = $this->getQueryBuilder()
                ->select('o, a')
                ->leftJoin('o.assignees', 'a')
                ->andWhere('o.id = :id')
                ->setParameter('id', $id);

        $result = $qb->getQuery()->getSingleResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result;
    }

    /**
     * Closes the channel, only if created by creator.
     * 
     * @param int $id Channel id
     * @param UserInterface $creator
     * @return boolean
     * @throw Exception
     */
    public function close($id, UserInterface $creator)
    {
        $params = [
            'id' => $id,
            'creator' => $creator->getUsername(),
        ];

        $qb = $this->getQueryBuilder()
                ->andWhere('o.id = :id')
                ->andWhere('o.createdBy = :creator')
                ->setParameters($params)
        ;
        $query = $qb->getQuery();

        try {
            $obj = $query->getSingleResult();
            $this->em->remove($obj);
            $this->em->flush();
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new ChatChannelException('Channel is alerady closed.');
        }


        return true;
    }

    /**
     * Returns list of open channels with involved assignee.
     * 
     * @param UserInterface $assignee
     * @return array
     */
    public function getList(UserInterface $user)
    {
        $params = [
            'assigneeId' => $user->getId(),
            'assigneeClass' => get_class($user),
        ];

        $qb = $this->getQueryBuilder()
                ->leftJoin('o.assignees', 'a')
                ->andWhere('a.assigneeId = :assigneeId')
                ->andWhere('a.assigneeClass = :assigneeClass')
                ->andWhere('a.deletedAt IS null')
                ->orderBy('o.createdAt', 'DESC')
                ->setParameters($params)
        ;

        $query = $qb->getQuery();

        $items = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        
        $items = array_map(function($e) use ($user) {
            $name = $e['name'];
            $name = str_replace(', ' . $user->getUsername(), '', $name);
            $name = str_replace($user->getUsername() . ',', '', $name);
            $name = str_replace($user->getUsername(), '', $name);
            $name = trim($name);
            $name = preg_replace('/\s+/', ' ', $name);
            
            $e['name'] = $name;
            
            return $e;
        }, $items);

        return $items;
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;

        $this->em = $this->getContainer()->get('doctrine.orm.entity_manager');
    }

    protected function getRepo()
    {
        return $this->em->getRepository('SiciarekChatBundle:ChatChannel');
    }

    public function find($id)
    {
        try {
            return $this->getRepo()->findOneBy(['id' => $id]);
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new ChatChannelException('Channel does not exist.', 4561237 + 2);
        }
    }

    protected function getQueryBuilder()
    {
        $qb = $this->getRepo()
                ->createQueryBuilder('o')
                ->andWhere('o.deletedAt is NULL')
        ;

        return $qb;
    }

}
