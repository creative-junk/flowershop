<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\BuyerAgent;
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use AppBundle\Entity\UserOrder;
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
     * @Rest\Get("/orders")
     */
    public function myOrdersAction(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyOrdersOrderByDate($user);

        $data = array('seedlings' => array());
        foreach ($orders as $order) {
            $data['orders'][] = $this->serializeOrder($order);
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
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        //Get All Growers in this Buyers List
        $buyerGrowers = $em->getRepository('AppBundle:BuyerGrower')
            ->findBy([
                'listOwner' => $buyer->getMyCompany()
            ]);
        $growerIds = array();

        if ($buyerGrowers) {

            foreach ($buyerGrowers as $buyerGrower) {
                $growerIds[] = $buyerGrower->getGrower();
            }
        }else{
            $growerIds[] = 1;
        }

        $growers = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('company')
            ->andWhere('company.id NOT IN (:growers)')
            ->setParameter('growers',$growerIds)
            ->andWhere('company.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('company.companyType = :userType')
            ->setParameter('userType', 'grower')
            ->getQuery()
            ->execute();
        $data = array('growers' => array());
        foreach ($growers as $grower) {
            $data['growers'][] = $this->serializeCompany($grower);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\Get("/growers/my")
     */
    public function viewMyGrowersAction(){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $growers = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('buyer_grower')
            ->andWhere('buyer_grower.buyer = :whoOwns')
            ->setParameter('whoOwns',$buyer->getMyCompany())
            ->andWhere('buyer_grower.status = :whatStatus')
            ->setParameter('whatStatus','Accepted')
            ->getQuery()
            ->execute();
        $data = array('growers' => array());
        foreach ($growers as $grower) {
            $data['growers'][] = $this->serializeCompany($grower);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;


    }

    /**
     * @Rest\Get("/requests/growers/sent")
     */
    public function viewSentGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $whoseListIds[]=array();
        $whoseListIds[]=$user->getMyCompany();
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user->getMyCompany())
            ->andWhere('user.listOwner NOT IN (:buyers)')
            ->setParameter('buyers',$whoseListIds)
            ->getQuery()
            ->execute();
        $data = array('requests' => array());
        foreach ($requests as $request) {
            $data['requests'][] = $this->serializeCompany($request);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Get("/requests/growers/received")
     */
    public function viewReceivedGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user->getMyCompany())
            ->getQuery()
            ->execute();
        $data = array('requests' => array());
        foreach ($requests as $request) {
            $data['requests'][] = $this->serializeCompany($request);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Get("/agents")
     */
    public function viewAgents(){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $buyerAgents = $em->getRepository('AppBundle:BuyerAgent')
            ->findBy([
                'buyer' => $buyer->getMyCompany()
            ]);
        $agentIds = array();

        if ($buyerAgents) {

            foreach ($buyerAgents as $buyerAgent) {
                $agentIds[] = $buyerAgent->getAgent();
            }
        }else{
            $agentIds[] = 1;
        }
        $agents = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:agents)')
            ->setParameter('agents',$agentIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.companyType = :companyType')
            ->setParameter('companyType', 'Agent')
            ->getQuery()
            ->execute();
        $data = array('agents' => array());
        foreach ($agents as $agent) {
            $data['agents'][] = $this->serializeCompany($agent);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\Get("/agents/my")
     */
    public function viewMyAgents(){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $buyerAgents = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('buyer_agent')
            ->andWhere('buyer_agent.buyer = :whoOwns')
            ->setParameter('whoOwns',$buyer->getMyCompany())
            ->andWhere('buyer_agent.status = :whatStatus')
            ->setParameter('whatStatus','Accepted')
            ->getQuery()
            ->execute();
        $data = array('buyerAgents' => array());
        foreach ($buyerAgents as $buyerAgent) {
            $data['buyerAgents'][] = $this->serializeBuyerAgent($buyerAgent);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Get("/requests/agents/sent")
     */
    public function viewSentAgentRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AppBundle:BuyerAgent')
            ->getMyAgentRequests($user->getMyCompany())
              ->execute();
        $data = array('requests' => array());
        foreach ($requests as $request) {
            $data['requests'][] = $this->serializeBuyerAgent($request);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\Get("/requests/agents/received")
     */
    public function viewReceivedAgentRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user->getMyCompany())
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user->getMyCompany())
            ->getQuery()
            ->execute();
        $data = array('requests' => array());
        foreach ($requests as $request) {
            $data['requests'][] = $this->serializeBuyerAgent($request);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Rest\Get("/orders/auction")
     */
    public function auctionOrdersAction(){

    }

    private function serializeDirect(Direct $direct)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $price = $this->container->get('crysoft.currency_converter')->convertAmount($direct->getPricePerStem(),$direct->getVendor()->getCurrency(),$user->getMyCompany()->getCurrency());
        $price = number_format($price,2);

        return array(
            'productId'=> $direct->getId(),
            'productName' => $direct->getProduct()->getTitle(),
            'productImage' => $direct->getProduct()->getMainImage()->getImageName(),
            'pricePerStem' => $price,
            'color' => $direct->getProduct()->getColor(),
            'season' => $direct->getProduct()->getSeason(),
            'stemLength' => $direct->getProduct()->getStemLength(),
            'quality' => $direct->getQuality(),
            'stock' => number_format($direct->getNumberOfStems()),
            'packing' => number_format($direct->getStemsPerBox()),
            'grower' => $direct->getVendor()->getCompanyName(),
            'reviews' =>$direct->getReviews(),
            'growerId' => $direct->getVendor()->getId(),
            'rating'  =>$this->calculateRating($direct),
            'currency' =>'FOB '.$user->getMyCompany()->getCurrency());

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

    private function serializeOrder(UserOrder $order)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $orderAmount = $this->container->get('crysoft.currency_converter')->convertAmount($order->getOrderTotal(),$order->getOrderCurrency(),$user->getMyCompany()->getCurrency());
        $orderAmount = number_format($orderAmount,2);
        $orderDate = date_format($order->getCreatedAt(),'d-m-Y');

        return array(
            'orderId' => $order->getId(),
            'prettyId' => $order->getPrettyId(),
            'orderDate' => $orderDate,
            'orderAmount' => $user->getMyCompany()->getCurrency().' '.$orderAmount,
            'orderState' => $order->getOrderState(),
            'orderCurrency'=>$user->getMyCompany()->getCurrency(),
            'orderStatus'=> $order->getOrderStatus());

    }

    private function serializeCompany(Company $company){
        if ($company->getGallery()->getLogo()){
            $logo = $company->getGallery()->getLogo()->getImageName();
        }else{
            $logo = "avatar.jpg";
        }
        return array(
          'companyName' =>$company->getCompanyName(),
          'companyLogo'=>$logo,
          'companyRating'=>$this->calculateCompanyRating($company),
          'companyType'=>$company->getCompanyType(),
          'altitude'=>$company->getAltitude(),
          'country'=>$company->getCountry(),
          'numberOfVarieties'=>$company->getNumberOfVarieties(),
          'aboutCompany'=>$company->getAboutCompany(),
          'numberOfEmployees'=>$company->getNumberOfEmployees(),
          'telephoneNumber'=>$company->getTelephoneNumber(),
          'facebookPage'=>$company->getFacebookPage(),
          'twitterPage'=>$company->getTwitterPage(),
          'instagramPage'=>$company->getInstagramPage()
        );
    }
    private function calculateCompanyRating(Company $company)
    {
        $reviews = $company->getReviews();

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

    private function serializeBuyerAgent(BuyerAgent $buyerAgent)
    {
        $agent = $buyerAgent->getAgent();
        if ($agent->getGallery()->getLogo()){
            $logo = $agent->getGallery()->getLogo()->getImageName();
        }else{
            $logo = "avatar.jpg";
        }
        return array(
          'companyLogo'=>$logo,
          'companyName'=>$agent->getCompanyName(),
          'country'=>$agent->getCountry(),
          'status'=>$buyerAgent->getStatus(),
          'rating'=>$this->calculateCompanyRating($agent),
          'dateRequested'=>$buyerAgent->getUpdatedAt()
        );
    }


}
