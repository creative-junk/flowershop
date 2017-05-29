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
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\Table(name="product")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
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
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    private $summary;
    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="imageName", size="imageSize")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $imageName;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $imageSize;
    /**
     * @ORM\Column(type="string")
     */
    private $currency;
    /**
     * @Assert\NotBlank()
     * @Assert\Range(min=0,minMessage="Price has to be Greater than 0")
     * @ORM\Column(type="string")
     */
    private $price;
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
    private $quality;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $color;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $season;
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
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User",inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="product")
     */
    private $productList;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->productList = new ArrayCollection();

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
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @return User $user
     *
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @return User $user
     *
     */
    public function getGrower()
    {
        return $this->user;
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

    public function __toString(){
        return $this->getTitle();
    }

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
            //Lets make sure at least one field changes so Doctrine can process the file
            $this->updatedAt = new \DateTimeImmutable();
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param integer $imageSize
     * @return Product
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;

        return $this;
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
     * @return ArrayCollection[Product]
     */
    public function getProductList()
    {
        return $this->productList;
    }


}