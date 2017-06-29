<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/18/2017
 * Time: 4:46 PM
 ********************************************************************************/

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ThreadRepository extends EntityRepository
{
   public function getInboxThreads($user){
       return $this->createQueryBuilder('t')
           ->innerJoin('t.messages', 'mm')
           ->innerJoin('mm.participant', 'p')

            // the participant is in the thread participants
           ->andWhere('p.id = :user')
           ->setParameter(':user', $user)

           // the thread is not deleted by this participant
           ->andWhere('t.isDeleted = :isDeleted')
           ->setParameter('isDeleted', false)

           // there is at least one message written by an other participant
           ->andWhere('t.lastMessageDate IS NOT NULL')

           // sort by date of last message written by an other participant
           ->orderBy('t.lastMessageDate', 'DESC')
           ->getQuery()
           ->execute();
   }
    public function getSentThreads($user){
        return $this->createQueryBuilder('t')
            ->innerJoin('t.messages', 'mm')
            ->innerJoin('mm.sender', 'p')

            // the participant is in the thread participants
            ->andWhere('p.id = :user')
            ->setParameter(':user', $user)

            // the thread is not deleted by this participant
            ->andWhere('t.isDeleted = :isDeleted')
            ->setParameter('isDeleted', false)

            ->getQuery()
            ->execute();
    }
}