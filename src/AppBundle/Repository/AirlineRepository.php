<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 11/9/2017
 * Time: 3:57 PM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Airline;
use Doctrine\ORM\EntityRepository;

class AirlineRepository extends EntityRepository
{
    /**
     * @return Airline[]
     */
    public function findAllAirlinesOrderByName(){
        return $this->createQueryBuilder('airline')
            ->orderBy('airline.airlineName','asc')
            ->getQuery()
            ->execute();
    }
}