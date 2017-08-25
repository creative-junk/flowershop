<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/2/2017
 * Time: 1:02 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 * @ORM\Table(name="notification")
 */
class Notification
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
     * @ORM\Column(type="datetime")
     */
    protected $sentAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     * @var Company
     */
    private $participant;
    /**
     * @ORM\Column(type="string")
     */
    protected $subject;
    /**
     * @ORM\Column(type="text")
     */
    protected $message;

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
     * @return Company
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param Company $participant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;
    }
}