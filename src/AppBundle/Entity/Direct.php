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
    private $onHand=0;
    /**
     * @ORM\Column(type="integer")
     */
    private $onHold=0;
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
     * @ORM\Column(type="string",nullable=true)
     */
    private $previousPrice;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnSale=false;
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
        $this->setConsignmentNumber($numberOfStems);
        $this->setOnHand($numberOfStems);

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
     * @return mixed
     */
    public function getIsOnSale()
    {
        return $this->isOnSale;
    }

    /**
     * @param mixed $isOnSale
     */
    public function setIsOnSale($isOnSale)
    {
        $this->isOnSale = $isOnSale;
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
    public function getPreviousPrice()
    {
        return $this->previousPrice;
    }

    /**
     * @param mixed $previousPrice
     */
    public function setPreviousPrice($previousPrice)
    {
        $this->previousPrice = $previousPrice;
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
     * @return ArrayCollection[Rating]
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

    /**
     * @return integer
     * The number of items on Hand
     */
    public function getOnHand()
    {
        return $this->onHand;
    }

    /**
     * @param integer $onHand
     * Set the number of items On Hand
     */
    public function setOnHand($onHand)
    {
        $this->onHand = $onHand;
    }

    /**
     * @return integer
     * The Number of Items on Hold
     */
    public function getOnHold()
    {
        return $this->onHold;
    }

    /**
     * @param integer $onHold
     * Set the number of Products on Hold
     */
    public function setOnHold($onHold)
    {
        $this->onHold = $onHold;
    }

    /**
     * @param integer $hold
     * Put Inventory Items onHold awaiting Payment. Remove them from OnHand. This happens when an
     * Order is placed but before is paid for
     */
    public function hold($hold){
        $onHand = $this->getOnHand()-$hold;
        $this->setOnHand($onHand);
        $this->setOnHold($hold);

    }

    /**
     * @param integer $sell
     * Remove the items from OnHold. This happens when the Order State changes to Paid
     */
    public function sell($sell){
        $onHold = $this->getOnHold()-$sell;
        $this->setOnHold($onHold);
    }

    /**
     * @param int $release
     * Release items from OnHold back to onHand
     */
    public function release($release){
        $onHold = $this->getOnHold()- $release;
        $onHand = $this->getOnHand() + $release;
        $this->setOnHold($onHold);
        $this->setOnHand($onHand);
    }

    /**
     * @param $quantity
     * @return bool
     * Checks if the Inventory is sufficient to fulfil this Order.
     */
    public function isInventorySufficient($quantity){
        if ($quantity > $this->getOnHand()){
            return true;
        }else{
            return false;
        }
    }


}