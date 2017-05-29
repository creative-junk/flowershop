<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/27/2017
 ********************************************************************************/

namespace AppBundle\Controller\User;


use AppBundle\Entity\Auction;
use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\AuctionOrderItems;
use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\GrowersList;
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\Product;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AddGrowerForm;
use AppBundle\Form\addToCartFormType;
use AppBundle\Form\AuctionBuyForm;
use AppBundle\Form\CheckoutForm;
use AppBundle\Form\FilterFormType;
use AppBundle\Form\PaymentMethodFormType;
use AppBundle\Form\ShippingAddressFormType;
use AppBundle\Form\ShippingMethodFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/home")
 * @Security("is_granted('ROLE_BUYER')")
 *
 */
class HomeController extends Controller
{
    /**
     * @Route("/",name="home")
     */
    public function userHomeAction()
    {
        return $this->render('home/home.htm.twig');
    }

    /**
     * @Route("/home/profile",name="my_profile")
     */
    public function profileAction()
    {
        return $this->render('home.htm.twig');
    }

    /**
     * @Route("/home/wishlist",name="my_wishlist")
     */
    public function wishlistAction()
    {
        return $this->render('home.htm.twig');
    }

    /**
     * @Route("/home/compare",name="my_compare")
     */
    public function compareAction()
    {
        return $this->render('home.htm.twig');
    }

    /**
     * @Route("/market/filter",name="filter-products")
     */
    public function filterProductAction(Request $request){

        $filterValues = Array();


        $form = $this->createForm(FilterFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $filterValues=$form->getData();

            $filteredForm = $this->createForm(FilterFormType::class,$filterValues);

            // initialize a query builder
            $filterBuilder = $this->get('doctrine.orm.entity_manager')
                ->getRepository('AppBundle:Product')
                ->createQueryBuilder('e');

            // build the query from the given form object
            $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $filterBuilder);

            $query = $filterBuilder->getQuery();
            /**
             * @var $paginator \Knp\Component\Pager\Paginator
             */
            $paginator  = $this->get('knp_paginator');

            $result = $paginator->paginate(
                $query,
                $request->query->getInt('page', 1),
                $request->query->getInt('limit', 9)
            );

            return $this->render('home/Filter/shop.htm.twig', [
                'form' => $filteredForm->createView(),
                'products' => $result

            ]);
        }

