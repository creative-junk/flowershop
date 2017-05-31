<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Grower;

use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\User;
use AppBundle\Entity\GrowerBreeder;
use AppBundle\Entity\Auction;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\addToCartFormType;
use AppBundle\Form\AuctionProductForm;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\CheckoutForm;
use AppBundle\Form\LoginForm;
use AppBundle\Form\PaymentMethodFormType;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\ShippingAddressFormType;
use AppBundle\Form\ShippingMethodFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/grower")
 * @Security("is_granted('ROLE_GROWER')")
 *
 */
class GrowerController extends Controller
{
    /**
     * @Route("/",name="grower_dashboard")
     */
    public function dashboardAction()
    {

        return $this->render(':grower:home.htm.twig');
        //dump($products);die;
        //return new Response('Product Saved');
    }

    /**
     * @Route("/product/",name="grower_product_list")
     */
    public function listAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllMyActiveProductsOrderByDate($user);

        return $this->render('grower/product/mylist.html.twig', [
            'products' => $products,
        ]);

    }

    /**
     * @Route("/product/seedlings",name="grower_seedlings_list")
     */
    public function listSeedlingsAction(Request $request = null)
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Product')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', true)
            ->orderBy('product.createdAt', 'DESC');
        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );


        return $this->render('grower/seedlings/shop.htm.twig', [
            'products' => $result,
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/product/my",name="my_grower_product_list")
     */
    public function myProductListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        /*$products = $em->getRepository('AppBundle:Product')
            ->findAllMyActiveProductsOrderByDate($user);
        */
        $queryBuilder = $em->getRepository('AppBundle:Product')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.user = :isGrower')
            ->setParameter('isGrower', $user)
            ->orderBy('product.createdAt', 'DESC');
        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 20)
        );
        return $this->render('grower/product/mylist.html.twig', [
            'products' => $result,
        ]);

    }

    /**
     * @Route("/product/new",name="grower_product_new")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setUser($this->get('security.token_storage')->getToken()->getUser());
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setIsSeedling(false);
        $form = $this->createForm(ProductFormType::class, $product);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product Created Successfully!');

            return $this->redirectToRoute('my_grower_product_list');
        }

        return $this->render('grower/product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/product/my/{id}/view",name="product_details")
     */
    public function showAction(Request $request, Product $product)
    {

        return $this->render('grower/product/product-details.htm.twig', [
            'product' => $product,

        ]);
    }

    /**
     * @Route("/product/seedlings/{id}/view",name="seedling_details")
     */
    public function showSeedlingAction(Request $request, Product $product)
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
            $quantity = $request->request->get('qty');
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
                $existingCart[0]->setCartTotal(($existingCart[0]->getCartTotal()) + ($lineTotal));
                $existingCart[0]->setNrItems(($existingCart[0]->getNrItems()) + $quantity);
                $cartItem->setCart($existingCart[0]);
                $em->persist($existingCart[0]);
            } else {
                $cart->setCartAmount($lineTotal);
                $cart->setCartTotal($lineTotal);
                $cart->setNrItems($quantity);
                $cart->setCartCurrency($currency);
                $cartItem->setCart($cart);
                $em->persist($cart);
            }
            $em->persist($cartItem);
            $em->flush();

            $this->addFlash('success', 'Seedling Successfully Added to Cart!');

            return $this->redirectToRoute('grower_seedlings_list');
        }
        return $this->render('grower/seedlings/product-details.htm.twig', [
            'product' => $product,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/product/{id}/edit",name="grower_product_edit")
     */
    public function editAction(Request $request, Product $product)
    {
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setIsSeedling(false);

        $form = $this->createForm(ProductFormType::class, $product);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product Updated Successfully!');

            return $this->redirectToRoute('my_grower_product_list');
        }

        return $this->render('grower/product/edit.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/orders/",name="grower_order_list")
     */
    public function ordersListAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyReceivedOrdersOrderByDate($user);
        return $this->render('grower/order/list.html.twig', [
            'orders' => $orders,
        ]);

    }

    /**
     * @Route("/orders/my",name="my_grower_order_list")
     */
    public function myOrdersListAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyOrdersOrderByDate($user);
        return $this->render('grower/order/mylist.html.twig', [
            'orders' => $orders,
        ]);

    }

    /**
     * @Route("/orders/{id}/update",name="grower_order_update")
     */
    public function updateOrderStatusAction(Request $request, UserOrder $order)
    {
        $form = $this->createForm(ProductFormType::class, $order);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Order Status Updated Successfully!');

            return $this->redirectToRoute('grower_order_list');
        }

        return $this->render('grower/order/update.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/auction/",name="auction_product_list")
     */
    public function auctionListAction()
    {

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllActiveAuctionProductsOrderByDate();

        return $this->render('grower/product/list.html.twig', [
            'products' => $products,
        ]);

    }

    /**
     * @Route("/auction/my/products",name="my_grower_auction_list")
     */
    public function myAuctionProductListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.user = :isGrower')
            ->setParameter('isGrower', $user)
            ->orderBy('product.createdAt', 'DESC');

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/auction/product/mylist.html.twig', [
            'products' => $result,
        ]);

    }

    /**
     * @Route("/auction/product/new",name="grower_auction_new")
     */
    public function newAuctionProductAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $product = new Auction();
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setUser($user);
        $product->setStatus("Pending Agent");

        $em = $this->getDoctrine()->getManager();
        $growerAgents = $em->getRepository("AppBundle:GrowerAgent")
            ->findBy([
            'grower'=>$user,
            'status'=>"Accepted"
        ]);
        $agents=array();
        foreach ($growerAgents as $growerAgent) {
            $agents[]=$growerAgent->getAgent();
        }
        //$product->setAgent($agents);

        $form = $this->createForm(AuctionProductForm::class, $product, ['agents' => $agents]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em->persist($product);
            $em->flush();
            //TODO Notify the agent of the Assignment
            $this->addFlash('success', 'Product Posted to Auction Successfully!');

            return $this->redirectToRoute('my_grower_auction_list');
        }

        return $this->render('grower/auction/product/new.html.twig', [
            'productForm' => $form->createView(),
            'agents' => $agents
        ]);
    }

    /**
     * @Route("/auction/product/{id}/edit",name="grower_auction_product_edit")
     */
    public function editAuctionProductAction(Request $request, Auction $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $growerAgents = $em->getRepository("AppBundle:GrowerAgent")
            ->findBy([
                'grower'=>$user,
                'status'=>"Accepted"
            ]);
        $agents=array();
        foreach ($growerAgents as $growerAgent) {
            $agents[]=$growerAgent->getAgent();
        }

        $form = $this->createForm(AuctionProductForm::class, $product, ['agents' => $agents]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product in Auction Updated Successfully!');

            return $this->redirectToRoute('my_grower_auction_list');
        }

        return $this->render('grower/auction/product/edit.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/breeders/",name="breeder_list")
     */
    public function breederListAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'breeder');

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
        return $this->render('grower/breeders/list.html.twig', [
            'breeders' => $result,
        ]);
    }

    /**
     * @Route("/breeders/my",name="my_breeder_list")
     */
    public function myBreederListAction(Request $request)
    {
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerBreeder')
            ->createQueryBuilder('gb')
            ->andWhere('gb.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('gb.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $grower);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
        return $this->render('grower/breeders/mylist.html.twig', [
            'growerBreeders' => $result,
        ]);
    }

    /**
     * @Route("/breeders/{id}/view",name="breeder_profile")
     */
    public function breederProfileAction(Request $request, User $breeder)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("AppBundle:Product")
            ->findAllUserActiveSeedlingsOrderByDate($breeder);
        $nrProducts = $em->getRepository("AppBundle:Product")
            ->findNrActiveBreederSeedlings($breeder);
        return $this->render(':grower/breeders:profile.html.twig', [
            'breeder' => $breeder,
            'products' => $products,
            'nrProducts' => $nrProducts,
        ]);

    }

    /**
     * @Route("/buyer/{id}/view",name="grower_buyer_profile")
     */
    public function buyerProfileAction(User $buyer)
    {
        $em = $this->getDoctrine()->getManager();
        $billingAddress = $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($buyer);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($buyer);
        if ($billingAddress) {
            $billingAddress = $billingAddress[0];
        }
        if ($shippingAddress) {
            $shippingAddress = $shippingAddress[0];
        }
        return $this->render('grower/buyers/buyer-profile.htm.twig', [
            'buyer' => $buyer,
            'billingAddress' => $billingAddress,
            'shippingAddress' => $shippingAddress,
        ]);
    }

    /**
     * @Route("/agents/",name="grower_agent_list")
     */
    public function agentListAction(Request $request)
    {
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $growerAgents = $em->getRepository('AppBundle:GrowerAgent')
            ->findBy([
                'listOwner' => $grower
            ]);
        $agentIds = array();

        if ($growerAgents) {

            foreach ($growerAgents as $growerAgent) {
                $agentIds[] = $growerAgent->getAgent();
            }
        } else {
            $agentIds[] = 1;
        }


        $queryBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:agents)')
            ->setParameter('agents', $agentIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'agent');

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/agents/list.html.twig', [
            'agents' => $result,
        ]);
    }

    /**
     * @Route("/agents/my",name="my_grower_agent_list")
     */
    public function myAgentListAction(Request $request)
    {
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerAgent')
            ->createQueryBuilder('gb')
            ->andWhere('gb.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('gb.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $grower);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
        return $this->render('grower/agents/mylist.html.twig', [
            'growerAgents' => $result,
        ]);
    }

    /**
     * @Route("/agents/product/{id}/view",name="agent_profile_product_details")
     */
    public function auctionProfile(Auction $product)
    {
        return $this->render(':grower/agents:product-details.htm.twig', [
            'product' => $product
        ]);
    }

    /**
     * @Route("/agents/{id}/view",name="agent_profile")
     */
    public function agentProfileAction(User $agent)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("AppBundle:Auction")
            ->findAllMyActiveAgentProductsOrderByDate($agent);

        $nrProducts = $em->getRepository("AppBundle:Auction")
            ->findNrMyActiveProductsAgent($agent);

        return $this->render(':grower/agents:profile.html.twig', [
            'agent' => $agent,
            'products' => $products,
            'nrProducts' => $nrProducts,
        ]);
    }

    /**
     * @Route("/agents/profile/{id}/view",name="agent_details")
     */
    public function agentProfileDetailsAction(User $agent)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("AppBundle:Auction")
            ->findAllMyActiveAgentProductsOrderByDate($agent);

        $nrProducts = $em->getRepository("AppBundle:Auction")
            ->findNrMyActiveProductsAgent($agent);

        return $this->render(':grower/agents:profile-details.html.twig', [
            'agent' => $agent,
            'products' => $products,
            'nrProducts' => $nrProducts,
        ]);
    }

    /**
     * @Route("/buyers",name="grower_buyer_list")
     */
    public function buyerListAction(Request $request = null)
    {
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('user')
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'buyer');

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/buyers/list.html.twig', [
            'buyers' => $result,
        ]);
    }


    /**
     * @Route("/buyers/my",name="my_grower_buyer_list")
     */
    public function myBuyerListAction(Request $request)
    {
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('gb')
            ->andWhere('gb.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('gb.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $grower);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
        return $this->render('grower/buyers/mylist.html.twig', [
            'buyerGrowers' => $result,
        ]);
    }

    /**
     * @Route("/checkout/",name="grower-checkout")
     *
     */
    public function billingAddressAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $billingAddressArray = $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);
        if ($billingAddressArray){
            $billingAddress= $billingAddressArray[0];
        }else {

            $billingAddress = new BillingAddress();
            $billingAddress->setUser($user);
            $billingAddress->setFirstName($user->getFirstName());
            $billingAddress->setLastName($user->getLastName());
            $billingAddress->setEmailAddress($user->getUserName());
        }
        $form = $this->createForm(CheckoutForm::class, $billingAddress);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $billingAddress = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($billingAddress);
            $em->flush();

            return $this->redirectToRoute('shipping-address');
        }
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        return $this->render(':partials:checkout-billing.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/shipping",name="shipping-address")
     *
     */
    public function shippingAddressAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);

        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user);

        if ($shippingAddress){

            $shippingAddress=$shippingAddress[0];

        }else if (!$shippingAddress && $billingAddress){
            $shippingAddress = new ShippingAddress();
            $shippingAddress->setUser($user);
            $shippingAddress->setFirstName($billingAddress[0]->getFirstName());
            $shippingAddress->setLastName($billingAddress[0]->getLastName());
            $shippingAddress->setEmailAddress($billingAddress[0]->getEmailAddress());
            $shippingAddress->setCompany($billingAddress[0]->getCompany());
            $shippingAddress->setStreetAddress($billingAddress[0]->getStreetAddress());
            $shippingAddress->setTown($billingAddress[0]->getTown());
            $shippingAddress->setCountry($billingAddress[0]->getCountry());
            $shippingAddress->setZip($billingAddress[0]->getZip());
            $shippingAddress->setPhoneNumber($billingAddress[0]->getPhoneNumber());

        }else{
            $shippingAddress = new ShippingAddress();
            $shippingAddress->setUser($user);
            $shippingAddress->setFirstName($user->getFirstName());
            $shippingAddress->setLastName($user->getLastName());
            $shippingAddress->setEmailAddress($user->getUserName());

        }


        $form = $this->createForm(ShippingAddressFormType::class, $shippingAddress);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $shippingAddress = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($shippingAddress);
            $em->flush();

            return $this->redirectToRoute('shipping-method');
        }

        return $this->render(':partials:checkout-shipping-address.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/shipping-method",name="shipping-method")
     *
     */
    public function shippingMethodAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        $form = $this->createForm(ShippingMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shippingCost = $request->request->get('shippingCost');
            $cartTotal=$cart[0]->getCartTotal()-$cart[0]->getShippingCost();

            $cart[0]->setShippingCost($shippingCost);
            $cartTotal+=$shippingCost;
            $cart[0]->setCartTotal($cartTotal);
            $em->persist($cart[0]);
            $em->flush();

            return $this->redirectToRoute('payment');
        }

        return $this->render(':partials:checkout-shipping-method.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/payment",name="payment")
     *
     */
    public function paymentAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        $form = $this->createForm(PaymentMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('shipping-method');
        }

        return $this->render(':partials:checkout-pay.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/cart",name="grower-cart")
     */
    public function growerCartAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);
        if ($cart) {
            $cartItems = $em->getRepository('AppBundle:CartItems')
                ->findAllItemsInMyCartOrderByDate($cart[0]);
        } else {
            $cartItems = "";
        }
        return $this->render('cart.htm.twig', [
            'cartItems' => $cartItems,
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/requests/my/breeders",name="my_breeder_requests")
     */
    public function getMyBreederRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerBreeder')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/breeders/requests.html.twig', [
            'breederRequests' => $result,
        ]);
    }

    /**
     * @Route("/requests/my/buyers",name="my_grower_buyer_requests")
     */
    public function getMyBuyerRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/buyers/requests.html.twig', [
            'buyerRequests' => $result,
        ]);
    }

    /**
     * @Route("/requests/my/agents",name="my_grower_agent_requests")
     */
    public function getMyAgentRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner = :whoOwnsList')
            ->setParameter('whoOwnsList', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/agents/requests.html.twig', [
            'agentRequests' => $result,
        ]);
    }

    /**
     * @Route("/requests/breeders",name="breeder_requests")
     */
    public function getBreederRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerBreeder')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/breeders/requests.html.twig', [
            'breederRequests' => $result,
        ]);
    }

    /**
     * @Route("/requests/buyers",name="grower_buyer_requests")
     */
    public function getGrowerBuyerRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/buyers/requests.html.twig', [
            'buyerRequests' => $result,
        ]);
    }

    /**
     * @Route("/requests/agents",name="grower_agent_requests")
     */
    public function getAgentRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('grower/agents/requests.html.twig', [
            'agentRequests' => $result,
        ]);
    }

    public function getTotalRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrBreederRequests($user);
        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrBuyerRequests($user);
        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrAgentRequests($user);
        $totalRequests += $nrBreederRequests;
        $totalRequests += $nrBuyerRequests;
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getTotalBreederRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrBreederRequests($user);

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getTotalBuyerRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrBuyerRequests($user);
        $totalRequests += $nrBuyerRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getTotalAgentRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrAgentRequests($user);
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getMyTotalRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrMyBreederRequests($user);

        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyAgentRequests($user);
        $totalRequests += $nrBreederRequests;
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getMyTotalBreederRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrMyBreederRequests($user);

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getMyTotalAgentRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyAgentRequests($user);
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    public function getMyTotalBuyerRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrMyBuyerRequests($user);

        $totalRequests += $nrBuyerRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

    /**
     * @Route("/wishlist/my",name="my-seedling-wishlist")
     */
    public function myWishlistAction()
    {
        $agent = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $wishlist = $em->getRepository('AppBundle:MyList')
            ->getMyWishlist($agent);
        if ($wishlist) {
            $wishlist = $wishlist[0];
        }
        return $this->render(':agent/myList:wishlist.htm.twig', [
            'products' => $wishlist
        ]);


    }


}