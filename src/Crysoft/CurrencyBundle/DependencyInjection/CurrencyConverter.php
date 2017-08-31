<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/31/2017
 * Time: 11:42 AM
 ********************************************************************************/

namespace Crysoft\CurrencyBundle\DependencyInjection;
use Doctrine\ORM\EntityManager;

class CurrencyConverter
{
    protected $em;
    protected $rates;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->rates = Array();
    }
    public function getExchangeRate($isoFrom,$isoTo){
        if (!key_exists($isoFrom,$this->rates)){
            $this->rates[$isoFrom] = $this->em->getRepository("AppBundle:Currency")->findOneBy([
                'iso'=>$isoFrom
            ]);
        }
        if (!key_exists($isoTo,$this->rates)){
            $this->rates[$isoTo] = $this->em->getRepository("AppBundle:Currency")->findOneBy([
                'iso'=>$isoTo
            ]);
        }

        if (!key_exists($isoFrom,$this->rates)){
            throw new \Exception("$isoFrom does not exist in Obtao currency database");
        }
        if (!key_exists($isoTo,$this->rates)){
            throw new \Exception("$isoTo does not exist in Obtao currency database");
        }

        return $this->rates[$isoFrom]->getChangeRateToUSD()*$this->rates[$isoTo]->getChangeRateFromUSD();
    }
    public function convertAmount($amount,$isoFrom,$isoTo){
        return $amount * $this->getExchangeRate($isoFrom,$isoTo);
    }
}