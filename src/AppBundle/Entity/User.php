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
use FOS\MessageBundle\Model\ParticipantInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @ORM\HasLifecycleCallbacks
 * @UniqueEntity(fields={"email"},message="It looks like you already have an account!")
 */
class User implements UserInterface
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
     * @ORM\Column(type="json_array")
     */
    private $roles =[];

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;
    /**
     * @ORM\Column(type="boolean")
     */
    private $isMainAccount;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $lastLoginTime;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isPasswordCreated;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $accountCreatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $accountCreatedBy;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $passwordResetToken;
    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $isResetTokenValid;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $approvedBy;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $myCompany;
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product",mappedBy="user",fetch="EXTRA_LAZY")
     */
    private $products;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment",mappedBy="user",fetch="EXTRA_LAZY")
     */
    private $myComments;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $updatedBy;

    public function __construct()
    {
        // we set up "created"+"modified"
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
        $this->products = new ArrayCollection();
        $this->myComments = new ArrayCollection();

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
        //when changing the plainPassword
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
     * @return boolean
     */
    public function getIsMainAccount()
    {
        return $this->isMainAccount;
    }

    /**
     * @param boolean $isMainAccount
     */
    public function setIsMainAccount($isMainAccount)
    {
        $this->isMainAccount = $isMainAccount;
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
     * @return ArrayCollection[Products]
     */
    public function getProducts()
    {
        return $this->products;
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
    public function getMyCompany()
    {
        return $this->myCompany;
    }

    /**
     * @param mixed $myCompany
     */
    public function setMyCompany($myCompany)
    {
        $this->myCompany = $myCompany;
    }

    /**
     * @return mixed
     */
    public function getMyComments()
    {
        return $this->myComments;
    }

    /**
     * @return mixed
     */
    public function getIsPasswordCreated()
    {
        return $this->isPasswordCreated;
    }

    /**
     * @param mixed $isPasswordCreated
     */
    public function setIsPasswordCreated($isPasswordCreated)
    {
        $this->isPasswordCreated = $isPasswordCreated;
    }

    /**
     * @return mixed
     */
    public function getAccountCreatedAt()
    {
        return $this->accountCreatedAt;
    }

    /**
     * @param mixed $accountCreatedAt
     */
    public function setAccountCreatedAt($accountCreatedAt)
    {
        $this->accountCreatedAt = $accountCreatedAt;
    }

    /**
     * @return mixed
     */
    public function getAccountCreatedBy()
    {
        return $this->accountCreatedBy;
    }

    /**
     * @param mixed $accountCreatedBy
     */
    public function setAccountCreatedBy($accountCreatedBy)
    {
        $this->accountCreatedBy = $accountCreatedBy;
    }

    /**
     * @return mixed
     */
    public function getPasswordResetToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * @param mixed $passwordResetToken
     */
    public function setPasswordResetToken($passwordResetToken)
    {
        $this->passwordResetToken = $passwordResetToken;
    }

    /**
     * @return mixed
     */
    public function getIsResetTokenValid()
    {
        return $this->isResetTokenValid;
    }

    /**
     * @param mixed $isResetTokenValid
     */
    public function setIsResetTokenValid($isResetTokenValid)
    {
        $this->isResetTokenValid = $isResetTokenValid;
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




}