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
use AppBundle\Entity\Auction;
use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\Direct;
use Doctrine\ORM\EntityRepository;

class RatingRepository extends EntityRepository
{
    public function findRoseReviews(Direct $rose){
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function sumQualityRatingsForRose(Direct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.quality) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumValueRatingsForRose(Direct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.value) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumPriceRatingsForRose(Direct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.price) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function nrRatingsForRose(Direct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.rose=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrRatings')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findAuctionReviews(Auction $rose){
        return $this->createQueryBuilder('t')
            ->where('t.auction=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function sumQualityRatingsForAuction(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auction=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.quality) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumValueRatingsForAuction(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auction=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.value) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumPriceRatingsForAuction(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auction=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.price) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function nrRatingsForAuction(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auction=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrRatings')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findVendorReviews($user){
        return $this->createQueryBuilder('t')
            ->where('t.vendor=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
    public function findAgentReviews($user){
        return $this->createQueryBuilder('t')
            ->where('t.agent=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
    public function findAuctionProductReviews(AuctionProduct $rose){
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function sumQualityRatingsForAuctionProducts(AuctionProduct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.quality) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumValueRatingsForAuctionProducts(AuctionProduct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.value) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function sumPriceRatingsForAuctionProducts(AuctionProduct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose',$rose)
            ->select('AVG(t.price) as sumQuality')
            ->getQuery()
            ->execute();
    }
    public function nrRatingsForAuctionProducts(AuctionProduct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrRatings')
            ->getQuery()
            ->getSingleScalarResult();
    }
}