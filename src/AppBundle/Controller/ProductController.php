<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\Product;
use AppBundle\Form\addToCartFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    /**
     * @Route("/shop/",name="products")
     *
     */
    public function indexAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveProductsOrderByDate();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        //$cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();

            $existingCart = $em->getRepository('AppBundle:Cart')
                ->findMyCart($user);
            $quantity = $request->request->get('quantity');
            $price = $request->request->get('productPrice');
            $currency = $request->request->get('productCurrency');

            //Create The cart Item
            $cartItem = new CartItems();
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($price);
            $lineTotal = ($price) * ($quantity);
            $cartItem->setLineTotal($lineTotal);

            //Update the Cart
            if ($existingCart[0]) {
                $existingCart[0]->setCartAmount(($existingCart->getCartAmount()) + ($lineTotal));
                $existingCart[0]->setNrItems(($existingCart->getNrItems()) + $quantity);
                $cartItem->setCart($existingCart[0]);
                $em->persist($existingCart[0]);
            } else {
                $cart->setCartAmount($lineTotal);
                $cart->setNrItems($quantity);
                $cart->setCartCurrency($currency);
                $cartItem->setCart($cart);
                $em->persist($cart);
            }
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success', 'Product Created, Yaay!');

            return $this->redirectToRoute('products');
        }


        return $this->render('shop.htm.twig',[
            'products'=>$products,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/product/grid",name="grid_products")
     *
     */
    public function gridProductsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);


        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveProductsOrderByDate();


        return $this->render(':partials:shop-grid-view.htm.twig',[
            'products'=>$products,
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/product/list",name="list_products")
     *
     */
    public function listProductsAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveProductsOrderByDate();

        return $this->render(':partials:shop-list-view.htm.twig',[
            'products'=>$products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/Seedlings/",name="seedlings")
     *
     */
    public function indexSeedlingsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveSeedlingsOrderByDate();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();

            $existingCart = $em->getRepository('AppBundle:Cart')
                ->findMyCart($user);
            $quantity = $request->request->get('quantity');
            $price = $request->request->get('productPrice');
            $currency = $request->request->get('productCurrency');

            //Create The cart Item
            $cartItem = new CartItems();
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($price);
            $lineTotal = ($price) * ($quantity);
            $cartItem->setLineTotal($lineTotal);

            //Update the Cart
            if ($existingCart[0]) {
                $existingCart[0]->setCartAmount(($existingCart->getCartAmount()) + ($lineTotal));
                $existingCart[0]->setNrItems(($existingCart->getNrItems()) + $quantity);
                $cartItem->setCart($existingCart[0]);
                $em->persist($existingCart[0]);
            } else {
                $cart->setCartAmount($lineTotal);
                $cart->setNrItems($quantity);
                $cart->setCartCurrency($currency);
                $cartItem->setCart($cart);
                $em->persist($cart);
            }
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success', 'Product Created, Yaay!');

            return $this->redirectToRoute('products');
        }


        return $this->render('shop.htm.twig', [
            'products' => $products,
            'form' => $form->createView()

        ]);
    }


    /**
     * @Route("product/featured",name="featured_products")
     */
    public function featuredProductsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();
        // $cart->setOwnedBy($user);


        $form = $this->createForm(addToCartFormType::class, $cart);

        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveFeaturedProductsOrderByDate();

        return $this->render('product/featured.html.twig',[
            'featuredProducts'=>$products,
            'form' => $form->createView()

        ]);
    }
    /**
     * @Route("product/new",name="new_products")
     */
    public function newProductsAction(){
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveNewProductsOrderByDate();

        return $this->render('product/featured.html.twig',[
            'featuredProducts'=>$products,
        ]);
    }
    /**
     * @Route("/shop/{id}/view",name="product_details")
     */
    public function showAction(Request $request, Product $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $existingCart = $em->getRepository('AppBundle:Cart')
                ->findMyCart($user);
            $quantity = $request->request->get('quantity');
            $price = $request->request->get('productPrice');
            $currency = $request->request->get('productCurrency');

            //Create The cart Item
            $cartItem = new CartItems();
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($price);
            $cartItem->setProduct($product);
            $lineTotal = ($price) * ($quantity);
            $cartItem->setLineTotal($lineTotal);

            //Update the Cart
            if ($existingCart) {
                $existingCart[0]->setCartAmount(($existingCart[0]->getCartAmount()) + ($lineTotal));
                $existingCart[0]->setNrItems(($existingCart[0]->getNrItems()) + $quantity);
                $cartItem->setCart($existingCart[0]);
                $em->persist($existingCart[0]);
            } else {
                $cart->setCartAmount($lineTotal);
                $cart->setNrItems($quantity);
                $cart->setCartCurrency($currency);
                $cartItem->setCart($cart);
                $em->persist($cart);
            }
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success', 'Product Successfully Added to Cart!');

            return $this->redirectToRoute('products');
        }
        return $this->render('::product-details.htm.twig',[
            'product' => $product,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/shop/cart",name="updatecart")
     */
    public function addToCart(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $existingCart = $em->getRepository('AppBundle:Cart')
                ->findMyCart($user);
            $quantity = $request->request->get('quantity');
            $price = $request->request->get('productPrice');
            $currency = $request->request->get('productCurrency');

            //Create The cart Item
            $cartItem = new CartItems();
            $cartItem->setQuantity($quantity);
            $cartItem->setUnitPrice($price);
            $lineTotal = ($price) * ($quantity);
            $cartItem->setLineTotal($lineTotal);

            //Update the Cart
            if ($existingCart[0]) {
                $existingCart[0]->setCartAmount(($existingCart[0]->getCartAmount()) + ($lineTotal));
                $existingCart[0]->setNrItems(($existingCart[0]->getNrItems()) + $quantity);
                $cartItem->setCart($existingCart[0]);
                $em->persist($existingCart[0]);
            } else {
                $cart->setCartAmount($lineTotal);
                $cart->setNrItems($quantity);
                $cart->setCartCurrency($currency);
                $cartItem->setCart($cart);
                $em->persist($cart);
            }
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success', 'Product Successfully Added to Cart!');

            return $this->redirectToRoute('products');
        }
        return $this->render('product/shop-grid.htm.twig', [
            'form' => $form->createView()

        ]);

    }


}
