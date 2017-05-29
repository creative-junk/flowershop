<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/10/2017
 * Time: 5:37 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GrowerBreederRepository")
 * @ORM\Table(name="grower_breeder")
 */
class GrowerBreeder
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="growerBreeders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grower;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="breederGrowers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $breeder;
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
    public function getBreeder()
    {
        return $this->breeder;
    }

    /**
     * @param mixed $breeder
     */
    public function setBreeder($breeder)
    {
        $this->breeder = $breeder;
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



}