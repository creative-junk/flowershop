<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/20/2017
 * Time: 10:45 AM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Company;
use Doctrine\ORM\EntityRepository;

class NotificationRepository extends EntityRepository
{
    public function getNotifications(Company $user){
            return $this->createQueryBuilder('t')

                // the participant is in the thread participants
                ->andWhere('t.participant = :user')
                ->setParameter(':user', $user)

                // the thread is not deleted by this participant
                ->andWhere('t.isDeleted = :isDeleted')
                ->setParameter('isDeleted', false)

                // sort by date of last message written by an other participant
                ->orderBy('t.subject', 'DESC')
                ->getQuery()
                ->execute();
    }
    public function getNrUnread(Company $participant){
        $builder = $this->createQueryBuilder('m');

        return (int) $builder
            ->select($builder->expr()->count('m.id'))

            // the participant is in the thread participants
            ->andWhere('m.participant = :user')
            ->setParameter(':user', $participant)

              ->andWhere('m.isRead = :isRead')
            ->setParameter('isRead', false)

            ->getQuery()
            ->getSingleScalarResult();
    }


}