<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 11/8/2017
 * Time: 3:16 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="shipping_rate")
 * @ORM\HasLifecycleCallbacks
 */
class ShippingRate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="Airport")
     */
    private $airport;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Airline")
     */
    private $airline;
    /**
     * @ORM\Column(type="string")
     */
    private $minimumCharge="300";
    /**
     * @ORM\Column(type="string")
     */
    private $baseRate="0.0";
    /**
     * @ORM\Column(type="string")
     */
    private $firstIncrement="0.0";
    /**
     * @ORM\Column(type="string")
     */
    private $secondIncrement="0.0";
    /**
     * @ORM\Column(type="string")
     */
    private $thirdIncrement="0.0";
    /**
     * @ORM\Column(type="string")
     */
    private $fourthIncrement="0.0";
    /**
     * @ORM\Column(type="string")
     */
    private $fifthIncrement="0.0";
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $sixthIncrement="0.0";

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $createdBy;
    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $updatedBy;


    function __construct()
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
     * @return Airport
     */
    public function getAirport()
    {
        return $this->airport;
    }

    /**
     * @param Airport $airport
     */
    public function setAirport($airport)
    {
        $this->airport = $airport;
    }

    /**
     * @return Airline
     */
    public function getAirline()
    {
        return $this->airline;
    }

    /**
     * @param Airline $airline
     */
    public function setAirline($airline)
    {
        $this->airline = $airline;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getMinimumCharge()
    {
        return $this->minimumCharge;
    }

    /**
     * @param mixed $minimumCharge
     */
    public function setMinimumCharge($minimumCharge)
    {
        $this->minimumCharge = $minimumCharge;
    }

    /**
     * @return mixed
     */
    public function getBaseRate()
    {
        return $this->baseRate;
    }

    /**
     * @param mixed $baseRate
     */
    public function setBaseRate($baseRate)
    {
        $this->baseRate = $baseRate;
    }

    /**
     * @return mixed
     */
    public function getFirstIncrement()
    {
        return $this->firstIncrement;
    }

    /**
     * @param mixed $firstIncrement
     */
    public function setFirstIncrement($firstIncrement)
    {
        $this->firstIncrement = $firstIncrement;
    }

    /**
     * @return mixed
     */
    public function getSecondIncrement()
    {
        return $this->secondIncrement;
    }

    /**
     * @param mixed $secondIncrement
     */
    public function setSecondIncrement($secondIncrement)
    {
        $this->secondIncrement = $secondIncrement;
    }

    /**
     * @return mixed
     */
    public function getThirdIncrement()
    {
        return $this->thirdIncrement;
    }

    /**
     * @param mixed $thirdIncrement
     */
    public function setThirdIncrement($thirdIncrement)
    {
        $this->thirdIncrement = $thirdIncrement;
    }

    /**
     * @return mixed
     */
    public function getFourthIncrement()
    {
        return $this->fourthIncrement;
    }

    /**
     * @param mixed $fourthIncrement
     */
    public function setFourthIncrement($fourthIncrement)
    {
        $this->fourthIncrement = $fourthIncrement;
    }

    /**
     * @return mixed
     */
    public function getFifthIncrement()
    {
        return $this->fifthIncrement;
    }

    /**
     * @param mixed $fifthIncrement
     */
    public function setFifthIncrement($fifthIncrement)
    {
        $this->fifthIncrement = $fifthIncrement;
    }

    /**
     * @return mixed
     */
    public function getSixthIncrement()
    {
        return $this->sixthIncrement;
    }

    /**
     * @param mixed $sixthIncrement
     */
    public function setSixthIncrement($sixthIncrement)
    {
        $this->sixthIncrement = $sixthIncrement;
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