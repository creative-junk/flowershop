<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 6/21/2017
 * Time: 5:12 PM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findRoseComments(Product $rose){
        return $this->createQueryBuilder('t')
            ->where('t.product=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function nrCommentsForRose(Product $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.product=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrComments')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findUserComments($user){
        return $this->createQueryBuilder('t')
            ->where('t.user=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
}