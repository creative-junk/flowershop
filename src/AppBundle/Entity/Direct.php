<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 7/19/2017
 * Time: 1:53 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DirectRepository")
 * @ORM\Table(name="direct")
 */
class Direct
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
    private $consignmentNumber;
    /**
     * @ORM\Column(type="integer")
     */
    private $numberOfStems;
    /**
     * @ORM\Column(type="integer")
     */
    private $minimumOrder;
    /**
     * @ORM\Column(type="integer")
     */
    private $stemsPerBox;
    /**
     * @ORM\Column(type="string")
     */
    private $quality;
    /**
     * @ORM\Column(type="string")
     */
    private $currency;
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0,minMessage="Price has to be Greater than 0")
     * @ORM\Column(type="string")
     */
    private $pricePerStem;
    /**
     * @ORM\Column(type="boolean")
     */
    private $announceToBuyers;
    /**
     * @ORM\Column(type="boolean")
     */
    private $areSamplesAvailable;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="roses")
     */
    private $vendor;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="product",fetch="EXTRA_LAZY")
     */
    private $productList;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rating",mappedBy="rose",fetch="EXTRA_LAZY")
     */
    private $reviews;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="product",fetch="EXTRA_LAZY")
     */
    private $comments;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $addedBy;

    function __construct()
    {
        $this->productList = new ArrayCollection();
        $this->setConsignmentNumber($this->numberOfStems);
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
     * @return integer
     */
    public function getMinimumOrder()
    {
        return $this->minimumOrder;
    }

    /**
     * @param integer $minimumOrder
     */
    public function setMinimumOrder($minimumOrder)
    {
        $this->minimumOrder = $minimumOrder;
    }

    /**
     * @return integer
     */
    public function getStemsPerBox()
    {
        return $this->stemsPerBox;
    }

    /**
     * @param integer $stemsPerBox
     */
    public function setStemsPerBox($stemsPerBox)
    {
        $this->stemsPerBox = $stemsPerBox;
    }

    /**
     * @return integer
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
     * @return boolean
     */
    public function getAnnounceToBuyers()
    {
        return $this->announceToBuyers;
    }

    /**
     * @param boolean $announceToBuyers
     */
    public function setAnnounceToBuyers($announceToBuyers)
    {
        $this->announceToBuyers = $announceToBuyers;
    }

    /**
     * @return boolean
     */
    public function getAreSamplesAvailable()
    {
        return $this->areSamplesAvailable;
    }

    /**
     * @param boolean $areSamplesAvailable
     */
    public function setAreSamplesAvailable($areSamplesAvailable)
    {
        $this->areSamplesAvailable = $areSamplesAvailable;
    }



    /**
     * @return ArrayCollection[Product]
     */
    public function getProductList()
    {
        return $this->productList;
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
    public function getAddedBy()
    {
        return $this->addedBy;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    public function __toString(){
        return $this->product->getTitle();
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
    public function getConsignmentNumber()
    {
        return $this->consignmentNumber;
    }

    /**
     * @param mixed $consignmentNumber
     */
    public function setConsignmentNumber($consignmentNumber)
    {
        $this->consignmentNumber = $consignmentNumber;
    }


}