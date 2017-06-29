<?php

namespace AppBundle\Controller\Compare;

use AppBundle\Entity\Auction;
use AppBundle\Entity\MyList;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompareController extends Controller
{
    /**
     * @Route("/rose/compare/{id}/add",name="add-rose-to-compare")
     */
    public function addProductToCompareAction(Request $request, Product $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $compareList = $em->getRepository("AppBundle:MyList")->findOneBy(['listOwner' => $user, 'listType' => 'Product-Compare', 'productType' => 'Rose', 'product' => $product]);
        if ($compareList) {
            return new Response(null, 500);
        } else {
            $compareList = new MyList();
            $compareList->setProduct($product);
            $compareList->setListType('Product-Compare');
            $compareList->setListOwner($user);
            $compareList->setProductType('Rose');
            $em->persist($compareList);
            $em->flush();
            return new Response(null, 204);
        }
    }

    /**
     * @Route("/auction/compare/{id}/compare",name="add-auction-to-compare")
     */
    public function addAuctionToCompareAction(Request $request, Auction $auction)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $compareList = new MyList();
        $compareList->setAuctionProduct($auction);
        $compareList->setListType('Product-Compare');
        $compareList->setListOwner($user);
        $compareList->setProductType('Auction');
        return new Response(null, 204);

    }

    /**
     * @Route("/rose/compare/{id}/remove",name="remove-rose-from-compare")
     */
    public function removeProductFromCompareAction(Request $request, MyList $list)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $em->remove($list);
        $em->flush();
        return new Response(null, 204);

    }

    /**
     * @Route("compare/list",name="comparison-list")
     */
    public function getComparisonListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $compareList = $em->getRepository("AppBundle:MyList")->findBy(['listOwner' => $user, 'listType' => 'Product-Compare', 'productType' => 'Rose',]);
        return $this->render('compare/listing.htm.twig', ['productList' => $compareList]);
    }

    /**
     * @Route("compare/clear",name="clear-list")
     */
    public function clearCompareAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $compareList = $em->getRepository("AppBundle:MyList")->findBy([
            'listOwner' => $user,
            'listType' => 'Product-Compare',
            'productType' => 'Rose',]);
        foreach ($compareList as $listItem) {
            $em->remove($listItem);
        }
        $em->flush();
        return new Response(null, 204);
    }

    /**
     * @Route("compare-nr-items",name="nr-items-compare")
     */
    public function getNrCompareItemsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $nrItems = $em->getRepository("AppBundle:MyList")->getNrItemsToCompare($user);
        return $this->render(':partials/iflora:compare.htm.twig', ['nrItems' => $nrItems]);
    }
}
