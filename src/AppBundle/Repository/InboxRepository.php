<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/18/2017
 * Time: 4:15 PM
 ********************************************************************************/

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class InboxRepository extends EntityRepository
{

    public function getNrUnread($participant){
        $builder = $this->createQueryBuilder('m');

        return (int) $builder
            ->select($builder->expr()->count('m.id'))

            ->where('m.participant = :participant')
            ->setParameter('participant', $participant)

            ->andWhere('m.sender != :sender')
            ->setParameter('sender', $participant)

            ->andWhere('m.isRead = :isRead')
            ->setParameter('isRead', false)

            ->getQuery()
            ->getSingleScalarResult();
    }

}