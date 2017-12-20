<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 12/1/2017
 * Time: 1:41 PM
 ********************************************************************************/

namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Payment as BasePayment;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaymentRepository")
 * @ORM\Table(name="payment")
 */
class Payment extends BasePayment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     *
     * @var integer $id
     */
    protected $id;
    /**
     * @var UserOrder
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\UserOrder",inversedBy="payments")
     */
    private $order;
    /**
     * @var
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\AuctionOrder")
     */
    private $auctionOrder;
    /**
     * @ORM\Column(type="string")
     */
    private $gateway;
    /**
     * @ORM\Column(type="string")
     */
    private $paymentAmount;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $paidAt;
    /**
     * @return UserOrder
     */
    public function getOrder()
    {
        return $this->order;

    }

    /**
     * @param UserOrder $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
        $this->setPaidAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getAuctionOrder()
    {
        return $this->auctionOrder;
    }

    /**
     * @param mixed $auctionOrder
     */
    public function setAuctionOrder($auctionOrder)
    {
        $this->auctionOrder = $auctionOrder;
        $this->setPaidAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        return $this->paymentAmount;
    }

    /**
     * @param mixed $paymentAmount
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->paymentAmount = $paymentAmount;
    }

    /**
     * @return mixed
     */
    public function getPaidAt()
    {
        return $this->paidAt;
    }

    /**
     * @param mixed $paidAt
     */
    public function setPaidAt($paidAt)
    {
        $this->paidAt = $paidAt;
    }


}