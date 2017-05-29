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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 * @ORM\Table(name="cart")
 */
class Cart
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
    private $cartCurrency;
    /**
     * @ORM\Column(type="string")
     */
    private $cartAmount;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $cartTotal;
    /**
     * @ORM\Column(type="integer")
     */
    private $nrItems;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $shippingCost;
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
    private $ownedBy;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CartItems",mappedBy="cart")
     */
    private $cartItems;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->cartItems = new ArrayCollection();

    }
    /**
     * @return mixed
     */
    public function getCartCurrency()
    {
        return $this->cartCurrency;
    }

    /**
     * @param mixed $cartCurrency
     */
    public function setCartCurrency($cartCurrency)
    {
        $this->cartCurrency = $cartCurrency;
    }

    /**
     * @return mixed
     */
    public function getCartAmount()
    {
        return $this->cartAmount;
    }

    /**
     * @param mixed $cartAmount
     */
    public function setCartAmount($cartAmount)
    {
        $this->cartAmount = $cartAmount;
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
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }

    /**
     * @param mixed $ownedBy
     */
    public function setOwnedBy(User $ownedBy)
    {
        $this->ownedBy = $ownedBy;
    }

    /**
     * @return mixed
     */
    public function getNrItems()
    {
        return $this->nrItems;
    }

    /**
     * @param mixed $nrItems
     */
    public function setNrItems($nrItems)
    {
        $this->nrItems = $nrItems;
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
     * @return ArrayCollection [CartItem]
     */
    public function getCartItems()
    {
        return $this->cartItems;
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
    public function getCartTotal()
    {
        return $this->cartTotal;
    }

    /**
     * @param mixed $cartTotal
     */
    public function setCartTotal($cartTotal)
    {
        $this->cartTotal = $cartTotal;
    }

}