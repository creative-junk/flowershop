<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/23/2017
 * Time: 6:42 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"},message="It looks like you already have an account!")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $companyName;

    /**
     * @ORM\Column(type="string")
     */
    private $companyType;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isActive;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $createdBy;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $updatedBy;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User",mappedBy="myCompany",fetch="EXTRA_LAZY")
     */
    private $users;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BillingAddress",mappedBy="company",fetch="EXTRA_LAZY")
     */
    private $billingAddress;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShippingAddress",mappedBy="company",fetch="EXTRA_LAZY")
     */
    private $shippingAddress;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $roses;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Auction",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $auctionProducts;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="buyer",fetch="EXTRA_LAZY")
     */
    private $buyerGrowers;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBuyers;

    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBreeders;
    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="breeder",fetch="EXTRA_LAZY")
     */
    private $breederGrowers;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rating",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $reviews;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->users = new ArrayCollection();
        $this->billingAddress =  new ArrayCollection();
        $this->shippingAddress =  new ArrayCollection();
        $this->growerBreeders = new ArrayCollection();
        $this->growerBuyers =  new ArrayCollection();
        $this->breederGrowers = new ArrayCollection();
        $this->buyerGrowers = new ArrayCollection();
        $this->roses = new ArrayCollection();
        $this->auctionProducts = new ArrayCollection();

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
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
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
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }
    public function __toString()
    {
        return $this->getCompanyName();
    }

    /**
     * @return mixed
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @param mixed $companyType
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;
    }

    /**
     * @return mixed
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param mixed $billingAddress
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return mixed
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param mixed $shippingAddress
     */
    public function setShippingAddress($shippingAddress)
    {
        $this->shippingAddress = $shippingAddress;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getRoses()
    {
        return $this->roses;
    }

    /**
     * @return mixed
     */
    public function getBuyerGrowers()
    {
        return $this->buyerGrowers;
    }

    /**
     * @return mixed
     */
    public function getGrowerBuyers()
    {
        return $this->growerBuyers;
    }

    /**
     * @return mixed
     */
    public function getGrowerBreeders()
    {
        return $this->growerBreeders;
    }

    /**
     * @return mixed
     */
    public function getBreederGrowers()
    {
        return $this->breederGrowers;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @return mixed
     */
    public function getAuctionProducts()
    {
        return $this->auctionProducts;
    }

}