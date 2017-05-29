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

}