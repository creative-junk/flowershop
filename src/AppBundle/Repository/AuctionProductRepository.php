<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/18/2017
 * Time: 5:49 PM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

class AuctionProductRepository extends EntityRepository
{
    /**
     *
     */
    public function findAllMyActiveAuctionProductsOrderByDate(Company $user){

        return $this->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auctionProduct.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auction.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('auctionProduct.createdAt','DESC')
            ->getQuery()
            ->execute();
    }

    public function findMyActiveAuctionProducts(Company $user){
        $nrProducts= $this->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->select('count(auctionProduct.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auctionProduct.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    public function findAllMyActiveAgentProductsOrderByDate(Company $user){

        return $this->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auctionProduct.sellingAgent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auctionProduct.status= :status')
            ->setParameter('status',"Active")
            ->orderBy('auctionProduct.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    public function findNrMyActiveProductsAgent(Company $user){
        $nrProducts= $this->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->select('count(auctionProduct.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('auctionProduct.sellingAgent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('auctionProduct.status= :status')
            ->setParameter('status',"Agent Accepted")
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
}