        return $this->render('home/Filter/filter.htm.twig', [
            'form' => $form->createView()

        ]);
    }
    /**
     * @Route("/market/",name="buyer_shop")
     */
    public function buyerShopGridAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        //$form = $this->createForm(addToCartFormType::class, $cart);

        $form = $this->createForm(FilterFormType::class);

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Product')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
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


        return $this->render('home/shop.htm.twig', [
            'products' => $result,
            'form' => $form->createView(),
            'filterValues'=>''
        ]);
    }
    /**
     * @Route("/market/grower/view/roses/{id}",name="view_grower_roses")
     */
    public function viewGrowerRosesAction(Request $request,User $user)
    {

        $form = $this->createForm(FilterFormType::class);

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Product')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.user= :isGrower')
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


        return $this->render('home/shop.htm.twig', [
            'products' => $result,
            'form' => $form->createView(),
            'filterValues'=>''
        ]);
    }
    /**
     * @Route("/market/{id}/view",name="buyer_product_details")
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

            $existingCartItem = $em->getRepository('AppBundle:CartItems')
                ->findItemInCart($product);

            if ($existingCartItem){
                $newQty=$existingCartItem[0]->getQuantity()+$quantity;
                $existingCartItem[0]->setQuantity($newQty);
                $lineTotal = ($price) * ($newQty);
                $existingCartItem[0]->setLineTotal($lineTotal);
                $cartItem = $existingCartItem[0];
            }else {
                //Create The cart Item
                $cartItem = new CartItems();
                $cartItem->setQuantity($quantity);
                $cartItem->setUnitPrice($price);
                $cartItem->setProduct($product);
                $lineTotal = ($price) * ($quantity);
                $cartItem->setLineTotal($lineTotal);
            }
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

            $this->addFlash('success', 'Product Successfully Added to Cart!');
            return new Response(null, 204);
            //return $this->redirectToRoute('buyer_shop');
        }
        return $this->render('home/product-details.htm.twig', [
            'product' => $product,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/roses",name="buyer_roses")
     */
    public function buyerRosesAction()
    {
        return $this->render('home/home.htm.twig');
    }

    /**
     * @Route("/growers",name="buyer_growers")
     */
    public function buyerGrowersAction(Request $request = null)
    {
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $buyerGrowers = $em->getRepository('AppBundle:BuyerGrower')
            ->findBy([
                'listOwner' => $buyer
            ]);
        $growerIds = array();

        if ($buyerGrowers) {

            foreach ($buyerGrowers as $buyerGrower) {
                $growerIds[] = $buyerGrower->getGrower();
            }
        }else{
            $growerIds[] = 1;
        }

        $queryBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:growers)')
            ->setParameter('growers',$growerIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.userType = :userType')
            ->setParameter('userType', 'grower');

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

        return $this->render('home/growers/list.html.twig', [
            'growers' => $result,
        ]);

    }
    /**
     * @Route("/growers/my",name="my_buyer_growers")
     */
    public function myGrowersAction(Request $request){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('buyer_grower')
            ->andWhere('buyer_grower.listOwner = :whoOwns')
            ->setParameter('whoOwns',$buyer)
            ->andWhere('buyer_grower.status = :whatStatus')
            ->setParameter('whatStatus','Accepted');

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

        return $this->render('home/growers/mygrowers.htm.twig', [
            'buyerGrowers' => $result,
        ]);

    }
    /**
     * @Route("/growers/{id}/view",name="view_grower")
     */
    public function viewGrowerAction(Request $request, User $grower)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $products = $grower->getProducts();
        $nrproducts = $em->getRepository('AppBundle:Product')
            ->findMyActiveProducts($grower);
         $nrAuctionProducts = $em->getRepository('AppBundle:Auction')
             ->findMyActiveAuctionProducts($grower);

        return $this->render('home/growers/grower-details.htm.twig', [
            'grower' => $grower,
            'products'=>$products,
            'nrProducts' => $nrproducts,
            'nrAuctionProducts' => $nrAuctionProducts
        ]);

    }
    /**
     * @Route("/agents",name="buyer_agents")
     */
    public function buyerAgentsAction(Request $request = null)
    {
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $buyerAgents = $em->getRepository('AppBundle:BuyerAgent')
            ->findBy([
                'listOwner' => $buyer
            ]);
        $agentIds = array();

        if ($buyerAgents) {

            foreach ($buyerAgents as $buyerAgent) {
                $agentIds[] = $buyerAgent->getAgent();
            }
        }else{
            $agentIds[] = 1;
        }
        $queryBuilder = $em->getRepository('AppBundle:User')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:agents)')
            ->setParameter('agents',$agentIds)
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

        return $this->render('home/agents/list.html.twig', [
            'agents' => $result,
        ]);

    }
    /**
     * @Route("/agents/my",name="my_buyer_agents")
     */
    public function myBuyerAgentsAction(Request $request){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('buyer_agent')
            ->andWhere('buyer_agent.listOwner = :whoOwns')
            ->setParameter('whoOwns',$buyer)
            ->andWhere('buyer_agent.status = :whatStatus')
            ->setParameter('whatStatus','Accepted');


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

        return $this->render(':home/agents:myagents.htm.twig', [
            'buyerAgents' => $result,
        ]);

    }

    /**
     * @Route("/agents/{id}/view",name="view_agent")
     */
    public function agentProfileActionAction(Request $request, User $agent)
    {
        $em = $this->getDoctrine()->getManager();

        $products = $agent->getProducts();
        $nrproducts = $em->getRepository('AppBundle:Product')
            ->findMyActiveProducts($agent);
        $nrAuctionProducts = $em->getRepository('AppBundle:Auction')
            ->findMyActiveAuctionProducts($agent);

        return $this->render('home/agents/view.htm.twig',[
            'agent' => $agent,
            'products'=>$products,
            'nrProducts' => $nrproducts,
            'nrAuctionProducts' => $nrAuctionProducts

        ]);

    }

    /**
     * @Route("/agents/roses/{id}",name="buyer_view_agent_roses")
     */
    public function agentAuctionRosesAction(){
        return $this->render('home/agents/auction.html.twig');
    }


    /**
     * @Route("/orders/",name="my_order_list")
     */
    public function ordersListAction()
    {

        return $this->render(':home:order.htm.twig');

    }

    /**
     * @Route("/orders/my",name="order_list")
     */
    public function myOrdersListAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyOrdersOrderByDate($user);
        return $this->render('home/order/list.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/my/{id}/view",name="order-details")
     */
    public function orderDetailsAction(Request $request, UserOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(':home/order:order-details.htm.twig',[
            'order'=>$order,
            'orderItems'=>$order->getOrderItems()
            ]);
    }

    /**
     * @Route("/auction/orders/my",name="auction_order_list")
     */
    public function myAuctionOrdersListAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:AuctionOrder')
            ->findAllMyOrdersOrderByDate($user);
        return $this->render('home/order/auction/list.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/auction/orders/my/{id}/view",name="auction-order-details")
     */
    public function auctionOrderDetailsAction(Request $request, AuctionOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(':home/order/auction:order-details.htm.twig',[
            'order'=>$order,
            'orderItems'=>$order->getOrderItems()
        ]);
    }
    /**
     * @Route("/requests/my/growers",name="my_buyer_grower_requests")
     */
    public function getMyGrowerRequestsAction(Request $request){
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

        return $this->render('home/growers/myRequests.htm.twig', [
            'growerRequests' => $result,
        ]);
    }
    /**
     * @Route("/requests/my/agents",name="my_buyer_agent_requests")
     */
    public function getMyAgentRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:BuyerAgent')
                                ->getMyAgentRequests($user);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('home/agents/myRequests.htm.twig', [
            'agentRequests' => $result,
        ]);
    }
    /**
     * @Route("/requests/growers",name="buyer_grower_requests")
     */
    public function getGrowerRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:BuyerGrower')
                    ->getGrowerRequestsQuery($user);
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('home/growers/requests.html.twig', [
            'breederRequests' => $result,
        ]);
    }
    /**
     * @Route("/requests/agents",name="buyer_agent_requests")
     */
    public function getAgentRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:BuyerAgent')
                        ->getAgentRequestsQuery($user);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('home/agents/requests.html.twig', [
            'agentRequests' => $result,
        ]);
    }
    /**
     * @Route("/cart",name="buyer-cart")
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
     * @Route("/checkout",name="buyer_checkout")
     */
    public function checkoutAction(Request $request){
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
            $em->persist($billingAddress);
            $em->flush();
            return $this->redirectToRoute('buyer_shipping');

        }
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        return $this->render(':partials/iflora/user:checkout.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }
    /**
     * @Route("/shipping-address",name="buyer_shipping")
     */
    public function shippingAddressAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $shippingAddress = new ShippingAddress();

        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
                ->findMyBillingAddress($user);

        if ($billingAddress){
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
            $shippingAddress->setUser($user);
            $shippingAddress->setFirstName($user->getFirstName());
            $shippingAddress->setLastName($user->getLastName());
            $shippingAddress->setEmailAddress($user->getUserName());

        }


        $form = $this->createForm(ShippingAddressFormType::class, $shippingAddress);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($shippingAddress);
            $em->flush();
            return $this->redirectToRoute('buyer_shipping_method');

        }

        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);


        return $this->render(':partials/iflora/user:shipping-address.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }
    /**
     * @Route("/shipping-method",name="buyer_shipping_method")
     */
    public function shippingMethodAction(Request $request){
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
            return $this->redirectToRoute('buyer_payment_method');

        }

         return $this->render(':partials/iflora/user:shipping-method.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }
    /**
     * @Route("/payment",name="buyer_payment_method")
     */
    public function paymentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user);
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);


        $myOrder = new UserOrder();
        $myOrder->setCreatedAt(new \DateTime());
        $myOrder->setBillingAddress($billingAddress[0]);
        $myOrder->setShippingAddress($shippingAddress[0]);
        $myOrder->setUser($user);
        $myOrder->setOrderStatus("Pending");
        $myOrder->setOrderNotes("None");
        $myOrder->setIsAuctionOrder(false);
        $myOrder->setCheckoutCompletedAt(new \DateTime());
        $myOrder->setOrderState("Active");
        $myOrder->setOrderAmount($cart[0]->getCartAmount());
        $myOrder->setOrderCurrency($cart[0]->getCartCurrency());
        $myOrder->setShippingCost($cart[0]->getShippingCost());
        $myOrder->setOrderTotal($cart[0]->getCartTotal());


        $orderItems = new ArrayCollection();
        $cartItems = $cart[0]->getCartItems();


        foreach ( $cartItems as $cartItem){
            $orderItem = new OrderItems();
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setUnitPrice($cartItem->getUnitPrice());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setLineTotal($cartItem->getLineTotal());
            $orderItem->setOrder($myOrder);
            $em->persist($orderItem);
            $em->remove($cartItem);
        }
        //$myOrder->setOrderItems($orderItems);

        $form = $this->createForm(PaymentMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myOrder->setProcessingFee($request->request->get("paymentMethod"));
            $em->persist($myOrder);
            $em->remove($cart[0]);
            $em->flush();
            $this->container->get('session')->set('order', $myOrder);
            return $this->redirectToRoute('checkout-complete');

        }

        return $this->render(':partials/iflora/user:pay.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }

    /**
     * @Route("/checkout/complete",name="checkout-complete")
     */
    public function checkoutCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $order = $this->container->get('session')->get('order');

        return $this->render(':partials/iflora/user:checkout-complete.htm.twig',[
            'order'=>$order
        ]);
    }

    /**
     * @Route("/auction/",name="buyer_auction")
     */
    public function auctionListAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
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

        return $this->render('buyer/auction/list.htm.twig', [
            'products' => $result,
        ]);


    }

    /**
     * @Route("/auction/{id}/view",name="buyer_auction_product_details")
     */
    public function auctionProductDetailsAction(Request $request, Auction $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('buyer/auction/product-details.htm.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/auction/buy/{id}",name="buyer_checkout_auction")
     */
    public function buyAuctionProductAction(Request $request, Auction $product){
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
        $form = $this->createForm(AuctionBuyForm::class, $billingAddress);


        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($billingAddress);
            $em->flush();
            $this->container->get('session')->set('product', $product);

            return $this->redirectToRoute('auction_buyer_shipping');

        }


        return$this->render(':partials/iflora/user/auction:checkout.htm.twig',[
            'buyerCheckoutForm'=>$form->createView(),
            'product'=>$product
        ]);
    }
    /**
     * @Route("/auction/shipping-address",name="auction_buyer_shipping")
     */
    public function auctionShippingAddressAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $product = $this->container->get('session')->get('product');

        $em = $this->getDoctrine()->getManager();

        $shippingAddress = new ShippingAddress();

        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);

        if ($billingAddress){
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
            $shippingAddress->setUser($user);
            $shippingAddress->setFirstName($user->getFirstName());
            $shippingAddress->setLastName($user->getLastName());
            $shippingAddress->setEmailAddress($user->getUserName());

        }


        $form = $this->createForm(ShippingAddressFormType::class, $shippingAddress);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($shippingAddress);
            $em->flush();

            $this->container->get('session')->set('product', $product);

            return $this->redirectToRoute('auction_buyer_shipping_method');

        }

        return $this->render(':partials/iflora/user/auction:shipping-address.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'product'=> $product
        ]);
    }
    /**
     * @Route("/auction/shipping-method",name="auction_buyer_shipping_method")
     */
    public function auctionShippingMethodAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $product = $this->container->get('session')->get('product');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ShippingMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shippingCost = $request->request->get('shippingCost');

            $this->container->get('session')->set('product', $product);
            $this->container->get('session')->set('shippingCost',$shippingCost);
            return $this->redirectToRoute('auction_buyer_agent_checkout');

        }

        return $this->render(':partials/iflora/user/auction:shipping-method.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'product'=> $product
        ]);
    }
    /**
     * @Route("/auction/checkout-agent",name="auction_buyer_agent_checkout")
     */
    public function auctionAgentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $product = $this->container->get('session')->get('product');

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ShippingMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->container->get('session')->set('product', $product);

            return $this->redirectToRoute('auction_buyer_payment_method');

        }

        return $this->render(':partials/iflora/user/auction:my-agent.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'product'=> $product
        ]);
    }
    /**
     * @Route("/auction/payment",name="auction_buyer_payment_method")
     */
    public function auctionPaymentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user);

        $product = $this->container->get('session')->get('product');
        $shippingCost = $this->container->get('session')->get('shippingCost');

        $myOrder = new AuctionOrder();
        $myOrder->setCreatedAt(new \DateTime());
        $myOrder->setBillingAddress($billingAddress[0]);
        $myOrder->setShippingAddress($shippingAddress[0]);
        $myOrder->setSoldBy($user);
        $myOrder->setOrderStatus("Pending");
        $myOrder->setOrderNotes("None");

        $myOrder->setCheckoutCompletedAt(new \DateTime());
        $myOrder->setOrderState("Active");
        $myOrder->setOrderAmount($product->getPrice());
        $myOrder->setOrderCurrency($product->getCurrency());
        $myOrder->setShippingCost($shippingCost);
        $myOrder->setOrderTotal($product->getPrice()+$shippingCost);


        $orderProduct = $em->find("AppBundle:Auction",$product->getId());
        $orderItem = new AuctionOrderItems();
        $orderItem->setProduct($orderProduct);
        $orderItem->setUnitPrice($product->getPrice());
        $orderItem->setQuantity($product->getQuantity());
        $orderItem->setLineTotal($product->getPrice());
        $orderItem->setOrder($myOrder);
        $em->persist($orderItem);

        $form = $this->createForm(PaymentMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $myOrder->setAgent($orderProduct->getAgent());
            $myOrder->setProcessingFee($request->request->get("paymentMethod"));
            $em->persist($myOrder);
            $em->flush();

            $this->container->get('session')->set('product', '');
            $this->container->get('session')->set('shippingCost', '');

            $this->container->get('session')->set('order', $myOrder);
            return $this->redirectToRoute('auction-checkout-complete');

        }

        return $this->render(':partials/iflora/user/auction:pay.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/auction/checkout/complete",name="auction-checkout-complete")
     */
    public function auctionCheckoutCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $order = $this->container->get('session')->get('order');

        return $this->render(':partials/iflora/user/auction:checkout-complete.htm.twig',[
            'order'=>$order
        ]);
    }



    public function getTotalRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrGrowerRequests($user);

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrAgentRequests($user);
        $totalRequests += $nrBreederRequests;
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getTotalGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrGrowerRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrGrowerRequests($user);

        $totalRequests += $nrGrowerRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getTotalAgentRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrAgentRequests($user);
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyTotalRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrMyGrowerRequests($user);

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyAgentRequests($user);
        $totalRequests += $nrBreederRequests;
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyTotalGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrMyGrowerRequests($user);

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyTotalAgentRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyAgentRequests($user);
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

}