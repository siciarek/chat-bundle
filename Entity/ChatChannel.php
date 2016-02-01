<?php

namespace Siciarek\ChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Siciarek\ChatBundle\Entity\ChatChannel
 *
 * @ORM\Entity
 * @ORM\Table(name="chat_channel")
 * @ORM\Entity(repositoryClass="ChatChannelRepository")
 */
class ChatChannel
{

    const NAME_MAX_LENGTH = 32;
    const TYPE_PRIVATE = 'private';
    const TYPE_PUBLIC = 'public';
    const TYPE_PROTECTED = 'protected';

    public static $types = [
        self::TYPE_PRIVATE,
        self::TYPE_PUBLIC,
        self::TYPE_PROTECTED,
    ];

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
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @ORM\OneToMany(targetEntity="ChatMessage", mappedBy="channel", cascade={ "all" }, orphanRemoval=true)
     */
    private $messages;

    /**
     * @ORM\OrderBy({"createdAt" = "DESC"})
     * @ORM\OneToMany(targetEntity="ChatChannelAssignee", mappedBy="channel", cascade={ "all" }, orphanRemoval=true)
     */
    private $assignees;

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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignees = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set type
     *
     * @param string $type
     * @return ChatChannel
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return ChatChannel
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set closedAt
     *
     * @param \DateTime $closedAt
     * @return ChatChannel
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;

        return $this;
    }

    /**
     * Get closedAt
     *
     * @return \DateTime 
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }

    /**
     * Add messages
     *
     * @param \Siciarek\ChatBundle\Entity\ChatMessage $messages
     * @return ChatChannel
     */
    public function addMessage(\Siciarek\ChatBundle\Entity\ChatMessage $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \Siciarek\ChatBundle\Entity\ChatMessage $messages
     */
    public function removeMessage(\Siciarek\ChatBundle\Entity\ChatMessage $messages)
    {
        $messages->setChannel($this);
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Add assignees
     *
     * @param \Siciarek\ChatBundle\Entity\ChatChannelAssignee $assignees
     * @return ChatChannel
     */
    public function addAssignee(\Siciarek\ChatBundle\Entity\ChatChannelAssignee $assignees)
    {
        $assignees->setChannel($this);
        $this->assignees[] = $assignees;

        return $this;
    }

    /**
     * Remove assignees
     *
     * @param \Siciarek\ChatBundle\Entity\ChatChannelAssignee $assignees
     */
    public function removeAssignee(\Siciarek\ChatBundle\Entity\ChatChannelAssignee $assignees)
    {
        $this->assignees->removeElement($assignees);
    }

    /**
     * Get assignees
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAssignees()
    {
        $assignees = $this->assignees->filter(function($e) {
            return $e->getDeletedAt() === null;
        });

        return $assignees;
    }

}
