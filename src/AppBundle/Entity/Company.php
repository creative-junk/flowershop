<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/23/2017
 * Time: 6:42 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CompanyRepository")
 * @ORM\Table(name="company")
 * @ORM\HasLifecycleCallbacks
 * @Vich\Uploadable
 * @UniqueEntity(fields={"email"},message="It looks like you already have an account!")
 */
class Company
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
    private $companyCode;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Company name MUST be provided")
     */
    private $companyName;

    /**
     * @ORM\Column(type="string")
     */
    private $companyType;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isActive;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isPaid;
    /**
     * @Vich\UploadableField(mapping="product_image", fileNameProperty="logoName", size="logoSize")
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $logoName;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $logoSize;
    /* @Assert\Type("AppBundle\Entity\ProductImages")
    * @Assert\Valid()
    * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
    */
    private $image1;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image2;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image3;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image4;
    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $image5;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $altitude;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Country MUST be selected")
     */
    private $country;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Currency MUST be provided")
     */
    private $currency;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $numberOfVarieties;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $aboutCompany;
    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $numberOfEmployees;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Telephone number MUST be provided")
     */
    private $telephoneNumber;
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Email Address MUST be provided")
     */
    private $email;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $website;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $emailAddress;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $facebookPage;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $twitterPage;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $instagramPage;
    /**
     * @ORM\Column(type="string",nullable=true)
     * @Assert\NotBlank(message="Kindly tell us how you first heard about us")
     */
    private $reference;

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
    private $createdBy;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $updatedBy;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User",mappedBy="myCompany",fetch="EXTRA_LAZY")
     */
    private $users;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\BillingAddress",mappedBy="company",fetch="EXTRA_LAZY")
     */
    private $billingAddress;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ShippingAddress",mappedBy="company",fetch="EXTRA_LAZY")
     */
    private $shippingAddress;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Direct",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $roses;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Auction",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $auctionProducts;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="buyer",fetch="EXTRA_LAZY")
     */
    private $buyerGrowers;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBuyers;

    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBreeders;
    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="breeder",fetch="EXTRA_LAZY")
     */
    private $breederGrowers;
    /**
     * @ORM\OneToMany(targetEntity="BuyerAgent",mappedBy="buyer",fetch="EXTRA_LAZY")
     */
    private $buyerAgents;
    /**
     * @ORM\OneToMany(targetEntity="BuyerAgent",mappedBy="agent",fetch="EXTRA_LAZY")
     */
    private $agentBuyers;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GrowerAgent",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerAgents;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GrowerAgent",mappedBy="agent",fetch="EXTRA_LAZY")
     */
    private $agentGrowers;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $comments;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Rating",mappedBy="vendor",fetch="EXTRA_LAZY")
     */
    private $reviews;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\OrderItems",mappedBy="vendor",fetch="EXTRA_LAZY")
     *
     */
    private $myOrderItems;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="recommendedBy",fetch="EXTRA_LAZY")
     */
    private $myRecommendations;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="listOwner",fetch="EXTRA_LAZY")
     */
    private $myLists;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $approvedBy;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $approvedOn;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->users = new ArrayCollection();
        $this->billingAddress =  new ArrayCollection();
        $this->shippingAddress =  new ArrayCollection();
        $this->growerBreeders = new ArrayCollection();
        $this->growerBuyers =  new ArrayCollection();
        $this->breederGrowers = new ArrayCollection();
        $this->buyerGrowers = new ArrayCollection();
        $this->roses = new ArrayCollection();
        $this->auctionProducts = new ArrayCollection();
        $this->myOrderItems = new ArrayCollection();
        $this->buyerAgents = new ArrayCollection();
        $this->agentBuyers = new ArrayCollection();
        $this->growerAgents = new ArrayCollection();
        $this->agentGrowers = new ArrayCollection();
        $this->myLists = new ArrayCollection();
        $this->myRecommendations = new ArrayCollection();
        $this->myReceivedAgencyOrders = new ArrayCollection();
        $this->myOrderItems = new ArrayCollection();

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
    public function getCompanyCode()
    {
        return $this->companyCode;
    }

    /**
     * @param mixed $companyCode
     */
    public function setCompanyCode($companyCode)
    {
        $this->companyCode = $companyCode;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getLogoName()
    {
        return $this->logoName;
    }

    /**
     * @param mixed $imageName
     */
    public function setLogoName($logoName)
    {
        $this->logoName = $logoName;
    }

    /**
     * @return File|null
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $imageFile
     * @return Company
     */
    public function setLogoFile(File $image = null)
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
    public function getLogoSize()
    {
        return $this->logoSize;
    }

    /**
     * @param integer $logoSize
     * @return Company
     */
    public function setLogoSize($logoSize)
    {
        $this->logoSize = $logoSize;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAltitude()
    {
        return $this->altitude;
    }

    /**
     * @param mixed $altitude
     */
    public function setAltitude($altitude)
    {
        $this->altitude = $altitude;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return integer
     */
    public function getNumberOfVarieties()
    {
        return $this->numberOfVarieties;
    }

    /**
     * @param integer $numberOfVarieties
     */
    public function setNumberOfVarieties($numberOfVarieties)
    {
        $this->numberOfVarieties = $numberOfVarieties;
    }

    /**
     * @return mixed
     */
    public function getAboutCompany()
    {
        return $this->aboutCompany;
    }

    /**
     * @param mixed $aboutCompany
     */
    public function setAboutCompany($aboutCompany)
    {
        $this->aboutCompany = $aboutCompany;
    }

    /**
     * @return integer
     */
    public function getNumberOfEmployees()
    {
        return $this->numberOfEmployees;
    }

    /**
     * @param integer $numberOfEmployees
     */
    public function setNumberOfEmployees($numberOfEmployees)
    {
        $this->numberOfEmployees = $numberOfEmployees;
    }

    /**
     * @return mixed
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * @param mixed $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * @param mixed $emailAddress
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return mixed
     */
    public function getFacebookPage()
    {
        return $this->facebookPage;
    }

    /**
     * @param mixed $facebookPage
     */
    public function setFacebookPage($facebookPage)
    {
        $this->facebookPage = $facebookPage;
    }

    /**
     * @return mixed
     */
    public function getTwitterPage()
    {
        return $this->twitterPage;
    }

    /**
     * @param mixed $twitterPage
     */
    public function setTwitterPage($twitterPage)
    {
        $this->twitterPage = $twitterPage;
    }

    /**
     * @return mixed
     */
    public function getInstagramPage()
    {
        return $this->instagramPage;
    }

    /**
     * @param mixed $instagramPage
     */
    public function setInstagramPage($instagramPage)
    {
        $this->instagramPage = $instagramPage;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
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
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * @param mixed $isPaid
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
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
     * @return mixed
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param mixed $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return mixed
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }
    public function __toString()
    {
        return $this->getCompanyName();
    }

    /**
     * @return mixed
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @param mixed $companyType
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;
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
    public function getRoses()
    {
        return $this->roses;
    }

    /**
     * @return mixed
     */
    public function getBuyerGrowers()
    {
        return $this->buyerGrowers;
    }

    /**
     * @return mixed
     */
    public function getGrowerBuyers()
    {
        return $this->growerBuyers;
    }

    /**
     * @return mixed
     */
    public function getGrowerBreeders()
    {
        return $this->growerBreeders;
    }

    /**
     * @return mixed
     */
    public function getBreederGrowers()
    {
        return $this->breederGrowers;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @return mixed
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @return mixed
     */
    public function getAuctionProducts()
    {
        return $this->auctionProducts;
    }

    /**
     * @return mixed
     */
    public function getMyOrderItems()
    {
        return $this->myOrderItems;
    }
    /**
     * @return ArrayCollection|BuyerAgent[]
     */
    public function getBuyerAgents()
    {
        return $this->buyerAgents;
    }

    /**
     * @param mixed $buyerAgents
     */
    public function setBuyerAgents($buyerAgents)
    {
        $this->buyerAgents = $buyerAgents;
    }

    /**
     * @return ArrayCollection|BuyerAgent[]
     */
    public function getAgentBuyers()
    {
        return $this->agentBuyers;
    }

    /**
     * @param mixed $agentBuyers
     */
    public function setAgentBuyers($agentBuyers)
    {
        $this->agentBuyers = $agentBuyers;
    }



    /**
     * @return mixed
     */
    public function getGrowerAgents()
    {
        return $this->growerAgents;
    }

    /**
     * @param mixed $growerAgents
     */
    public function setGrowerAgents($growerAgents)
    {
        $this->growerAgents = $growerAgents;
    }

    /**
     * @return mixed
     */
    public function getAgentGrowers()
    {
        return $this->agentGrowers;
    }

    /**
     * @param mixed $agentGrowers
     */
    public function setAgentGrowers($agentGrowers)
    {
        $this->agentGrowers = $agentGrowers;
    }

    /**
     * @return mixed
     */
    public function getMyLists()
    {
        return $this->myLists;
    }

    /**
     * @return mixed
     */
    public function getMyRecommendations()
    {
        return $this->myRecommendations;
    }

    /**
     * @return mixed
     */
    public function getMyReceivedAgencyOrders()
    {
        return $this->myReceivedAgencyOrders;
    }

    /**
     * @return mixed
     */
    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    /**
     * @param mixed $approvedBy
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approvedBy = $approvedBy;
    }

    /**
     * @return mixed
     */
    public function getApprovedOn()
    {
        return $this->approvedOn;
    }

    /**
     * @param mixed $approvedOn
     */
    public function setApprovedOn($approvedOn)
    {
        $this->approvedOn = $approvedOn;
    }

    /**
     * @return mixed
     */
    public function getImage1()
    {
        return $this->image1;
    }

    /**
     * @param mixed $image1
     */
    public function setImage1($image1)
    {
        $this->image1 = $image1;
    }

    /**
     * @return mixed
     */
    public function getImage2()
    {
        return $this->image2;
    }

    /**
     * @param mixed $image2
     */
    public function setImage2($image2)
    {
        $this->image2 = $image2;
    }

    /**
     * @return mixed
     */
    public function getImage3()
    {
        return $this->image3;
    }

    /**
     * @param mixed $image3
     */
    public function setImage3($image3)
    {
        $this->image3 = $image3;
    }

    /**
     * @return mixed
     */
    public function getImage4()
    {
        return $this->image4;
    }

    /**
     * @param mixed $image4
     */
    public function setImage4($image4)
    {
        $this->image4 = $image4;
    }

    /**
     * @return mixed
     */
    public function getImage5()
    {
        return $this->image5;
    }

    /**
     * @param mixed $image5
     */
    public function setImage5($image5)
    {
        $this->image5 = $image5;
    }



}