<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 5/26/2017
 * Time: 7:11 PM
 ********************************************************************************/

namespace AppBundle\Repository;


use AppBundle\Entity\MyList;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class MyListRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return MyList
     */
    public function getMyRecommendations(User $user){
        return $this->createQueryBuilder('myList')
            ->andWhere('myList.listType = :listType')
            ->setParameter('listType',"Agent Recommendations")
            ->andWhere('myList.recommendedBy= :recommendedBy')
            ->setParameter('recommendedBy',$user)
            ->orderBy('myList.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @param User $user
     * @return MyList
     */
    public function getMyUserRecommendations(User $user){
        return $this->createQueryBuilder('myList')
            ->andWhere('myList.listType = :listType')
            ->setParameter('listType',"Agent Recommendations")
            ->andWhere('myList.listOwner= :listOwner')
            ->setParameter('listOwner',$user)
            ->orderBy('myList.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @param User $user
     * @return MyList
     */
    public function getMyWIshlist(User $user){
        return $this->createQueryBuilder('myList')
            ->andWhere('myList.listType = :listType')
            ->setParameter('listType',"Wishlist")
            ->andWhere('myList.listOwner= :listOwner')
            ->setParameter('listOwner',$user)
            ->orderBy('myList.createdAt','DESC')
            ->getQuery()
            ->execute();
    }
    /**
     * @param User $user
     * @return MyList
     */
    public function getMyAuctionWIshlist(User $user){
        return $this->createQueryBuilder('myList')
            ->andWhere('myList.listType = :listType')
            ->setParameter('listType',"Wishlist")
            ->andWhere('myList.listOwner= :listOwner')
            ->setParameter('listOwner',$user)
            ->andWhere('myList.listType= :listType')
            ->setParameter('listType',"Acution")
            ->orderBy('myList.createdAt','DESC')
            ->getQuery()
            ->execute();
    }

}