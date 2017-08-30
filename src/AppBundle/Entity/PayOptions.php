<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/30/2017
 * Time: 12:28 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_options")
 */
class PayOptions
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string")
     */
    private $id;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $methodProductionId;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $methodClientKey;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $methodServerKey;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\PaymentMethod")
     */
    private $paymentMethod;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="payOptions")
     */
    private $myCompany;

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
    public function getmethodProductionId()
    {
        return $this->methodProductionId;
    }

    /**
     * @param mixed $methodProductionId
     */
    public function setmethodProductionId($methodProductionId)
    {
        $this->methodProductionId = $methodProductionId;
    }

    /**
     * @return mixed
     */
    public function getMethodClientKey()
    {
        return $this->methodClientKey;
    }

    /**
     * @param mixed $methodClientKey
     */
    public function setMethodClientKey($methodClientKey)
    {
        $this->methodClientKey = $methodClientKey;
    }

    /**
     * @return mixed
     */
    public function getMethodServerKey()
    {
        return $this->methodServerKey;
    }

    /**
     * @param mixed $methodServerKey
     */
    public function setMethodServerKey($methodServerKey)
    {
        $this->methodServerKey = $methodServerKey;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @param mixed $method
     */
    public function setPaymentMethod($method)
    {
        $this->paymentMethod = $method;
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

}