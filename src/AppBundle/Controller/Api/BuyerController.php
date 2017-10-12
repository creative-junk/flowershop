<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\Direct;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/home")
 * @Security("is_granted('ROLE_BUYER')")
 *
 */
class BuyerController extends Controller
{
    /**
     * @Rest\Get("/market")
     */
    public function directMarketAction(){
        $em = $this->getDoctrine()->getManager();
        $roses = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('direct')
            ->innerJoin('direct.product', 'product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', false)
            ->orderBy('direct.createdAt', 'DESC')
            ->getQuery()
            ->execute();
        $data = array('seedlings' => array());
        foreach ($roses as $rose) {
            $data['roses'][] = $this->serializeDirect($rose);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Get("/auction")
     */
    public function auctionMarketAction(){
        $em = $this->getDoctrine()->getManager();

        $roses = $em->getRepository('AppBundle:AuctionProduct')
            ->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('auctionProduct.createdAt', 'DESC')
            ->getQuery()
            ->execute();
        $data = array('seedlings' => array());
        foreach ($roses as $rose) {
            $data['roses'][] = $this->serializeAuctionProduct($rose);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\Get("/growers")
     */
    public function viewGrowersAction(){

    }

    /**
     * @Rest\Get("/growers/my")
     */
    public function viewMyGrowersAction(){

    }

    /**
     * @Rest\Get("/requests/growers/sent")
     */
    public function viewSentGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("/requests/growers/received")
     */
    public function viewReceivedGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("/agents")
     */
    public function viewAgents(){

    }

    /**
     * @Rest\Get("/agents/my")
     */
    public function viewMyAgents(){

    }

    /**
     * @Rest\Get("/requests/agents/sent")
     */
    public function viewSentAgentRequestsAction(){

    }

    /**
     * @Rest\Get("/requests/agents/received")
     */
    public function viewReceivedAgentRequestsAction(){

    }

    /**
     * @Rest\Get("/orders/market")
     */
    public function directOrdersAction(){

    }

    /**
     * @Rest\Get("/orders/auction")
     */
    public function auctionOrdersAction(){

    }

    private function serializeDirect(Direct $direct)
    {
        return array(
            'productName' => $direct->getProduct()->getTitle(),
            'productImage' => $direct->getProduct()->getMainImage()->getImageName(),
            'pricePerStem' => $direct->getPricePerStem(),
            'color' => $direct->getProduct()->getColor(),
            'season' => $direct->getProduct()->getSeason(),
            'stemLength' => $direct->getProduct()->getStemLength(),
            'quality' => $direct->getQuality(),
            'stock' => $direct->getNumberOfStems(),
            'packing' => $direct->getStemsPerBox(),
            'grower' => $direct->getVendor()->getCompanyName(),
            'reviews' =>$direct->getReviews(),
            'growerId' => $direct->getVendor()->getId(),
            'rating'  =>$this->calculateRating($direct));

    }

    private function calculateRating(Direct $direct)
    {
        $reviews = $direct->getReviews();

        $sumQualityratings = 0;
        $avgQualityRatings = 0;
        $sumValueratings = 0;
        $avgValueRatings = 0;
        $sumPriceratings = 0;
        $avgPriceRatings = 0;
        $count = 0;
        $avgrating = 0;
        if (!$reviews->isEmpty()) {
            foreach ($reviews as $review) {
                $sumQualityratings = $sumQualityratings + $review->getQuality();
                $sumValueratings = $sumValueratings + $review->getValue();
                $sumPriceratings = $sumPriceratings + $review->getPrice();
                $count++;
            }
            $avgPriceRatings    = ceil($sumPriceratings/$count);
            $avgQualityRatings  = ceil($sumQualityratings/$count);
            $avgValueRatings    = ceil($sumValueratings/$count);

            $avgrating = ceil(($avgQualityRatings+$avgValueRatings+$avgPriceRatings)/3);

        }
        return $avgrating;

    }
    private function serializeAuctionProduct(AuctionProduct $direct)
    {
        return array(
            'productName' => $direct->getWhichAuction()->getProduct()->getTitle(),
            'productImage' => $direct->getWhichAuction()->getProduct()->getMainImage()->getImageName(),
            'pricePerStem' => $direct->getPricePerStem(),
            'color' => $direct->getWhichAuction()->getProduct()->getColor(),
            'season' => $direct->getWhichAuction()->getProduct()->getSeason(),
            'stemLength' => $direct->getWhichAuction()->getProduct()->getStemLength(),
            'quality' => $direct->getWhichAuction()->getQuality(),
            'stock' => $direct->getWhichAuction()->getNumberOfStems(),
            'packing' => $direct->getWhichAuction()->getStemsPerBox(),
            'grower' => $direct->getWhichAuction()->getVendor()->getCompanyName(),
            'reviews' =>$direct->getReviews(),
            'growerId' => $direct->getWhichAuction()->getVendor()->getId(),
            'rating'  =>$this->calculateAuctionRating($direct));

    }

    private function calculateAuctionRating(AuctionProduct $direct)
    {
        $reviews = $direct->getReviews();

        $sumQualityratings = 0;
        $avgQualityRatings = 0;
        $sumValueratings = 0;
        $avgValueRatings = 0;
        $sumPriceratings = 0;
        $avgPriceRatings = 0;
        $count = 0;
        $avgrating = 0;
        if (!$reviews->isEmpty()) {
            foreach ($reviews as $review) {
                $sumQualityratings = $sumQualityratings + $review->getQuality();
                $sumValueratings = $sumValueratings + $review->getValue();
                $sumPriceratings = $sumPriceratings + $review->getPrice();
                $count++;
            }
            $avgPriceRatings    = ceil($sumPriceratings/$count);
            $avgQualityRatings  = ceil($sumQualityratings/$count);
            $avgValueRatings    = ceil($sumValueratings/$count);

            $avgrating = ceil(($avgQualityRatings+$avgValueRatings+$avgPriceRatings)/3);

        }
        return $avgrating;

    }

}
