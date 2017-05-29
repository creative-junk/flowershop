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
     * @return Product[]
     */
    public function findAllMyActiveAuctionProductsOrderByDate(User $user){

        return $this->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.user= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('product.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    public function findMyActiveAuctionProducts(User $user){
        $nrProducts= $this->createQueryBuilder('product')
            ->select('count(product.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.user= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    public function findMyActiveProductRequests(User $user){
        $nrProducts= $this->createQueryBuilder('product')
            ->select('count(product.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.agent= :isAgent')
            ->setParameter('isAgent',$user)
            ->andWhere('product.status= :status')
            ->setParameter('status',"Pending Agent")
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }

}