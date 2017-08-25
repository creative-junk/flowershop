<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/9/2017
 * Time: 4:57 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrowerAgentRepository")
 * @ORM\Table(name="grower_agent")
 */
class GrowerAgent
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Company",inversedBy="agentGrowers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="growerAgents")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grower;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     */
    private $listOwner;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $agentListOwner;

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
    public function getGrower()
    {
        return $this->grower;
    }

    /**
     * @param mixed $grower
     */
    public function setGrower($grower)
    {
        $this->grower = $grower;
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
     * @return mixed
     */
    public function getDateSince()
    {
        return $this->dateSince;
    }

    /**
     * @param mixed $dateSince
     */
    public function setDateSince($dateSince)
    {
        $this->dateSince = $dateSince;
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
     * @return mixed
     */
    public function getAgentListOwner()
    {
        return $this->agentListOwner;
    }

    /**
     * @param mixed $agentListOwner
     */
    public function setAgentListOwner($agentListOwner)
    {
        $this->agentListOwner = $agentListOwner;
    }



}