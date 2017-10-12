<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 9/19/2017
 * Time: 11:38 AM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="shipping_options")
 */
class ShippingOptions
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
    private $shippingMethodName;
    /**
     * @ORM\Column(type="string")
     */
    private $shippingMethodCost;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Company",inversedBy="shippingOptions")
     */
    private $ownedBy;

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
    public function getShippingMethodName()
    {
        return $this->shippingMethodName;
    }

    /**
     * @param mixed $shippingMethodName
     */
    public function setShippingMethodName($shippingMethodName)
    {
        $this->shippingMethodName = $shippingMethodName;
    }

    /**
     * @return mixed
     */
    public function getShippingMethodCost()
    {
        return $this->shippingMethodCost;
    }

    /**
     * @param mixed $shippingMethodCost
     */
    public function setShippingMethodCost($shippingMethodCost)
    {
        $this->shippingMethodCost = $shippingMethodCost;
    }

    /**
     * @return mixed
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }

    /**
     * @param mixed $ownedBy
     */
    public function setOwnedBy($ownedBy)
    {
        $this->ownedBy = $ownedBy;
    }

}