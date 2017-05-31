<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrderItemsRepository")
 * @ORM\Table(name="order_items")
 */
class OrderItems
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
    private $quantity;
    /**
     * @ORM\Column(type="string")
     */
    private $unitPrice;
    /**
     * @ORM\Column(type="string")
     */
    private $lineTotal;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $itemStatus;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastProcessed;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserOrder",inversedBy="orderItems",cascade={"persist"})
     */
    private $order;
    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="myOrderItems")
     */
    private $vendor;

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
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @param mixed $unitPrice
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
    }

    /**
     * @return mixed
     */
    public function getLineTotal()
    {
        return $this->lineTotal;
    }

    /**
     * @param mixed $lineTotal
     */
    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = $lineTotal;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param mixed $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
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
    public function getItemStatus()
    {
        return $this->itemStatus;
    }

    /**
     * @param mixed $itemStatus
     */
    public function setItemStatus($itemStatus)
    {
        $this->itemStatus = $itemStatus;
    }

    /**
     * @return mixed
     */
    public function getLastProcessed()
    {
        return $this->lastProcessed;
    }

    /**
     * @param mixed $lastProcessed
     */
    public function setLastProcessed($lastProcessed)
    {
        $this->lastProcessed = $lastProcessed;
    }

}