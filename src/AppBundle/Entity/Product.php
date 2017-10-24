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
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Category")
     */
    private $category;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=false})
     */
    private $isSeedling;
    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default"=false})
     *
     */
    private $isOnSale;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @Gedmo\Slug(fields={"title"},updatable=false)
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $description;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $mainImage;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $openHeadTop;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $openHeadSide;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $closedHeadSide;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $openHeadBouquet;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $closedHeadBouquet;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $vaselife;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $stemLength;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $headsize;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $variety;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $color;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $season;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isScented;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean",options={"default"=true})
     */
    private $isActive;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean",options={"default"=true})
     */
    private $isAuthorized;
    /**
     * @ORM\Column(type="boolean",options={"default"=false})
     */
    private $isFeatured;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="roses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendor;

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
     * @return mixed
     */
    public function getIsAuthorized()
    {
        return $this->isAuthorized;
    }

    /**
     * @param mixed $isAuthorized
     */
    public function setIsAuthorized($isAuthorized)
    {
        $this->isAuthorized = $isAuthorized;
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
     * @return User $user
     *
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return Company $company
     *
     */
    public function getGrower()
    {
        return $this->vendor;
    }
    /**
     * @return Company $company
     *
     */
    public function getBreeder(){
        return $this->vendor;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getIsSeedling()
    {
        return $this->isSeedling;
    }

    /**
     * @param mixed $isSeedling
     */
    public function setIsSeedling($isSeedling)
    {
        $this->isSeedling = $isSeedling;
    }

    /**
     * @return mixed
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * @param mixed $isFeatured
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;
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
     * @return mixed
     */
    public function getVaselife()
    {
        return $this->vaselife;
    }

    /**
     * @param mixed $vaselife
     */
    public function setVaselife($vaselife)
    {
        $this->vaselife = $vaselife;
    }

    /**
     * @return mixed
     */
    public function getStemLength()
    {
        return $this->stemLength;
    }

    /**
     * @param mixed $stemLength
     */
    public function setStemLength($stemLength)
    {
        $this->stemLength = $stemLength;
    }

    /**
     * @return mixed
     */
    public function getHeadsize()
    {
        return $this->headsize;
    }

    /**
     * @param mixed $headsize
     */
    public function setHeadsize($headsize)
    {
        $this->headsize = $headsize;
    }


    /**
     * @return mixed
     */
    public function getVariety()
    {
        return $this->variety;
    }

    /**
     * @param mixed $variety
     */
    public function setVariety($variety)
    {
        $this->variety = $variety;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * @param mixed $season
     */
    public function setSeason($season)
    {
        $this->season = $season;
    }

    /**
     * @return mixed
     */
    public function getIsScented()
    {
        return $this->isScented;
    }

    /**
     * @param mixed $isScented
     */
    public function setIsScented($isScented)
    {
        $this->isScented = $isScented;
    }

    /**
     * @return Company
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * @param Company $vendor
     */
    public function setVendor($vendor)
    {
        $this->vendor = $vendor;
    }

    public function __toString(){
        return $this->getTitle();
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
     * @return mixed
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @param mixed $mainImage
     */
    public function setMainImage($mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @return mixed
     */
    public function getOpenHeadTop()
    {
        return $this->openHeadTop;
    }

    /**
     * @param mixed $openHeadTop
     */
    public function setOpenHeadTop($openHeadTop)
    {
        $this->openHeadTop = $openHeadTop;
    }

    /**
     * @return mixed
     */
    public function getOpenHeadSide()
    {
        return $this->openHeadSide;
    }

    /**
     * @param mixed $openHeadSide
     */
    public function setOpenHeadSide($openHeadSide)
    {
        $this->openHeadSide = $openHeadSide;
    }

    /**
     * @return mixed
     */
    public function getClosedHeadSide()
    {
        return $this->closedHeadSide;
    }

    /**
     * @param mixed $closedHeadSide
     */
    public function setClosedHeadSide($closedHeadSide)
    {
        $this->closedHeadSide = $closedHeadSide;
    }

    /**
     * @return mixed
     */
    public function getOpenHeadBouquet()
    {
        return $this->openHeadBouquet;
    }

    /**
     * @param mixed $openHeadBouquet
     */
    public function setOpenHeadBouquet($openHeadBouquet)
    {
        $this->openHeadBouquet = $openHeadBouquet;
    }

    /**
     * @return mixed
     */
    public function getClosedHeadBouquet()
    {
        return $this->closedHeadBouquet;
    }

    /**
     * @param mixed $closedHeadBouquet
     */
    public function setClosedHeadBouquet($closedHeadBouquet)
    {
        $this->closedHeadBouquet = $closedHeadBouquet;
    }


}