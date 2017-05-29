<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Auction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AuctionController extends Controller
{
    /**
     * @Route("/auction/",name="auction_list")
     */
    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllActiveAuctionProductsOrderByDate();

        return $this->render('auction.htm.twig',[
            'products'=>$products,
        ]);
    }
    /**
     * @Route("/auction/grid",name="grid_products")
     *
     */
    public function gridAuctionAction()
    {
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllActiveAuctionProductsOrderByDate();

        return $this->render(':partials:auction-grid-view.htm.twig',[
            'products'=>$products,
        ]);
    }
    /**
     * @Route("/auction/list",name="list_products")
     *
     */
    public function listAuctionAction()
    {
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllActiveAuctionProductsOrderByDate();

        return $this->render(':partials:auction-list-view.htm.twig',[
            'products'=>$products,
        ]);
    }
    /**
     * @Route("/auction/{id}/view")
     */
    public function showAction(Auction $product){
        return $this->render('::auction-product-details.htm.twig',[
            'auctionProduct' => $product
        ]);
    }
}
