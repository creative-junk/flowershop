<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/31/2017
 * Time: 11:59 AM
 ********************************************************************************/

namespace Crysoft\CurrencyBundle\Twig\Extensions;

use Crysoft\CurrencyBundle\DependencyInjection\CurrencyConverter;


class CrysoftTwigCurrency extends \Twig_Extension
{
    private $converter;

    function __construct(CurrencyConverter $converter)
    {
        $this->converter = $converter;
    }
    public function getName()
    {
        return 'crysoft_currency';
    }
    public function getFilters()
    {
        return array(
            'convertCurrency' => new \Twig_Filter_Method($this,'getConversionBetween')
        );
    }
    public function getConversionBetween($amount,$isoFrom,$isoTo){
        try{
            $value = $this->converter->convertAmount($amount,$isoFrom,$isoTo);
            return number_format(round($value,2),2);
        }catch (\Exception $e){
            return "?";
        }
    }
}