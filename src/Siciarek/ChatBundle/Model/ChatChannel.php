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

    protected function getQueryBuilder()
    {
        $repo = $this->em->getRepository('SiciarekChatBundle:ChatChannel');

        $qb = $repo->createQueryBuilder('o')
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
    public function create($name, UserInterface $creator, $type = Channel::TYPE_PUBLIC)
    {

        $channel = new Channel();
        $channel->setName($name);
        $channel->setType($type);

        $a = new Assignee();
        $a->setAssigneeClass(get_class($creator));
        $a->setAssigneeId($creator->getId());

        $channel->addAssignee($a);

        $this->em->persist($channel);
        $this->em->flush();

        // TODO: use serializer
        $qb = $this->getQueryBuilder()->andWhere('o.id = :id')->setParameter('id', $channel->getId());
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

        $result = $query->getSingleResult();

        $this->em->remove($result);
        $this->em->flush();

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
