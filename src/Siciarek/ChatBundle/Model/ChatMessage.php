<?php

namespace Siciarek\ChatBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChatMessage implements ContainerAwareInterface
{

    /**
     * @var ContainerAwareInterface
     */
    protected $container;
    
    public function send($message, ChatChannel $channel) {
        
    }

    public function getContainer()
    {
        return $this->container;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
