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
}

