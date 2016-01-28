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
class ChatChannelAssignee
{

    use ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\SoftDeletable\SoftDeletable;

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
     * Set assigneeId
     *
     * @param integer $assigneeId
     * @return ChatChannelAssignee
     */
    public function setAssigneeId($assigneeId)
    {
        $this->assigneeId = $assigneeId;

        return $this;
    }

    /**
     * Get assigneeId
     *
     * @return integer 
     */
    public function getAssigneeId()
    {
        return $this->assigneeId;
    }

    /**
     * Set assigneeClass
     *
     * @param string $assigneeClass
     * @return ChatChannelAssignee
     */
    public function setAssigneeClass($assigneeClass)
    {
        $this->assigneeClass = $assigneeClass;

        return $this;
    }

    /**
     * Get assigneeClass
     *
     * @return string 
     */
    public function getAssigneeClass()
    {
        return $this->assigneeClass;
    }

    /**
     * Set channel
     *
     * @param \Siciarek\ChatBundle\Entity\ChatChannel $channel
     * @return ChatChannelAssignee
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
