<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 8/11/2017
 * Time: 12:38 AM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use Doctrine\ORM\EntityRepository;

class DirectRepository extends EntityRepository
{
    /**
     * @return Direct[]
     */
    public function findAllActiveProductsOrderByDate(){
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',false)
            ->orderBy('product.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return Direct[]
     */
    public function findAllActiveSeedlingsOrderByDate()
    {
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', true)
            ->orderBy('direct.createdAt', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return Direct[]
     */
    public function findAllMyActiveProductsOrderByDate(Company $user){
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',false)
            ->andWhere('direct.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',false)
            ->orderBy('direct.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return Direct[]
     */
    public function findAllActiveFeaturedProductsOrderByDate(){
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.isFeatured = :isFeatured')
            ->setParameter('isFeatured',true)
            ->orderBy('direct.createdAt','DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->execute();
    }
    /**
     * @return Direct[]
     */
    public function findAllActiveNewProductsOrderByDate(){
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('direct.createdAt >= :isNew')
            ->setParameter('isNew', 'NOW() - INTERVAL 3 MONTH')
            ->orderBy('direct.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    public function findMyActiveProducts(Company $user){
        $nrProducts= $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->select('count(product.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('direct.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',false)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    public function findNrActiveBreederSeedlings(Company $user){
        $nrProducts= $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->select('count(direct.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('direct.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',true)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
    /**
     * @return Direct[]
     */
    public function findAllUserActiveSeedlingsOrderByDate(Company $breeder){
        return $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',false)
            ->andWhere('direct.vendor= :createdBy')
            ->setParameter('createdBy',$breeder)
            ->andWhere('product.isSeedling= :isSeedling')
            ->setParameter('isSeedling',true)
            ->orderBy('direct.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    public function findNrAllMyActiveProducts(Company $grower){
        $nrProducts= $this->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->select('count(direct.id)')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive',true)
            ->andWhere('direct.vendor= :createdBy')
            ->setParameter('createdBy',$grower)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrProducts){
            return $nrProducts;
        }else{
            return 0;
        }
    }
}