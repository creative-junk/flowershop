<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/17/2017
 * Time: 12:58 PM
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InboxRepository")
 * @ORM\Table(name="message")
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    protected $id;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isRead;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isDeleted;
    /**
     * @ORM\Column(type="boolean")
     */
    protected $isSpam;
    /**
     * @ORM\Column(type="datetime")
     */
    protected $sentAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var User
     */
    protected $sender;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @var User
     */
    private $participant;
    /**
     * @ORM\Column(type="string")
     */
    protected $subject;
    /**
     * @ORM\Column(type="string")
     */
    protected $message;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Thread",inversedBy="messages")
     * @var Thread
     */
    protected $thread;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getIsRead()
    {
        return $this->isRead;
    }

    /**
     * @param mixed $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return mixed
     */
    public function getIsSpam()
    {
        return $this->isSpam;
    }

    /**
     * @param mixed $isSpam
     */
    public function setIsSpam($isSpam)
    {
        $this->isSpam = $isSpam;
    }

    /**
     * @return mixed
     */
    public function getSentAt()
    {
        return $this->sentAt;
    }

    /**
     * @param mixed $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sentAt = $sentAt;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }


    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     */
    public function setThread($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }
    /**
     * @return User
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param User $participant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;
    }
}