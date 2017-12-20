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
use AppBundle\Entity\Company;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class AuctionRepository extends EntityRepository
{
    /**
     * @return Auction[]
     */
    public function findAllActiveAuctionProductsOrderByDate(){
        return $this->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->orderBy('product.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     *
     */
    public function findAllMyActiveAuctionProductsOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('product.createdAt','DESC');
    }
    /**
     *
     */
    public function findAllMyActiveAuctionOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status= :status')
            ->setParameter('status','Active')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('product.createdAt','DESC');
    }
    /**
     *
     */
    public function findAllMyAcceptedAuctionOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status= :status')
            ->setParameter('status','Accepted')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('product.createdAt','DESC');
    }
    /**
     *
     */
    public function findAllMyUnassignedAuctionOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :unassigned')
            ->setParameter('unassigned','Unassigned')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('auction.isInStock = :inStock')
            ->setParameter('inStock',true)
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $user)
            ->orderBy('product.createdAt', 'DESC');
    }
    /**
     *
     */
    public function findAllMyPendingAuctionOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :pendingAgent')
            ->setParameter('pendingAgent','Pending Agent')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $user)
            ->orderBy('product.createdAt', 'DESC');
    }
    /**
     *
     */
    public function findAllMyShippedAuctionOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :pendingAgent')
            ->setParameter('pendingAgent','Shipped')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $user)
            ->orderBy('product.createdAt', 'DESC');
    }

    public function findMyActiveAuctionProducts(Company $user){
        $nrProducts= $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->select('count(product.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    public function findMyActiveProductRequests(Company $user){
        $nrProducts= $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->select('count(auction.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auction.sellingAgent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auction.status= :status')
            ->setParameter('status',"Pending Agent")
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    public function findNrMyActiveProductsAgent(Company $user){
        $nrProducts= $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->select('count(product.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auction.sellingAgent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auction.status= :status')
            ->setParameter('status',"Agent Accepted")
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    /**
     * @return Auction[]
     */
    public function findAllMyActiveAgentProductsOrderByDate(Company $user){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auction.sellingAgent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auction.status= :status')
            ->setParameter('status',"Agent Accepted")
            ->orderBy('product.createdAt','DESC')
            ->getQuery()
            ->execute();
    }


    public function findAllUnassignedAuctionOrderByDate(){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :unassigned')
            ->setParameter('unassigned','Unassigned')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('product.createdAt', 'DESC');
    }
    public function findAllAssignedAuctionOrderByDate(){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :pendingAgent')
            ->setParameter('pendingAgent','Pending Agent')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('product.createdAt', 'DESC');
    }
    public function findAllAcceptedAuctionOrderByDate(){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status= :status')
            ->setParameter('status','Accepted')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->orderBy('product.createdAt','DESC');
    }
    public function findAllShippedAuctionOrderByDate(){

        return $this->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :pendingAgent')
            ->setParameter('pendingAgent','Shipped')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('product.createdAt', 'DESC');
    }


}