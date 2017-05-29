<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 5/4/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\GrowersList;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GrowerListRepository extends EntityRepository
{
    /**
     * @return GrowersList
     */
    public function findthisList(User $buyer, User $grower)
    {
        return $this->createQueryBuilder('growerslist')
            ->andWhere('growerslist.whoseList= :whoseList')
            ->setParameter('whoseList', $buyer)
            ->andWhere('growerslist.grower= :grower')
            ->setParameter('grower', $grower)
            ->getQuery()
            ->execute();
    }
}