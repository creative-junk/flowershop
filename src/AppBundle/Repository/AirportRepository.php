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
use AppBundle\Entity\Airport;
use Doctrine\ORM\EntityRepository;

class AirportRepository extends EntityRepository
{
    /**
     * @return Airport[]
     */
    public function findAllAirportsOrderByName(){
        return $this->createQueryBuilder('airport')
            ->orderBy('airport.airportName','asc')
            ->getQuery()
            ->execute();
    }
}