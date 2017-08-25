<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/19/2017
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuctionRepository")
 * @ORM\Table(name="auction")
 * @ORM\HasLifecycleCallbacks
 */
class Auction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     */
    private $product;
    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfStems;
    /**
     * @ORM\Column(type="string")
     */
    private $pricePerStem;
    /**
     * @ORM\Column(type="string")
     */
    private $quality;
    /**
     * @ORM\Column(type="boolean")
     */
    private $announceToAgents;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isInStock;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $numberOfAgentStems;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $addedBy;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $shippedAt;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $acceptedAt;

    /**
     * @
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     *
     */
    private $sellingAgent;
    /**
     * @ORM\Column(type="string")
     */
    private $currency;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="auctionProducts")
     */
    private $vendor;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="auctionProduct",fetch="EXTRA_LAZY")
     */
    private $auctionList;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rating",mappedBy="auction",fetch="EXTRA_LAZY")
     */
    private $reviews;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="auction",fetch="EXTRA_LAZY")
     */
    private $comments;
    /**
     * @ORM\Column(type="string")
     */
    private $status;


    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());

        $this->auctionList= new ArrayCollection();
        $this->reviews = new ArrayCollection();

    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return integer
     */
    public function getNumberOfStems()
    {
        return $this->numberOfStems;
    }

    /**
     * @param integer $numberOfStems
     */
    public function setNumberOfStems($numberOfStems)
    {
        $this->numberOfStems = $numberOfStems;
    }

    /**
     * @return mixed
     */
    public function getPricePerStem()
    {
        return $this->pricePerStem;
    }

    /**
     * @param mixed $pricePerStem
     */
    public function setPricePerStem($pricePerStem)
    {
        $this->pricePerStem = $pricePerStem;
    }

    /**
     * @return mixed
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param mixed $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @return boolean
     */
    public function getAnnounceToAgents()
    {
        return $this->announceToAgents;
    }

    /**
     * @param boolean $announceToAgents
     */
    public function setAnnounceToAgents($announceToAgents)
    {
        $this->announceToAgents = $announceToAgents;
    }

    /**
     * @return boolean
     */
    public function getIsInStock()
    {
        return $this->isInStock;
    }

    /**
     * @param boolean $isInStock
     */
    public function setIsInStock($isInStock)
    {
        $this->isInStock = $isInStock;
    }

    /**
     * @return Company
     */
    public function getSellingAgent()
    {
        return $this->sellingAgent;
    }

    /**
     * @param Company $sellingAgent
     */
    public function setSellingAgent($sellingAgent)
    {
        $this->sellingAgent = $sellingAgent;
    }

    /**
     * @return integer
     */
    public function getNumberOfAgentStems()
    {
        return $this->numberOfAgentStems;
    }

    /**
     * @param integer $numberOfAgentStems
     */
    public function setNumberOfAgentStems($numberOfAgentStems)
    {
        $this->numberOfAgentStems = $numberOfAgentStems;
    }

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
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
    public function getCreatedAt()
    {
        return $this->createdAt;
    }



    /**
     * @return User $user
     *
     */
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param User $user
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }


    /**
     * @return ArrayCollection[MyList]
     */
    public function getAuctionList()
    {
        return $this->auctionList;
    }



    /**
     * @return mixed
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param mixed $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
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
    public function getComments()
    {
        return $this->comments;
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
    public function getShippedAt()
    {
        return $this->shippedAt;
    }

    /**
     * @param mixed $shippedAt
     */
    public function setShippedAt($shippedAt)
    {
        $this->shippedAt = $shippedAt;
    }

    /**
     * @return mixed
     */
    public function getAcceptedAt()
    {
        return $this->acceptedAt;
    }

    /**
     * @param mixed $acceptedAt
     */
    public function setAcceptedAt($acceptedAt)
    {
        $this->acceptedAt = $acceptedAt;
    }

    public function __toString(){
        return $this->getProduct()->getTitle();
    }

}