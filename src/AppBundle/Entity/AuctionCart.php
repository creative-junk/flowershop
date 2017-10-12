<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/24/2017
 * Time: 12:31 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="auction_cart")
 */
class AuctionCart
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $cartCurrency;
    /**
     * @ORM\Column(type="string")
     */
    private $cartQuantity;
    /**
     * @ORM\Column(type="string")
     */
    private $itemPrice;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $whoseCart;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AuctionProduct")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());

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
    public function getCartQuantity()
    {
        return $this->cartQuantity;
    }

    /**
     * @param mixed $cartQuantity
     */
    public function setCartQuantity($cartQuantity)
    {
        $this->cartQuantity = $cartQuantity;
    }

    /**
     * @return mixed
     */
    public function getItemPrice()
    {
        return $this->itemPrice;
    }

    /**
     * @param mixed $itemPrice
     */
    public function setItemPrice($itemPrice)
    {
        $this->itemPrice = $itemPrice;
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
    public function getWhoseCart()
    {
        return $this->whoseCart;
    }

    /**
     * @param mixed $whoseCart
     */
    public function setWhoseCart($whoseCart)
    {
        $this->whoseCart = $whoseCart;
    }


    /**
     * @return AuctionProduct
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


}