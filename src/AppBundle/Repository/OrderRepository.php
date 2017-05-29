<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    /**
     * @return UserOrder[]
     */
    public function findAllUserOrdersOrderByDate(){

        return $this->createQueryBuilder('user_order')
            ->orderBy('user_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return UserOrder[]
     */
    public function findAllMyOrdersOrderByDate(User $user){

        return $this->createQueryBuilder('user_order')
            ->andWhere('user_order.user= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('user_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @return UserOrder[]
     */
    public function findAllMyReceivedOrdersOrderByDate(User $user){

        return $this->createQueryBuilder('user_order')
            ->andWhere('user_order.vendor= :soldBy')
            ->setParameter('soldBy',$user)
            ->orderBy('user_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
}