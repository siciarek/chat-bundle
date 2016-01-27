<?php

namespace Siciarek\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Siciarek\ChatBundle\Entity\ChatRoom
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_room")
 * @ORM\Entity(repositoryClass="ChatRoomRepository")
 */
class ChatRoom {

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
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @ORM\OneToMany(targetEntity="ChatMessage", mappedBy="room", cascade={ "all" }, orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\Column()
     */
    private $type; // ["duo","open"]

    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $closedAt;


}

