<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/19/2017
 ********************************************************************************/

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AuctionRepository")
 * @ORM\Table(name="auction")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 */
class Auction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
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
    private $quantity;
    /**
     * @ORM\Column(type="string")
     */
    private $currency;
    /**
     * @ORM\Column(type="string")
     */
    private $bundlePrice;
    /**
     * @ORM\Column(type="string")
     */
    private $finalPrice;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="boolean")
     */
    private $isAuthorized;

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
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     *
     */
    private $agent;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $status;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="auctionProduct",fetch="EXTRA_LAZY")
     */
    private $auctionList;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->auctionList= new ArrayCollection();

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
    public function getPrice()
    {
        return $this->finalPrice;
    }


    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->finalPrice = $price;
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
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }



    /**
     * @return mixed
     */
    public function getFinalPrice()
    {
        return $this->finalPrice;
    }

    /**
     * @param mixed $finalPrice
     */
    public function setFinalPrice($finalPrice)
    {
        $this->finalPrice = $finalPrice;
    }

    /**
     * @return User
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param mixed $agent
     */
    public function setAgent(User $agent)
    {
        $this->agent = $agent;
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

    /**
     * @return mixed
     */
    public function getBundlePrice()
    {
        return $this->bundlePrice;
    }

    /**
     * @param mixed $bundlePrice
     */
    public function setBundlePrice($bundlePrice)
    {
        $this->bundlePrice = $bundlePrice;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
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
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param mixed $imageSize
     */
    public function setImageSize($imageSize)
    {
        $this->imageSize = $imageSize;
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
    function __toString()
    {

       return $this->title;
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
     * @return ArrayCollection[MyList]
     */
    public function getAuctionList()
    {
        return $this->auctionList;
    }

}