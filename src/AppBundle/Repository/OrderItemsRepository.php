<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/31/2017
 * Time: 4:39 PM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use Doctrine\ORM\EntityRepository;

class OrderItemsRepository extends EntityRepository
{
    public function findAllMyReceivedOrders(User $user){
        return $this->createQueryBuilder('order_items')
            ->andWhere('user_order.user= :createdBy')
            ->setParameter('createdBy',$user)
            ->orderBy('user_order.createdAt','DESC')
            ->getQuery()
            ->execute();
    }

    public function findNrAllMyReceivedOrders(Company $user){
        $nrReceivedOrders= $this->createQueryBuilder('order_items')
            ->select('count(order_items.id)')
            ->andWhere('order_items.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrReceivedOrders){
            return $nrReceivedOrders;
        }else{
            return 0;
        }
    }
    public function findNrUnshippedItems(UserOrder $order){
        $nrUnshippedOrders= $this->createQueryBuilder('order_items')
            ->select('count(order_items.id)')
            ->andWhere('order_items.itemStatus = :isPending')
            ->setParameter('isPending', 'Pending')
            ->andWhere('order_items.order = :order')
            ->setParameter('order', $order)
            ->getQuery()
            ->getSingleScalarResult();
        if ($nrUnshippedOrders){
            return $nrUnshippedOrders;
        }else{
            return 0;
        }
    }

    public function findVendorReceivedOrders(Company $user){
        return $this->createQueryBuilder('order_items')
            ->andWhere('order_items.vendor= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->execute();
    }
}