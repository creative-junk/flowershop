<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/25/2017
 * Time: 3:55 PM
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MyListRepository")
 * @ORM\Table(name="my_list")
 */
class MyList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="myLists")
     */
    private $listOwner;
    /**
     * @ORM\Column(type="string")
     */
    private $listType;
    /**
     * @ORM\Column(type="string")
     */
    private $productType;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="myRecommendations")
     */
    private $recommendedBy;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product",inversedBy="productList")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Auction",inversedBy="auctionList")
     */
    private $auctionProduct;
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

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
    public function getListOwner()
    {
        return $this->listOwner;
    }

    /**
     * @param User $listOwner
     */
    public function setListOwner($listOwner)
    {
        $this->listOwner = $listOwner;
    }

    /**
     * @return mixed
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @param mixed $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * @return mixed
     */
    public function getProductType()
    {
        return $this->productType;
    }

    /**
     * @param mixed $productType
     */
    public function setProductType($productType)
    {
        $this->productType = $productType;
    }

    /**
     * @return User
     */
    public function getRecommendedBy()
    {
        return $this->recommendedBy;
    }

    /**
     * @param User $recommendedBy
     */
    public function setRecommendedBy($recommendedBy)
    {
        $this->recommendedBy = $recommendedBy;
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
     * @return Auction
     */
    public function getAuctionProduct()
    {
        return $this->auctionProduct;
    }

    /**
     * @param Auction $auctionProduct
     */
    public function setAuctionProduct($auctionProduct)
    {
        $this->auctionProduct = $auctionProduct;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


}