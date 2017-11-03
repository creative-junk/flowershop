<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\Auction;
use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\Company;
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
    public function findAllMyReceivedOrdersOrderByDate(Company $user){

        return $this->createQueryBuilder('auction_order')
            ->andWhere('auction_order.sellingAgent= :soldBy')
            ->setParameter('soldBy',$user)
            ->orderBy('auction_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return AuctionOrder[]
     */
    public function findAllMyOrderAssignmentRequests(Company $user){

        return $this->createQueryBuilder('auction_order')
            ->andWhere('auction_order.buyingAgent= :soldBy')
            ->setParameter('soldBy',$user)
            ->orderBy('auction_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    public function findMyAuctionAgencyRequests(Company $user){

        $auctionAgencyRequests=$this->createQueryBuilder('auction_order')
            ->select('count(auction_order.id)')
            ->andWhere('auction_order.buyingAgent= :isAgent')
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
    public function findNrAllMyAgentReceivedOrders(Company $user){

        $nrReceivedOrders= $this->createQueryBuilder('user_order')
            ->select('count(user_order.id)')
            ->andWhere('user_order.sellingAgent = :agentIs')
            ->setParameter('agentIs',$user)
            ->getQuery()
            ->getSingleScalarResult();

        if ($nrReceivedOrders){
            return $nrReceivedOrders;
        }else{
            return 0;
        }
    }
    public function findMyAuctionOrders(AuctionProduct $auctionProduct){
        return $this->createQueryBuilder('auction_order')
            ->andWhere('auction_order.product = :whichProduct')
            ->setParameter('whichProduct',$auctionProduct)
            ->orderBy('auction_order.checkoutCompletedAt','DESC')
            ->getQuery()
            ->execute();
    }

}