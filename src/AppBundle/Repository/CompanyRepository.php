<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/30/2017
 * Time: 11:35 AM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

class CompanyRepository extends EntityRepository
{
    public function getNrBuyers(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'Buyer')
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrGrowers(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'Grower')
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrBreeders(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'Breeder')
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrAgents(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'Agent')
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrChangeBuyersThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
            ->andWhere('user.createdAt= :createdAt')
            ->setParameter('createdAt', new \DateTime('-7 days'))
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrChangeGrowersThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'grower')
            ->andWhere('user.createdAt= :createdAt')
            ->setParameter('createdAt', new \DateTime('-7 days'))
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrChangeBreedersThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
            ->andWhere('user.createdAt= :createdAt')
            ->setParameter('createdAt', new \DateTime('-7 days'))
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrChangeAgentsThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.companyType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
            ->andWhere('user.createdAt= :createdAt')
            ->setParameter('createdAt', new \DateTime('-7 days'))
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrUsers(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    public function getNrChangeUsersThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.createdAt= :createdAt')
            ->setParameter('createdAt', new \DateTime('-7 days'))
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUsers){
            return $nrUsers;
        }else{
            return 0;
        }
    }
    /**
     * @return Company[]
     */
    public function findAllPendingCompanyAccounts(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',false)
            ->getQuery()
            ->execute();
    }
    /**
     * @return Company[]
     */
    public function findAllActiveCompanyAccounts(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',true)
            ->andWhere('user.companyName != :iflora')
            ->setParameter('iflora','Iflora')
            ->getQuery()
            ->execute();
    }
}