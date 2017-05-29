<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/11/2017
 * Time: 11:40 AM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GrowerBreederRepository extends EntityRepository
{
    public function getNrBreederRequests(User $user){
        $nrBreederRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.grower = :grower')
            ->setParameter('grower', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrBreederRequests){
            return $nrBreederRequests;
        }else{
            return 0;
        }
    }
    public function getBreederRequestsQuery(User $user){
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

    public function getNrMyBreederRequests(User $user){
        $nrBreederRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrBreederRequests){
            return $nrBreederRequests;
        }else{
            return 0;
        }
    }
    public function getMyBreederRequestsQuery(User $user){
        return $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }

}