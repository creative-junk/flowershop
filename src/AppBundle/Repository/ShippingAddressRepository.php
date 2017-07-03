<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/17/2017
 * Time: 3:42 PM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class ShippingAddressRepository extends EntityRepository
{
    public function findMyShippingAddress(Company $user){
        return $this->createQueryBuilder('shipping')
            ->andWhere('shipping.company= :ownedBy')
            ->setParameter('ownedBy',$user)
            ->getQuery()
            ->execute();
    }
}