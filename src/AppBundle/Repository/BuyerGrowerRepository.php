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

class BuyerGrowerRepository extends EntityRepository
{
    public function getNrGrowerRequests(User $user){
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
    public function getGrowerRequestsQuery(User $user){
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

    public function getNrMyGrowerRequests(User $user){
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
    public function getMyGrowerRequests(User $user){
        return $this->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }
}