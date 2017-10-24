<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Direct;
use AppBundle\Entity\Rating;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/grower")
 * @Security("is_granted('ROLE_GROWER')")
 *
 */
class GrowerController extends Controller
{
    /**
     * @Rest\Get("/seedlings")
     */
    public function viewSeedlingsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $seedlings = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('direct')
            ->innerJoin('direct.product', 'product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', true)
            ->orderBy('direct.createdAt', 'DESC')
            ->getQuery()
            ->execute();
        $data = array('seedlings' => array());
        foreach ($seedlings as $seedling) {
            $data['seedlings'][] = $this->serializeDirect($seedling);
           // $data['seedlings'][]['rating'] = $this->calculateRating($seedling);
        }
        //return new JsonResponse($data,200);
        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * @Rest\Get("/roses/my")
     */
    public function myRosesAction()
    {

    }

    /**
     * @Rest\Get("/market/my")
     */
    public function myDirectMarketAction()
    {

    }

    /**
     * @Rest\Get("/auction/unassigned")
     */
    public function unassignedAuctionAction()
    {

    }

    /**
     * @Rest\Get("/auction/pending-agent")
     */
    public function pendingAgentAuctionAction()
    {

    }

    /**
     * @Rest\Get("/auction/accepted-agent")
     */
    public function agentAcceptedAuctionAction()
    {

    }

    /**
     * @Rest\Get("/auction/shipped")
     */
    public function shippedAuctionAction()
    {

    }

    /**
     * @Rest\Get("/auction/active")
     */
    public function activeAuctionAction()
    {

    }

    /**
     * @Rest\Get("/buyers")
     */
    public function viewBuyersAction()
    {

    }

    /**
     * @Rest\Get("/buyers/my")
     */
    public function viewMyBuyersAction()
    {

    }

    /**
     * @Rest\Get("/requests/buyers/sent")
     */
    public function viewSentBuyerRequestsAction()
    {

    }

    /**
     * @Rest\Get("/requests/buyers/received")
     */
    public function viewReceivedBuyerRequestsAction()
    {

    }

    /**
     * @Rest\Get("/breeders")
     */
    public function viewBreedersAction()
    {

    }

    /**
     * @Rest\Get("/breeders/my")
     */
    public function viewMyBreedersAction()
    {

    }

    /**
     * @Rest\Get("/requests/breeders/sent")
     */
    public function viewSentBreederRequestsAction()
    {

    }

    /**
     * @Rest\Get("requests/breeders/received")
     */
    public function viewReceivedBreederRequestsAction()
    {}



    /**
     * @Rest\Get("/agents")
     */
    public function viewAgentsAction()
    {

    }

    /**
     * @Rest\Get("/agents/my")
     */
    public function viewMyAgentsAction()
    {

    }

    /**
     * @Rest\Get("/requests/agents/sent")
     */
    public function viewSentAgentRequestsAction()
    {

    }

    /**
     * @Rest\Get("/requests/agents/received")
     */
    public function viewReceivedAgentRequestsAction()
    {

    }

    /**
     * @Rest\Get("/orders")
     */
    public function viewMyOrdersAction()
    {

    }

    /**
     * @Rest\Get("/orders/received")
     */
    public function viewReceivedOrdersAction()
    {

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
            'stock' => $direct->getNumberOfStems(),
            'packing' => $direct->getStemsPerBox(),
            'grower' => $direct->getVendor()->getCompanyName(),
            'reviews' =>$direct->getReviews(),
            'growerId' => $direct->getVendor()->getId(),
            'rating'  =>$this->calculateRating($direct),
            'currency' =>$user->getMyCompany()->getCurrency());

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
}
