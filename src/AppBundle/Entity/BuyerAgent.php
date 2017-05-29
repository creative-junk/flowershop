<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/9/2017
 * Time: 5:54 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuyerAgentRepository")
 * @ORM\Table(name="buyer_agent")
 */
class BuyerAgent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="buyerAgents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="agentBuyers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $listOwner;

    /**
     * @ORM\Column(type="string")
     */
    private $status;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateSince;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

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
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * @param mixed $buyer
     */
    public function setBuyer($buyer)
    {
        $this->buyer = $buyer;
    }

    /**
     * @return mixed
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return \DateTime
     */
    public function getDateSince()
    {
        return $this->dateSince;
    }

    /**
     * @param \DateTime $dateSince
     */
    public function setDateSince($dateSince)
    {
        $this->dateSince = $dateSince;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getListOwner()
    {
        return $this->listOwner;
    }

    /**
     * @param mixed $listOwner
     */
    public function setListOwner($listOwner)
    {
        $this->listOwner = $listOwner;
    }
    function __toString()
    {
        $buyer = $this->buyer;

    }
}