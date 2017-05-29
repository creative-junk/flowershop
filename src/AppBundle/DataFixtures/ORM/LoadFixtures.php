<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/17/2017
 ********************************************************************************/

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );

    }

    public function category(){
        $cat = [
            'alba',
            'austin',
            'ayshire',
            'banksiae',
            'bourbon',
            'boursault',
            'bulk',
            'centifolia',
            'china',
            'damask'
        ];
        $key = array_rand($cat);

        return $cat[$key];

    }
    public function product(){
        $prod = [
            'Rush of Color Assorted Tulip Bouquet',
            'Colorful World Gerbera Daisy Bouquet',
            'The FTD Spring Garden Bouquet',
            'The Sunny Sentiments Bouquet by FTD',
            'The FTD Touch of Spring Bouquet',
            'The FTD Sunlit Meadows Bouquet by Better Homes and Gardens',
            'Mixed 1 Dozen Long Stem Roses',
            'The FTD Spring Tulip Bouquet by Better Homes and Gardens',
            'The FTD Beyond Blue Bouquet - VASE INCLUDED',
            'The Precious Heart Bouquet by FTD - VASE INCLUDED'
        ];
        $key = array_rand($prod);

        return $prod[$key];

    }
    public function imageurl(){
        $image = [
            'image01.jpg',
            'image02.jpg',
            'image03.jpg',
            'image04.jpg',
            'image05.jpg',
            'image06.jpg',
            'image07.jpg',
        ];
        $key = array_rand($image);

        return $image[$key];

    }
    public function userRole(){
        $image = [
            ['ROLE_ADMIN'],
            ['ROLE_GROWER'],
            ['ROLE_BREEDER'],
            ['ROLE_AGENT'],
            ['ROLE_BUYER'],
        ];
        $key = array_rand($image);

        return $image[$key];

    }

    public function userType()
    {
        $image = [
            'admin',
            'grower',
            'breeder',
            'agent',
            'buyer',
        ];
        $key = array_rand($image);

        return $image[$key];

    }
}