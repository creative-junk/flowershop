<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/30/2017
 * Time: 12:21 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="payment_method")
 */
class PaymentMethod
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
    private $methodName;

    /**
     * @Assert\Type("AppBundle\Entity\ProductImages")
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductImages",cascade={"persist"})
     */
    private $methodLogo;
    /**
     * @ORM\Column(type="string",nullable=true)
     */
    private $methodURL;

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
    public function getMethodName()
    {
        return $this->methodName;
    }

    /**
     * @param mixed $methodName
     */
    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    /**
     * @return mixed
     */
    public function getMethodLogo()
    {
        return $this->methodLogo;
    }

    /**
     * @param mixed $methodLogo
     */
    public function setMethodLogo($methodLogo)
    {
        $this->methodLogo = $methodLogo;
    }

    /**
     * @return mixed
     */
    public function getMethodURL()
    {
        return $this->methodURL;
    }

    /**
     * @param mixed $methodURL
     */
    public function setMethodURL($methodURL)
    {
        $this->methodURL = $methodURL;
    }
    function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->methodName;
    }
}