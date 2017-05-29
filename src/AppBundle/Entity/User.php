<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/17/2017
 ********************************************************************************/

namespace AppBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields={"email"},message="It looks like you already have an account!")
 */
class User implements UserInterface
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
    private $firstName;
    /**
     * @ORM\Column(type="string")
     */
    private $lastName;
    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(type="string",unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @Assert\NotBlank(groups={"Registration"})
     */

    private $plainPassword;

    /**
     * @ORM\Column(type="string")
     */
    private $userType;
    /**
     * @ORM\Column(type="json_array")
     */
    private $roles =[];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    /**
     * @ORM\Column(type="string",nullable=true,options={"default"="$"})
     */
    private $currency;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastLoginTime;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product",mappedBy="user",fetch="EXTRA_LAZY")
     */
    private $products;
    /**
     * @ORM\OneToMany(targetEntity="BuyerAgent",mappedBy="buyer",fetch="EXTRA_LAZY")
     */
    private $buyerAgents;
    /**
     * @ORM\OneToMany(targetEntity="BuyerAgent",mappedBy="agent",fetch="EXTRA_LAZY")
     */
    private $agentBuyers;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="buyer",fetch="EXTRA_LAZY")
     */
    private $buyerGrowers;
    /**
     * @ORM\OneToMany(targetEntity="BuyerGrower",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBuyers;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GrowerAgent",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerAgents;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\GrowerAgent",mappedBy="agent",fetch="EXTRA_LAZY")
     */
    private $agentGrowers;

    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="grower",fetch="EXTRA_LAZY")
     */
    private $growerBreeders;
    /**
     * @ORM\OneToMany(targetEntity="GrowerBreeder",mappedBy="breeder",fetch="EXTRA_LAZY")
     */
    private $breederGrowers;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="listOwner",fetch="EXTRA_LAZY")
     */
    private $myLists;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\MyList",mappedBy="recommendedBy",fetch="EXTRA_LAZY")
     */
    private $myRecommendations;


    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->buyerAgents = new ArrayCollection();
        $this->agentBuyers = new ArrayCollection();
        $this->growerAgents = new ArrayCollection();
        $this->agentGrowers = new ArrayCollection();
        $this->myLists = new ArrayCollection();
        $this->myRecommendations = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
           return $this->email;
    }
    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }
    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        $roles = $this->roles;
        if(!in_array('ROLE_USER',$roles)){
            $roles[]='ROLE_USER';
        }
        return $roles;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {

    }



    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        $this->plainPassword=null;
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
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        //Guarantees that the entity looks "dirty" to Doctrine
        //when chaning the plainPassword
        $this->password=null;
    }


    public function setRoles($roles)
    {
        $this->roles = $roles;
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
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
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @return mixed
     */
    public function getLastLoginTime()
    {
        return $this->lastLoginTime;
    }

    /**
     * @param mixed $lastLoginTime
     */
    public function setLastLoginTime($lastLoginTime)
    {
        $this->lastLoginTime = $lastLoginTime;
    }

    public function __toString()
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getFullName(){
        return trim($this->getFirstName() . ' ' . $this->getLastName());
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
    public function getBuyerGrowers()
    {
        return $this->buyerGrowers;
    }

    /**
     * @param mixed $buyerGrowers
     */
    public function setBuyerGrowers($buyerGrowers)
    {
        $this->buyerGrowers = $buyerGrowers;
    }

    /**
     * @return mixed
     */
    public function getGrowerBuyers()
    {
        return $this->growerBuyers;
    }

    /**
     * @param mixed $growerBuyers
     */
    public function setGrowerBuyers($growerBuyers)
    {
        $this->growerBuyers = $growerBuyers;
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
    public function getGrowerBreeders()
    {
        return $this->growerBreeders;
    }

    /**
     * @param mixed $growerBreeders
     */
    public function setGrowerBreeders($growerBreeders)
    {
        $this->growerBreeders = $growerBreeders;
    }

    /**
     * @return mixed
     */
    public function getBreederGrowers()
    {
        return $this->breederGrowers;
    }

    /**
     * @param mixed $breederGrowers
     */
    public function setBreederGrowers($breederGrowers)
    {
        $this->breederGrowers = $breederGrowers;
    }

    /**
     * @return ArrayCollection[Products]
     */
    public function getProducts()
    {
        return $this->products;
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


}