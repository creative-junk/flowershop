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
     * @param $username
     * @return null| User
     */
    public function findOneByUsername($username){
        return $this->createQueryBuilder('user')
            ->andWhere('user.email = :userName')
            ->setParameter('userName', $username)
            ->getQuery()
            ->execute();
    }
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
     * @return User[]
     */
    public function findAllPendingAdminUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.isPasswordCreated = :passwordCreated')
            ->setParameter(':passwordCreated',false)
            ->andWhere('user.roles = :isAdmin')
            ->setParameter(':isAdmin','["ROLE_ADMIN"]')
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findAllUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles != :userRole')
            ->setParameter(':userRole','["ROLE_ADMIN"]')
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findAllAdministratorUsers(){

        return $this->createQueryBuilder('user')
            ->orWhere('user.roles = :isAdmin')
            ->setParameter(':isAdmin','["ROLE_ADMIN"]')
            ->getQuery()
            ->execute();
    }





}