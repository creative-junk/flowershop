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
use Doctrine\ORM\EntityRepository;

class BillingAddressRepository extends EntityRepository
{
    public function findMyBillingAddress(Company $user){
        return $this->createQueryBuilder('billing')
            ->andWhere('billing.company = :ownedBy')
            ->setParameter('ownedBy',$user)
            ->getQuery()
            ->execute();
    }
}