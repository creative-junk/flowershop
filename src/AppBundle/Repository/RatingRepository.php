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

class RatingRepository extends EntityRepository
{
    public function findRoseReviews(Product $rose){
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function sumQualityRatingsForRose(Product $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.quality) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumValueRatingsForRose(Product $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.value) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumPriceRatingsForRose(Product $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.price) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function nrRatingsForRose(Product $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrRatings')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findUserReviews($user){
        return $this->createQueryBuilder('t')
            ->where('t.user=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
}