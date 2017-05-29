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


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class BuyerAgentRepository extends EntityRepository
{
    public function getNrAgentRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.buyer = :buyer')
            ->setParameter('buyer', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getAgentRequestsQuery(User $user){
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
    public function getNrMyAgentRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getMyAgentRequests(User $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }

    public function getNrBuyerRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.agent = :buyer')
            ->setParameter('buyer', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getBuyerRequestsQuery(User $user){
        return $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.agent = :buyer')
            ->setParameter('agent', $user)
            ->getQuery();
    }
    public function getNrMyBuyerRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.agent = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getMyBuyerRequests(User $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.agent = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }
    public function getMyAgentBuyers(User $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user)
            ->getQuery()
            ->execute();
    }
}