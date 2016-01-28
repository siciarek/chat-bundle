<?php

namespace Siciarek\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Siciarek\ChatBundle\Entity\ChatMessage
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_message")
 * @ORM\Entity(repositoryClass="ChatMessageRepository")
 */
class ChatMessage {

    use ORMBehaviors\Blameable\Blameable;
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\SoftDeletable\SoftDeletable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="ChatChannel", inversedBy="messages")
     */
    private $channel;

    /**
     * @ORM\Column(type="text")
     */
    private $content;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return ChatMessage
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set channel
     *
     * @param \Siciarek\ChatBundle\Entity\ChatChannel $channel
     * @return ChatMessage
     */
    public function setChannel(\Siciarek\ChatBundle\Entity\ChatChannel $channel = null)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * Get channel
     *
     * @return \Siciarek\ChatBundle\Entity\ChatChannel 
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
