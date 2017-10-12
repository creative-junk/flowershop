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
 * @ORM\HasLifecycleCallbacks
 */
class MyList
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="myLists")
     */
    private $listOwner;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $comparisonOwner;
    /**
     * @ORM\Column(type="string")
     */
    private $listType;
    /**
     * @ORM\Column(type="string")
     */
    private $productType;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="myRecommendations")
     */
    private $recommendedBy;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Direct",inversedBy="productList")
     */
    private $product;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AuctionProduct",inversedBy="auctionList")
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

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime()
    {
        // update the modified time
        $this->setUpdatedAt(new \DateTime());
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
    public function getListOwner()
    {
        return $this->listOwner;
    }

    /**
     * @param Company $listOwner
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
     * @return Direct
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Direct $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return AuctionProduct
     */
    public function getAuctionProduct()
    {
        return $this->auctionProduct;
    }

    /**
     * @param AuctionProduct $auctionProduct
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

    /**
     * @return mixed
     */
    public function getComparisonOwner()
    {
        return $this->comparisonOwner;
    }

    /**
     * @param mixed $comparisonOwner
     */
    public function setComparisonOwner($comparisonOwner)
    {
        $this->comparisonOwner = $comparisonOwner;
    }


}