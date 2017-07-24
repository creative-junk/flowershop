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
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
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
     * @Assert\NotBlank()
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
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

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());

        $this->auctionList= new ArrayCollection();

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
     * @return User
     */
    public function getSellingAgent()
    {
        return $this->sellingAgent;
    }

    /**
     * @param User $sellingAgent
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


    function __toString()
    {

        return $this->product->getTitle;
    }



}