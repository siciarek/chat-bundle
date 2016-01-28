<?php

namespace Siciarek\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Siciarek\ChatBundle\Entity\ChatChannelAssignee
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_channel_assignee")
 * @ORM\Entity(repositoryClass="ChatChannelAssigneRepository")
 */
class ChatChannelAssignee {

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
     * @ORM\ManyToOne(targetEntity="ChatChannel", inversedBy="assignees")
     */
    private $channel;

    /**
     * @ORM\Column(type="integer")
     */
    private $assigneeId;

    /**
     * @ORM\Column()
     */
    private $assigneeClass;
}

