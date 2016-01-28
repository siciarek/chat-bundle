<?php

namespace Siciarek\ChatBundle\Model;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ChatMessageException extends \Exception {
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, 4561237 + 1, $previous);
    }
}

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
