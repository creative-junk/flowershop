<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/api/agent")
 * @Security("is_granted('ROLE_AGENT')")
 *
 */
class AgentController extends Controller
{
    /**
     * @Rest\Get("/auction")
     */
    public function auctionMarketAction()
    {
        
    }

    /**
     * @Rest\Get("/products/assigned")
     */
    public function assignedProductsAction(){
        
    }

    /**
     * @Rest\Get("/products/accepted")
     */
    public function acceptedProductsAction(){
        
    }

    /**
     * @Rest\Get("/products/shipped")
     */
    public function shippedProductsAction(){
        
    }

    /**
     * @Rest\Get("/products/stock")
     */
    public function myStockAction(){
        
    }

    /**
     * @Rest\Get("/buyers")
     */
    public function viewBuyersAction(){
        
    }

    /**
     * @Rest\Get("/buyers/my")
     */
    public function viewMyBuyersAction(){
        
    }

    /**
     * @Rest\Get("requests/buyer/sent")
     */
    public function viewSentBuyerRequestsAction(){
        
    }

    /**
     * @Rest\Get("requests/buyer/received")
     */
    public function viewReceivedBuyerRequestsAction(){
        
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
     * @Rest\Get("requests/growers/sent")
     */
    public function viewSentGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("/requests/growers/received")
     */
    public function viewReceivedGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("/orders/assigned")
     */
    public function viewAssignedOrdersAction(){

    }

    /**
     * @Rest\Get("/orders/received")
     */
    public function viewReceivedOrdersAction(){

    }

    /**
     * @Rest\Get("/orders/my")
     */
    public function viewMyOrdersAction(){

    }
}
