<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CurrencyRepository")
 * @ORM\Table(name="currency")
 */
class Currency {

    /**
     * @ORM\Id
     * @ORM\Column(type="string",length=3)
     */
    protected $iso;

    /**
     * @ORM\Column(type="string", length=100)
     *
     */
    protected $label;

    /**
     * @ORM\Column(type="float", name="exchange_rate",nullable=true)
     */
    protected $exchangeRate ;


    public function __construct()
    {

    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set iso
     *
     * @param string $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }

    /**
     * Get iso
     *
     * @return string
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * Set exchangeRate
     *
     * @param float $exchangeRate
     * @return Currency
     */
    public function setExchangeRate($exchangeRate)
    {
        $this->exchangeRate = $exchangeRate;

        return $this;
    }

    /**
     * Get exchangeRate
     *
     * @return float
     */
    public function getExchangeRate()
    {
        return $this->exchangeRate;
    }

    public function __toString(){
        return $this->getLabel();
    }

    public function getChangeRateToUSD(){
        return $this->convertToUSD(1);
    }

    public function getChangeRateFromUSD(){
        return $this->convertFromUSD(1);
    }

    public function convertToUSD($nbToConvert)
    {
        return $nbToConvert/$this->getExchangeRate();
    }

    public function convertFromUSD($nbToConvert){
        return $nbToConvert*$this->getExchangeRate();
    }
}