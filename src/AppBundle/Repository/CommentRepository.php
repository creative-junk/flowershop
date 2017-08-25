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
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class CommentRepository extends EntityRepository
{
    public function findRoseComments(Direct $rose){
        return $this->createQueryBuilder('t')
            ->where('t.Direct=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function nrCommentsForRose(Direct $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.Direct=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrComments')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findAuctionComments(Auction $rose){
        return $this->createQueryBuilder('t')
            ->where('t.Direct=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function nrCommentsForAuction(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.Direct=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrComments')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findAuctionProductComments(AuctionProduct $rose){
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose',$rose)
            ->orderBy('t.createdAt','DESC');
    }
    public function nrCommentsForAuctionProduct(Auction $rose)
    {
        return $this->createQueryBuilder('t')
            ->where('t.auctionProduct=:rose')
            ->setParameter(':rose', $rose)
            ->select('COUNT(t.id) as nrComments')
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findVendorComments(Company $user){
        return $this->createQueryBuilder('t')
            ->where('t.vendor=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
    public function findUserComments(Company $user){
        return $this->createQueryBuilder('t')
            ->where('t.vendor=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
    public function findAgentComments(User $user){
        return $this->createQueryBuilder('t')
            ->where('t.agent=:user')
            ->setParameter(':user',$user)
            ->orderBy('t.createdAt','DESC');
    }
}