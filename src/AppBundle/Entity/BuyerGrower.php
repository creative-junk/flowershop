<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/9/2017
 * Time: 4:58 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BuyerGrowerRepository")
 * @ORM\Table(name="buyer_grower")
 */
class BuyerGrower
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="buyerGrowers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;
    /**
     * @ORM\ManyToOne(targetEntity="User",inversedBy="growerBuyers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $grower;
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
     * @return User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * @param User $buyer
     */
    public function setBuyer(User $buyer)
    {
        $this->buyer = $buyer;
    }

    /**
     * @return User
     */
    public function getGrower()
    {
        return $this->grower;
    }

    /**
     * @param User $grower
     */
    public function setGrower(User $grower)
    {
        $this->grower = $grower;
    }

    /**
     * @return User
     */
    public function getListOwner()
    {
        return $this->listOwner;
    }

    /**
     * @param User $listOwner
     */
    public function setListOwner(User $listOwner)
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