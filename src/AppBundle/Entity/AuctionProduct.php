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
     * @ORM\Column(type="string")
     */
    private $pricePerStem;
    /**
     * @ORM\Column(type="string")
     */
    private $status;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

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


}