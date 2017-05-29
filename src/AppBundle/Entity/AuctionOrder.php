<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="auction_order")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuctionOrderRepository")
 */
class AuctionOrder
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
    private $orderCurrency;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BillingAddress")
     */
    private $billingAddress;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ShippingAddress")
     */
    private $shippingAddress;
    /**
     * @ORM\Column(type="string")
     */
    private $orderAmount;
    /**
     * @ORM\Column(type="string")
     */
    private $shippingCost;
    /**
     * @ORM\Column(type="string")
     */
    private $orderTotal;
    /**
     * @ORM\Column(type="string")
     */
    private $processingFee;
    /**
     * @ORM\Column(type="string")
     */
    private $orderState;
    /**
     * @ORM\Column(type="text")
     */
    private $orderNotes;
    /**
     * @ORM\Column(type="datetime")
     */
    private $checkoutCompletedAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AuctionOrderItems",mappedBy="order")
     */
    private $orderItems;
    /**
     * @ORM\Column(type="string")
     */
    private $orderStatus;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $whoseOrder;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;


    function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->orderItems = new ArrayCollection();
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
    public function getOrderCurrency()
    {
        return $this->orderCurrency;
    }

    /**
     * @param mixed $orderCurrency
     */
    public function setOrderCurrency($orderCurrency)
    {
        $this->orderCurrency = $orderCurrency;
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
    public function getOrderAmount()
    {
        return $this->orderAmount;
    }

    /**
     * @param mixed $orderAmount
     */
    public function setOrderAmount($orderAmount)
    {
        $this->orderAmount = $orderAmount;
    }

    /**
     * @return mixed
     */
    public function getOrderState()
    {
        return $this->orderState;
    }

    /**
     * @param mixed $orderState
     */
    public function setOrderState($orderState)
    {
        $this->orderState = $orderState;
    }

    /**
     * @return mixed
     */
    public function getOrderNotes()
    {
        return $this->orderNotes;
    }

    /**
     * @param mixed $orderNotes
     */
    public function setOrderNotes($orderNotes)
    {
        $this->orderNotes = $orderNotes;
    }

    /**
     * @return mixed
     */
    public function getCheckoutCompletedAt()
    {
        return $this->checkoutCompletedAt;
    }

    /**
     * @param mixed $checkoutCompletedAt
     */
    public function setCheckoutCompletedAt($checkoutCompletedAt)
    {
        $this->checkoutCompletedAt = $checkoutCompletedAt;
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
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * @param mixed $orderStatus
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;
    }



    /**
     * @return mixed
     */
    public function getShippingCost()
    {
        return $this->shippingCost;
    }

    /**
     * @param mixed $shippingCost
     */
    public function setShippingCost($shippingCost)
    {
        $this->shippingCost = $shippingCost;
    }

    /**
     * @return mixed
     */
    public function getOrderTotal()
    {
        return $this->orderTotal;
    }

    /**
     * @param mixed $orderTotal
     */
    public function setOrderTotal($orderTotal)
    {
        $this->orderTotal = $orderTotal;
    }

    /**
     * @return mixed
     */
    public function getProcessingFee()
    {
        return $this->processingFee;
    }

    /**
     * @param mixed $processingFee
     */
    public function setProcessingFee($processingFee)
    {
        $this->processingFee = $processingFee;
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
     * @return User
     */
    public function getWhoseOrder()
    {
        return $this->whoseOrder;
    }

    /**
     * @param User $whoseOrder
     */
    public function setWhoseOrder($whoseOrder)
    {
        $this->whoseOrder = $whoseOrder;
    }

    /**
     * @return User
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param User $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }

    /**
     * @return ArrayCollection[AuctionOrderItems]
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * @param ArrayCollection[AuctionOrderItems]
     */
    public function setOrderItems($orderItems)
    {
        $this->orderItems = $orderItems;
    }


}