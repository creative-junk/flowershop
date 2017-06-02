<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/11/2017
 * Time: 11:48 AM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\GrowerAgent;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class GrowerAgentRepository extends EntityRepository
{
    public function getNrAgentRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.grower = :grower')
            ->setParameter('grower', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getNrMyAgentRequests(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user)
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
    public function getNrGrowerRequests(User $user){
        $nrGrowerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user)
            ->andWhere('user.agent = :agent')
            ->setParameter('agent', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrGrowerRequests){
            return $nrGrowerRequests;
        }else{
            return 0;
        }
    }
    public function getNrMyGrowerRequests(User $user){
        $nrGrowerRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user)
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

    /**
     * @param User $user
     * @return GrowerAgent
     */
    public function findMyAgents(User $user){
        return $this->createQueryBuilder('grower_agent')
            ->andWhere('grower_agent.status = :isAccepted')
            ->setParameter('isAccepted',true)
            ->andWhere('grower_agent.grower= :isGrower')
            ->setParameter('isGrower',$user)
            ->getQuery()
            ->execute();
    }

    public function getNrMyAgentGrowers(User $user){
        $nrAgentRequests= $this->createQueryBuilder('user')
            ->select('count(user.id)')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrAgentRequests){
            return $nrAgentRequests;
        }else{
            return 0;
        }
    }
    public function getNrMyGrowerAgents(User $user){
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