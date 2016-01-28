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

    protected function getRepo()
    {
        return $this->em->getRepository('SiciarekChatBundle:ChatChannel');
    }

    public function find($id) {
        
        try {
            $obj = $this->getRepo()->find($id);
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new ChatChannelException('Channel not exist.', 4561237 + 2);
        }
        
        return $obj;
    }
    
    protected function addUrls($channel)
    {
        $ret = [
            'urls' => [
                'messages' => $this->getContainer()->get('router')->generate('chat.message.list', ['channel' => $channel['id'],], true),
                'assignees' => $this->getContainer()->get('router')->generate('chat.channel.assignee.list', ['channel' => $channel['id'],], true),
            ],
        ];

        return array_merge($ret, $channel);
    }

    protected function getQueryBuilder()
    {
        $qb = $this->getRepo()
                ->createQueryBuilder('o')
                ->andWhere('o.deletedAt is NULL')
        ;

        return $qb;
    }

    /**
     * Creates the new channel
     * 
     * @param type $name
     * @param UserInterface $creator
     * @return \Siciarek\ChatBundle\Entity\ChatChannel
     */
    public function create($name, UserInterface $creator, $type = Channel::TYPE_PUBLIC, $assignees = [])
    {

        $channel = new Channel();
        $channel->setName($name);
        $channel->setType($type);

        array_unshift($assignees, $creator);

        foreach ($assignees as $assignee) {
            $a = new Assignee();
            $a->setAssigneeClass(get_class($assignee));
            $a->setAssigneeId($assignee->getId());

            $channel->addAssignee($a);

// Private chanel is only for two persons
            if ($type === Channel::TYPE_PRIVATE and $assignee === $assignees[1]) {
                break;
            }
        }

        $this->em->persist($channel);
        $this->em->flush();

// TODO: use serializer

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
        $result = $this->addUrls($result);

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
    public function getList(UserInterface $assignee)
    {
        $params = [
            'assigneeId' => $assignee->getId(),
            'assigneeClass' => get_class($assignee),
        ];

        $qb = $this->getQueryBuilder()
                ->leftJoin('o.assignees', 'a')
                ->andWhere('a.assigneeId = :assigneeId')
                ->andWhere('a.assigneeClass = :assigneeClass')
                ->orderBy('o.createdAt', 'DESC')
                ->setParameters($params)
        ;

        $query = $qb->getQuery();

        $items = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        $items = array_map([$this, 'addUrls'], $items);
        
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

}
