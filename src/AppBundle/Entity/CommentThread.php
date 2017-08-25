<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/17/2017
 * Time: 3:45 PM
 ********************************************************************************/

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment_thread")
 * @ORM\HasLifecycleCallbacks
 */
class CommentThread
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var User
     */
    private $createdBy;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="thread")
     * @var Comment[] | Collection
     */
    private $comments;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * Tells if new comments can be added in this thread
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $isCommentable = true;

    /**
     * Denormalized number of comments
     *
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $numComments = 0;

    /**
     * Denormalized date of the last comment
     *
     * @var DateTime
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastCommentAt = null;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Comment")
     * @var Comment
     */
    private $lastComent;

    function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());

        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }

        $this->comments= new ArrayCollection();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime()
    {
        // update the modified time
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param User $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return Message[]|Collection
     */
    public function getComments()
    {
        return $this->comments;
    }


    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return bool
     */
    public function isCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * @param  bool
     * @return null
     */
    public function setCommentable($isCommentable)
    {
        $this->isCommentable = (bool) $isCommentable;
    }

    /**
     * Gets the number of comments
     *
     * @return integer
     */
    public function getNumComments()
    {
        return $this->numComments;
    }

    /**
     * Sets the number of comments
     *
     * @param integer $numComments
     */
    public function setNumComments($numComments)
    {
        $this->numComments = intval($numComments);
    }

    /**
     * Increments the number of comments by the supplied
     * value.
     *
     * @param  integer $by Value to increment comments by
     * @return integer The new comment total
     */
    public function incrementNumComments($by = 1)
    {
        return $this->numComments += intval($by);
    }

    /**
     * @return DateTime
     */
    public function getLastCommentAt()
    {
        return $this->lastCommentAt;
    }

    /**
     * @param  DateTime
     * @return null
     */
    public function setLastCommentAt($lastCommentAt)
    {
        $this->lastCommentAt = $lastCommentAt;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Comment thread #'.$this->getId();
    }

    /**
     * @return bool
     */
    public function isIsCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * @param bool $isCommentable
     */
    public function setIsCommentable($isCommentable)
    {
        $this->isCommentable = $isCommentable;
    }

    /**
     * @return Comment
     */
    public function getLastComent()
    {
        return $this->lastComent;
    }

    /**
     * @param Comment $lastComent
     */
    public function setLastComent($lastComent)
    {
        $this->lastComent = $lastComent;
    }


}