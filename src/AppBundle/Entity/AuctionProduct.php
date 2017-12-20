<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/18/2017
 * Time: 4:51 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuctionProductRepository")
 * @ORM\Table(name="auction_product")
 */
class AuctionProduct
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Auction")
     */
    private $whichAuction;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $assignedStock;
    /**
     * @ORM\Column(type="string")
     */
    private $availableStock;
    /**
     * @ORM\Column(type="integer")
     */
    private $onHand=0;
    /**
     * @ORM\Column(type="integer")
     */
    private $onHold=0;
    /**
     * @ORM\Column(type="string")
     */
    private $pricePerStem;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $previousPrice;
    /**
     * @ORM\Column(type="string")
     */
    private $status;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isOnSale=false;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rating",mappedBy="auction",fetch="EXTRA_LAZY")
     */
    private $reviews;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="auction",fetch="EXTRA_LAZY")
     */
    private $comments;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company")
     */
    private $sellingAgent;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        $this->assignedStock = $this->availableStock;

        $this->comments= new ArrayCollection();
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
     * @return mixed
     */
    public function getAvailableStock()
    {
        return $this->availableStock;
    }

    /**
     * @param mixed $availableStock
     */
    public function setAvailableStock($availableStock)
    {
        $this->availableStock = $availableStock;
    }

    /**
     * @return mixed
     */
    public function getpricePerStem()
    {
        return $this->pricePerStem;
    }

    /**
     * @param mixed $pricePerStem
     */
    public function setpricePerStem($pricePerStem)
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
     * @return Auction
     */
    public function getWhichAuction()
    {
        return $this->whichAuction;
    }

    /**
     * @param Auction $whichAuction
     */
    public function setWhichAuction($whichAuction)
    {
        $this->whichAuction = $whichAuction;
    }

    /**
     * @return ArrayCollection | Rating
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @return ArrayCollection | Comment
     */
    public function getComments()
    {
        return $this->comments;
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
     * @return mixed
     */
    public function getAssignedStock()
    {
        return $this->assignedStock;
    }

    /**
     * @param mixed $assignedStock
     */
    public function setAssignedStock($assignedStock)
    {
        $this->assignedStock = $assignedStock;
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