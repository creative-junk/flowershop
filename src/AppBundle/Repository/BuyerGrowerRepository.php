<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/12/2017
 * Time: 4:23 PM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class BuyerGrowerRepository extends EntityRepository
{
    public function getNrGrowerRequests(Company $user){
        $nrGrowerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.buyer = :buyer')
            ->setParameter('buyer', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrGrowerRequests){
            return $nrGrowerRequests;
        }else{
            return 0;
        }
    }
    public function getNrBuyerRequests(Company $user){
        $nrBuyerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.grower = :grower')
            ->setParameter('grower', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrBuyerRequests){
            return $nrBuyerRequests;
        }else{
            return 0;
        }
    }
    public function getGrowerRequestsQuery(Company $user){
        return $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.buyer = :buyer')
            ->setParameter('buyer', $user)
            ->getQuery();
    }
    public function getBuyerRequestsQuery(Company $user){
        return $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.grower = :grower')
            ->setParameter('grower', $user)
            ->getQuery();
    }
    public function getNrMyGrowerRequests(Company $user){
        $nrGrowerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrGrowerRequests){
            return $nrGrowerRequests;
        }else{
            return 0;
        }
    }
    public function getMyGrowerRequests(Company $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }
    public function getNrMyBuyerRequests(Company $user){
        $nrBuyerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrBuyerRequests){
            return $nrBuyerRequests;
        }else{
            return 0;
        }
    }
    public function getMyBuyerRequests(Company $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }
    public function getNrMyGrowerBuyers(Company $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
}