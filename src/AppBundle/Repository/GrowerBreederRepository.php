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


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GrowerBreederRepository extends EntityRepository
{
    public function getNrBreederRequests(Company $user){
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
    public function getBreederRequestsQuery(Company $user){
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

    public function getNrMyBreederRequests(Company $user){
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
    public function getMyBreederRequestsQuery(Company $user){
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

    public function getNrGrowerRequests(Company $user){
        $nrBreederRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.breeder = :breeder')
            ->setParameter('breeder', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrBreederRequests){
            return $nrBreederRequests;
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
            ->andWhere('user.breeder = :breeder')
            ->setParameter('breeder', $user)
            ->getQuery();
    }

    public function getNrMyGrowerRequests(Company $user){
        $nrBreederRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.breeder = :whoIsBreeder')
            ->setParameter('whoIsBreeder', $user)
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
    public function getMyGrowerRequestsQuery(Company $user){
        return $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.breeder = :whoIsBreeder')
            ->setParameter('whoIsBreeder', $user)
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->getQuery();
    }
    public function getNrMyGrowers(Company $user){
        $nrGrowers= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.breeder = :whoIsBreeder')
            ->setParameter('whoIsBreeder', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrGrowers){
            return $nrGrowers;
        }else{
            return 0;
        }
    }

}