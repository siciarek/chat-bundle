<?php

namespace Siciarek\ChatBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\Security\Core\User\UserInterface;
use Siciarek\ChatBundle\Entity\ChatChannel as Channel;
use Siciarek\ChatBundle\Entity\ChatChannelAssignee as Assignee;
use Siciarek\ChatBundle\Entity\ChatMessage as Message;

class ChatMessageException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 4561237 + 1, $previous);
    }
}

class ChatMessage implements ContainerAwareInterface
{


    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $em;

    /**
     * @var ContainerAwareInterface
     */
    protected $container;

        /**
     * Returns list of open channels with involved assignee.
     * 
     * @param UserInterface $assignee
     * @return array
     */
    public function getList($channelId, UserInterface $assignee)
    {
        $params = [
            'channel' => $this->getContainer()->get('chat.channel')->find($channelId),
        ];

        $qb = $this->getQueryBuilder()
                ->andWhere('o.channel = :channel')
                ->orderBy('o.createdAt', 'DESC')
                ->setParameters($params)
        ;

        $query = $qb->getQuery();

        $items = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $items;
    }
    
    protected function getRepo()
    {
        return $this->em->getRepository('SiciarekChatBundle:ChatMessage');
    }

    protected function getQueryBuilder()
    {
        $qb = $this->getRepo()
                ->createQueryBuilder('o')
                ->andWhere('o.deletedAt is NULL')
        ;

        return $qb;
    }

    public function send($content, $channelId) {

        $m = new Message();
        $m->setContent($content);
        $m->setChannel($this->getContainer()->get('chat.channel')->find($channelId));
        $this->em->persist($m);
        $this->em->flush();
        
        return $m;
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
