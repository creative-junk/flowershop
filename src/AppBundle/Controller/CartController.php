<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\addToCartFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CartController extends Controller
{
    /**
     * @Route("/home/cart",name="buyer_cart_list")
     */
    public function buyerIndexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        if($cart) {
            $cartItems = $em->getRepository('AppBundle:CartItems')
                ->findAllItemsInMyCartOrderByDate($cart[0]);
            $cart = $cart[0];
        }else{
            $cartItems="";
            $cart = "";
        }
        return $this->render(':partials/iflora/user:macrocart.htm.twig', [
            'cartItems'=>$cartItems,
            'cart' => $cart
        ]);
    }
    /**
     * @Route("/grower/cart",name="grower_cart_list")
     */
    public function growerIndexAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        if($cart) {
            $cartItems = $em->getRepository('AppBundle:CartItems')
                ->findAllItemsInMyCartOrderByDate($cart[0]);
            $cart = $cart[0];
        }else{
            $cartItems="";
            $cart = "";
        }
        return $this->render(':partials/iflora:macrocart.htm.twig', [
            'cartItems'=>$cartItems,
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/mini",name="mini-cart")
     */
    public function cartAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        if ($cart) {
            return $this->render(':partials/iflora:minicart.htm.twig', [
                'cart' => $cart[0],
            ]);
        } else {
            return $this->render(':partials/iflora:minicart.htm.twig', [
                'cart' => null
            ]);
        }
    }

    /**
     * @Route("/shop/add-to-cart/",name="add-to-cart")
     */
    public function addToCartAction(Request $request){
        $cart = new Cart();
        $cart->setOwnedBy($this->get('security.token_storage')->getToken()->getUser());
        $form = $this->createForm(addToCartFormType::class, $cart);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
           /* $cart = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success','Product Created, Yaay!');

            return $this->redirectToRoute('admin_product_list');*/
        }

        return $this->render('::product-details.htm.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/checkout",name="checkout")
     */
    public function checkoutAction(Request $request)
    {

    }

    /**
     * @Route("/cart-totals",name="cart-totals")
     */
    public function cartTotalsAction(Request $request = null, Cart $cart)
    {
        return $this->render('cart.htm.twig', [
            'cart' => $cart,
        ]);
    }


}
