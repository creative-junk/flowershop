<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 12/20/2017
 * Time: 2:29 PM
 ********************************************************************************/

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class PaymentRepository extends EntityRepository
{
    public function findDirectPayments(){
        $qb = $this->createQueryBuilder('payment');
        $result = $qb
            ->where($qb->expr()->isNotNull('payment.order'))
            ->getQuery()
            ->getResult();
        return $result;

    }
    public function findAuctionPayments(){
        $qb = $this->createQueryBuilder('payment');
        $result = $qb
            ->where($qb->expr()->isNotNull('payment.auctionOrder'))
            ->getQuery()
            ->getResult();
        return $result;

    }
}