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

    public function find($id)
    {

        try {
            $obj = $this->getRepo()->find($id);
        } catch (\Doctrine\ORM\NoResultException $e) {
            throw new ChatChannelException('Channel not exist.', 4561237 + 2);
        }

        return $obj;
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

        $result = $this->getQueryBuilder()
                ->andWhere('o.name = :name')
                ->setParameters(['name' => $name])
                ->orderBy('o.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
        ;

        if (count($result) > 0) {
            $channel = $result[0];
        } else {
            $channel->setName($name);

            $this->em->persist($channel);
            $this->em->flush();
        }

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
