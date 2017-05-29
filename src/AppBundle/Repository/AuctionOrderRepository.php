<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use Doctrine\ORM\EntityRepository;

class AuctionOrderRepository extends EntityRepository
{
    /**
     * @return AuctionOrder[]
     */
    public function findAllUserOrdersOrderByDate(){

        return $this->createQueryBuilder('auction_order')
            ->orderBy('auction_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return AuctionOrder[]
     */
    public function findAllMyOrdersOrderByDate(User $user){

        return $this->createQueryBuilder('auction_order')
            ->andWhere('auction_order.whoseOrder= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('auction_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return AuctionOrder[]
     */
    public function findAllMyReceivedOrdersOrderByDate(User $user){

        return $this->createQueryBuilder('auction_order')
            ->andWhere('auction_order.vendor= :soldBy')
            ->setParameter('soldBy',$user)
            ->orderBy('auction_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    
    public function findMyAuctionAgencyRequests(User $user){

        $auctionAgencyRequests=$this->createQueryBuilder('auction_order')
            ->select('count(auction_order.id)')
            ->andWhere('auction_order.agent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auction_order.orderStatus = :orderStatus')
            ->setParameter('orderStatus',"Pending Agent")
            ->getQuery()
            ->getSingleScalarResult();
        if ($auctionAgencyRequests){
            return $auctionAgencyRequests;

        }else{
            return 0;
        }
    }
}