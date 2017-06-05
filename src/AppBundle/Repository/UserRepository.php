<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/29/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return User[]
     */
    public function findAllActiveBreedersOrderByDate()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'breeder')
            ->orderBy('user.firstName', 'ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return User[]
     */
    public function findAllActiveAgentsOrderByDate()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'agent')
            ->orderBy('user.firstName', 'ASC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return User[]
     */
    public function findAllActiveGrowers()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'grower')
            ->orderBy('user.firstName', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return User[]
     */
    public function findAllActiveBuyers()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'buyer')
            ->orderBy('user.firstName', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * @return User[]
     */
    public function findAllActiveAdmins()
    {
        return $this->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'breeder')
            ->orderBy('user.firstName', 'DESC')
            ->getQuery()
            ->execute();
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
    public function getNrBuyers(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.userType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
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
            ->andWhere('user.userType = :isBuyer')
            ->setParameter('isBuyer', 'grower')
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
            ->andWhere('user.userType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
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
            ->andWhere('user.userType = :isBuyer')
            ->setParameter('isBuyer', 'buyer')
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
    public function getNrChangeBuyersThisWeek(){
        $nrUsers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.userType = :isBuyer')
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
            ->andWhere('user.userType = :isBuyer')
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
            ->andWhere('user.userType = :isBuyer')
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
            ->andWhere('user.userType = :isBuyer')
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







}