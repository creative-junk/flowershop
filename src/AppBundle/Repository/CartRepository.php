<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\Cart;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class CartRepository extends EntityRepository
{

    /**
     * @return Cart
     */
    public function findMyCart(User $user){
        return $this->createQueryBuilder('cart')
            ->andWhere('cart.ownedBy= :createdBy')
            ->setParameter('createdBy',$user)
            ->getQuery()
            ->execute();
    }



}