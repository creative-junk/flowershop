<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @Route("/api/breeder")
 * @Security("is_granted('ROLE_BREEDER')")
 *
 */
class BreederController extends Controller
{
    /**
     * @Rest\Get("/seedlings/my")
     */
    public function mySeedlingsAction()
    {

    }

    /**
     * @Rest\Get("/market/my")
     */
    public function myMarketAction(){

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
     * @Rest\Get("/requests/growers/received")
     */
    public function viewReceivedGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("/requests/growers/sent")
     */
    public function viewSentGrowerRequestsAction(){

    }

    /**
     * @Rest\Get("orders/received")
     */
    public function viewReceivedOrdersAction(){

    }
}
