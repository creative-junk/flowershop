<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Grower;

use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use AppBundle\Entity\Message;
use AppBundle\Entity\Notification;
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\PayOptions;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\Thread;
use AppBundle\Entity\User;
use AppBundle\Entity\GrowerBreeder;
use AppBundle\Entity\Auction;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Product;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AccountFormType;
use AppBundle\Form\addToCartFormType;
use AppBundle\Form\AuctionProductForm;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\BuyerCompanyForm;
use AppBundle\Form\CheckoutForm;
use AppBundle\Form\DirectProductForm;
use AppBundle\Form\EditDirectProductForm;
use AppBundle\Form\GalleryForm;
use AppBundle\Form\GrowerCompanyForm;
use AppBundle\Form\LoginForm;
use AppBundle\Form\MessageReplyForm;
use AppBundle\Form\PaymentMethodFormType;
use AppBundle\Form\PaymentProofFormType;
use AppBundle\Form\PayOptionType;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\ShippingAddressFormType;
use AppBundle\Form\ShippingMethodFormType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        if ($grower->getIsFirstLogin()&&$user->getIsMainAccount()){
            return $this->redirectToRoute("grower-update-profile",['id'=>$grower->getId()]);
        }

        $em = $this->getDoctrine()->getManager();

        $nrMyReceivedOrders = $em->getRepository('AppBundle:OrderItems')
            ->findNrAllMyReceivedOrders($user->getMyCompany());
        $nrMyOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrAllMyOrdersAgent($user->getMyCompany());
        $nrMyBuyers = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrMyGrowerBuyers($user->getMyCompany());
        $nrMyAgents = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyGrowerAgents($user->getMyCompany());



        return $this->render(':grower:home.htm.twig',[
            'nrMyReceivedOrders'=>$nrMyReceivedOrders,
            'nrMyOrders' =>$nrMyOrders,
            'nrMyBuyers' => $nrMyBuyers,
            'nrMyAgents' => $nrMyAgents,
            'nrMyProducts' =>''
        ]);

    }
    /**
     * @Route("/users/pending",name="grower-pending-users")
     */
    public function pendingUsersAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $buyer = $user->getMyCompany();
        $em = $this->getDoctrine()->getManager();

        $activeUsers = $em->getRepository("AppBundle:User")
            ->findBy([
                'myCompany'=>$buyer,
                'isActive'=>false
            ]);
        return $this->render('users/pending.htm.twig',[
            'users'=>$activeUsers
        ]);

    }
    /**
     * @Route("/users/active",name="grower-active-users")
     */
    public function activeUsersAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $buyer = $user->getMyCompany();
        $em = $this->getDoctrine()->getManager();

        $activeUsers = $em->getRepository("AppBundle:User")
            ->findBy([
                'myCompany'=>$buyer,
                'isActive'=>true
            ]);
        return $this->render('users/active.htm.twig',[
            'users'=>$activeUsers
        ]);

    }

    /**
     * @Route("/update/{id}",name="grower-update-profile")
     */
    public function updateProfileAction(Request $request,Company $company){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsFirstLogin(false);

        $form = $this->createForm(GrowerCompanyForm::class,$company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $company = $form->getData();

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute("grower_dashboard");
        }

        return $this->render("companyProfile/grower.htm.twig",[
            'form'=>$form->createView()
        ]);


    }
    /**
     * @Route("/update/gallery/{id}",name="grower-update-gallery")
     */
    public function updateGalleryAction(Request $request,Company $company){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $gallery = $em->getRepository("AppBundle:Gallery")
            ->findOneBy([
                'myCompany'=>$company->getId()
            ]);

        $form = $this->createForm(GalleryForm::class,$gallery);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $company = $form->getData();

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute("my-grower-profile");
        }

        return $this->render("companyProfile/gallery.htm.twig",[
            'form'=>$form->createView(),
            'gallery'=>$gallery
        ]);


    }

    /**
     * @Route("/payment-options/add",name="grower-add-payment-option")
     */
    public function paymentOptionsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $company = $user->getMyCompany();

        $paymentOption = new PayOptions();
        $paymentOption->setMyCompany($company);

        $form = $this->createForm(PayOptionType::class,$paymentOption);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $paymentOption = $form->getData();

            $em->persist($paymentOption);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');

        }
        return $this->render("companyProfile/payment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/payment-options/{id}/update",name="grower-update-payment-option")
     */
    public function updatePaymentOptionsAction(Request $request,PayOptions $paymentOption){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $company = $user->getMyCompany();

        $form = $this->createForm(PayOptionType::class,$paymentOption);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $paymentOption = $form->getData();

            $em->persist($paymentOption);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');

        }
        return $this->render("companyProfile/updatePayment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/account",name="my-grower-profile")
     */
    public function profileAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        $billingAddress = $em->getRepository("AppBundle:BillingAddress")
            ->findOneBy([
                'company'=>$agent
            ]);

        $shippingAddress = $em->getRepository("AppBundle:ShippingAddress")
            ->findOneBy([
                'company'=>$agent
            ]);

        return $this->render('grower/account/account.htm.twig',[
            'billingAddress'=>$billingAddress,
            'shippingAddress'=>$shippingAddress
        ]);
    }

    /**
     * @Route("/account/edit",name="grower-edit-account")
     */
    public function editAccountAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(AccountFormType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');
        }
        return $this->render('grower/account/edit-basic.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/add/billing",name="grower-add-billing-address")
     */
    public function addBillingAddress(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        $billingAddress = new BillingAddress();
        $billingAddress->setCompany($agent);
        $billingAddress->setCountry($agent->getCountry());
        $billingAddress->setEmailAddress($agent->getEmail());
        $billingAddress->setPhoneNumber($agent->getTelephoneNumber());
        $billingAddress->setIsDefault(true);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(BillingAddressFormType::class,$billingAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $billingAddress = $form->getData();

            $em->persist($billingAddress);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');
        }

        return $this->render('grower/account/add-billing.htm.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/account/edit/billing",name="grower-edit-billing-address")
     */
    public function editBillingAddress(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $billingAddress = $em->getRepository("AppBundle:BillingAddress")
            ->findOneBy([
                'company'=>$agent
            ]);

        $form = $this->createForm(BillingAddressFormType::class,$billingAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $billingAddress = $form->getData();

            $em->persist($billingAddress);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');
        }

        return $this->render('grower/account/edit-billing.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/account/add/shipping",name="grower-add-shipping-address")
     */
    public function addShippingAddress(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        $shippingAddress = new ShippingAddress();
        $shippingAddress->setCompany($agent);
        $shippingAddress->setCountry($agent->getCountry());
        $shippingAddress->setEmailAddress($agent->getEmail());
        $shippingAddress->setPhoneNumber($agent->getTelephoneNumber());
        $shippingAddress->setIsDefault(true);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ShippingAddressFormType::class,$shippingAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $shippingAddress = $form->getData();

            $em->persist($shippingAddress);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');
        }

        return $this->render('grower/account/add-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/edit/shipping",name="grower-edit-shipping-address")
     */
    public function editShippingAddress(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $shippingAddress = $em->getRepository("AppBundle:ShippingAddress")
            ->findOneBy([
                'company'=>$agent
            ]);

        $form = $this->createForm(ShippingAddressFormType::class,$shippingAddress);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $shippingAddress = $form->getData();

            $em->persist($shippingAddress);
            $em->flush();

            return $this->redirectToRoute('my-grower-profile');
        }

        return $this->render('grower/account/edit-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);

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
        $queryBuilder = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
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
     * @Route("/roses/my",name="my_grower_roses")
     */
    public function myRosesListAction(Request $request = null)
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
     * @Route("/products/my",name="my_grower_direct_market")
     */
    public function myDirectMarketListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('product')
         //   ->andWhere('product.isActive = :isActive')
         //  ->setParameter('isActive', true)
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $vendor)
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
        return $this->render('grower/product/myProductlist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/product/new",name="grower_product_new")
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $product = new Product();
        $product->setUser($user);
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setVendor($user->getMyCompany());
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

            return $this->redirectToRoute('my_grower_direct_market');
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
    public function showSeedlingAction(Request $request, Direct $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        $form = $this->createForm(addToCartFormType::class, $cart);


        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart = $form->getData();

            $basePrice = $product->getPricePerStem();
            $currencyFrom = $product->getVendor()->getCurrency();
            $currencyTo = $user->getMyCompany()->getCurrency();


            $em = $this->getDoctrine()->getManager();

            $existingCart = $em->getRepository('AppBundle:Cart')
                ->findMyCart($user);
            $quantity = $request->request->get('quantity');
            $price = $this->container->get('crysoft.currency_converter')->convertAmount($basePrice,$currencyFrom,$currencyTo);
            $currency = $currencyTo;

            $existingCartItem = $em->getRepository('AppBundle:CartItems')
                ->findItemInCart($product);

            if ($existingCartItem){
                $newQty=$existingCartItem[0]->getQuantity()+$quantity;
                $existingCartItem[0]->setQuantity($newQty);
                $lineTotal = ($price) * ($newQty);
                $addingTotal = $price * $quantity;

                $existingCartItem[0]->setLineTotal($lineTotal);
                $cartItem = $existingCartItem[0];
            }else {
                //Create The cart Item
                $cartItem = new CartItems();
                $cartItem->setQuantity($quantity);
                $cartItem->setUnitPrice($price);
                $cartItem->setProduct($product);
                $addingTotal = $price * $quantity;
                $lineTotal = ($price) * ($quantity);
                $cartItem->setLineTotal($lineTotal);
            }
            //Update the Cart
            if ($existingCart) {
                $existingCart[0]->setCartAmount(($existingCart[0]->getCartAmount()) + ($addingTotal));
                $existingCart[0]->setCartTotal(($existingCart[0]->getCartTotal()) + ($addingTotal));
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
        return $this->render('grower/seedlings/product-details.htm.twig', [
            'direct' => $product,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/rose/{id}/edit",name="grower_edit_rose")
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

            $this->addFlash('success', 'Rose Updated Successfully!');

            return $this->redirectToRoute('my_grower_roses');
        }

        return $this->render('grower/product/edit.html.twig', [
            'productForm' => $form->createView(),
            'product' => $product
        ]);
    }
    /**
     * @Route("/product/{id}/edit",name="grower_edit_product")
     */
    public function editProductAction(Request $request, Direct $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $roses = $em->getRepository("AppBundle:Product")
            ->findBy([
                'vendor'=>$user->getMyCompany(),
                'isActive'=>true,
                'isAuthorized'=>true
            ]);

        $form = $this->createForm(EditDirectProductForm::class, $product, ['roses' => $roses]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Rose Updated Successfully!');

            return $this->redirectToRoute('my_grower_direct_market');
        }

        return $this->render('grower/market/edit.html.twig', [
            'productForm' => $form->createView(),
            'product' => $product,
            'roses' => $roses
        ]);
    }
    /**
     * @Route("/orders/",name="grower_order_list")
     */
    public function ordersListAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $orderItems = $em->getRepository("AppBundle:OrderItems")
            ->findVendorReceivedOrders($vendor);

        return $this->render('grower/order/list.html.twig', [
            'orderItems' => $orderItems,
        ]);

    }
    /**
     * @Route("/orders/auction/my/view/{id}",name="view_auction_order")
     */
    public function viewAuctionOrderAction(Request $request,AuctionOrder $order){

        return $this->render(':grower/auction/order:order-details.htm.twig',[
            'order'=>$order,
        ]);

    }
    /**
     * @Route("/orders/auction/my/{id}",name="grower_auction_order_list")
     */
    public function auctionOrdersListAction(Request $request,AuctionProduct $auction)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository("AppBundle:AuctionOrder")
            ->findMyAuctionOrders($auction);

        return $this->render('grower/auction/order/list.html.twig', [
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
     * @Route("/orders/my/{id}/view",name="grower-order-details")
     */
    public function orderDetailsAction(Request $request, UserOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(':grower/order:order-details.htm.twig',[
            'order'=>$order,
            'orderItems'=>$order->getOrderItems()
        ]);
    }
    /**
     * @Route("/orders/received/{id}/view",name="grower-order-item-details")
     */
    public function orderItemDetailsAction(Request $request, OrderItems $orderItem){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(':grower/order:order-item-details.htm.twig',[
            'order'=>$orderItem->getOrder(),
            'orderItem'=>$orderItem
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
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllMyActiveAuctionProductsOrderByDate($grower);

        return $this->render('grower/product/mylist.html.twig', [
            'products' => $products,
        ]);

    }
    /**
     * @Route("/auction/unassigned",name="unassigned_auction_list")
     */
    public function unassignedAuctionListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->findAllMyUnassignedAuctionOrderByDate($grower);

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
        return $this->render('grower/auction/product/myFloatedlist.html.twig', [
            'products' => $result,
        ]);

    }

    /**
     * @Route("/auction/pending",name="pending_auction_list")
     */
    public function pendingAuctionListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->findAllMyPendingAuctionOrderByDate($grower);

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
        return $this->render('grower/auction/product/myPendinglist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/auction/accepted",name="accepted_auction_list")
     */
    public function acceptedAuctionListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->findAllMyAcceptedAuctionOrderByDate($grower);

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
        return $this->render('grower/auction/product/myAcceptedlist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/auction/shipped",name="shipped_auction_list")
     */
    public function shippedAuctionListAction(Request $request=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->findAllMyShippedAuctionOrderByDate($grower);

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

        return $this->render('grower/auction/product/myShippedlist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/auction/active",name="active_auction_list")
     */
    public function activeAuctionListAction(Request $request=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower= $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:AuctionProduct')
            ->findAllMyAuctionProductsOrderByDate($grower);

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

        return $this->render('grower/auction/product/myActivelist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/auction/my/products",name="my_grower_auction_list")
     */
    public function myAuctionProductListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $vendor)
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
     * @Route("/market/product/new",name="grower_direct_new")
     */
    public function newDirectProductAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $product = new Direct();
        $product->setCurrency($user->getMyCompany()->getCurrency());
        $product->setAddedBy($user);
        $product->setCreatedAt(new \DateTime());
        $product->setVendor($user->getMyCompany());

        $em = $this->getDoctrine()->getManager();
        $roses = $em->getRepository("AppBundle:Product")
            ->findBy([
                'vendor'=>$user->getMyCompany(),
                'isActive'=>true,
                'isAuthorized'=>true
            ]);

        $form = $this->createForm(DirectProductForm::class, $product, ['roses' => $roses]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em->persist($product);
            $em->flush();
            //TODO Notify the agent of the Assignment
            $this->addFlash('success', 'Product Posted to Market Successfully!');

            return $this->redirectToRoute('my_grower_direct_list');
        }

        return $this->render('grower/market/new.html.twig', [
            'productForm' => $form->createView(),
            'roses'=>$roses
        ]);
    }
    /**
     * @Route("/direct/my/products",name="my_grower_direct_list")
     */
    public function myDirectProductListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('product')
            ->andWhere('product.vendor = :isGrower')
            ->setParameter('isGrower', $user->getMyCompany())
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

        return $this->render(':grower/product:myProductlist.html.twig', [
            'products' => $result,
        ]);

    }

    /**
     * @Route("/auction/product/new",name="grower_auction_new")
     */
    public function newAuctionProductAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        $product = new Auction();
        $product->setCurrency($user->getMyCompany()->getCurrency());
        $product->setAddedBy($user);
        $product->setIsInStock(true);
        $product->setCreatedAt(new \DateTime());
        $product->setVendor($user->getMyCompany());

        $em = $this->getDoctrine()->getManager();
        $roses = $em->getRepository("AppBundle:Product")
            ->findBy([
                'vendor'=>$user->getMyCompany(),
                'isActive'=>true,
                'isAuthorized'=>true
            ]);

        $growerAgents = $em->getRepository("AppBundle:GrowerAgent")
            ->findBy([
                'grower'=>$user->getMyCompany(),
                'status' => 'Accepted'
            ]);
        $agents=[];
        foreach ($growerAgents as $growerAgent){
                $agents[]=$growerAgent->getAgent();
        }
        $options['roses'] =$roses;
        $options['agents'] = $agents;

        $form = $this->createForm(AuctionProductForm::class, $product, ['options' => $options]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $sellingAgent = $form->get('sellingAgent')->getData();
            $announceToAgents = $form->get('announceToAgents')->getData();

            if ($sellingAgent){
                $this->sendAssignmentNotification($sellingAgent);
                $product->setStatus("Pending Agent");
            }else{
                $product->setStatus("Unassigned");
            }

            $em->persist($product);
            $em->flush();

            if ($announceToAgents){
                $this->notifyAgents($agents,$grower);
            }

            return $this->redirectToRoute('my_grower_direct_market');
        }

        return $this->render('grower/auction/product/new.html.twig', [
            'productForm' => $form->createView(),
            'roses'=>$roses
        ]);
    }

    /**
     * @Route("/auction/product/{id}/edit",name="grower_auction_product_edit")
     */
    public function editAuctionProductAction(Request $request, Auction $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();

        $currentAgent = $product->getSellingAgent();


        $roses = $em->getRepository("AppBundle:Product")
            ->findBy([
                'vendor'=>$user->getMyCompany(),
                'isActive'=>true,
                'isAuthorized'=>true
            ]);

        $growerAgents = $em->getRepository("AppBundle:GrowerAgent")
            ->findBy([
                'grower'=>$user->getMyCompany(),
                'status' => 'Accepted'
            ]);
        $agents=[];
        foreach ($growerAgents as $growerAgent){
            $agents[]=$growerAgent->getAgent();
        }
        $options['roses'] =$roses;
        $options['agents'] = $agents;

        $form = $this->createForm(AuctionProductForm::class, $product, ['options' => $options]);


        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $sellingAgent = $form->get('sellingAgent')->getData();
            //var_dump($sellingAgent);die;
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            if ($sellingAgent != $currentAgent){
                $this->sendAssignmentNotification($sellingAgent);
            }


               $this->addFlash('success', 'Product in Auction Updated Successfully!');

            return $this->redirectToRoute('my_grower_auction_list');
        }

        return $this->render('grower/auction/product/edit.html.twig', [
            'productForm' => $form->createView(),
            'product'=>$product
        ]);
    }

    /**
     * @Route("/breeders/",name="breeder_list")
     */
    public function breederListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $grower = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $growerBreeders = $em->getRepository('AppBundle:GrowerBreeder')
            ->findBy([
                'listOwner' => $grower
            ]);
        $breederIds = array();

        if ($growerBreeders) {

            foreach ($growerBreeders as $growerBreeder) {
                $breederIds[] = $growerBreeder->getBreeder();
            }
        } else {
            $breederIds[] = 1;
        }

        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('company')
            ->andWhere('company.id NOT IN (:breeders)')
            ->setParameter('breeders', $breederIds)
            ->andWhere('company.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('company.companyType = :userType')
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
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

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
    public function breederProfileAction(Request $request, Company $breeder)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        $breederExists = false;

        if ($this->growerBreederExists($grower,$breeder)){
            $breederExists=true;
        }
        $products = $em->getRepository("AppBundle:Direct")
            ->findAllUserActiveSeedlingsOrderByDate($breeder);
        $nrProducts = $em->getRepository("AppBundle:Direct")
            ->findNrActiveBreederSeedlings($breeder);;

        return $this->render('grower/breeders/details.htm.twig', [
            'breeder' => $breeder,
            'products'=>$products,
            'nrProducts' => $nrProducts,
            'breederExists'=>$breederExists
        ]);

    }

    /**
     * @Route("/buyer/{id}/view",name="grower_buyer_profile")
     */
    public function buyerProfileAction(Request $request,Company $buyer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyerExists = false;

        $grower = $user->getMyCompany();

        if ($this->buyerGrowerExists($grower,$buyer)){
            $buyerExists=true;
        }

        $em = $this->getDoctrine()->getManager();


        return $this->render('grower/buyers/details.htm.twig', [
            'buyer' => $buyer,
            'buyerExists' => $buyerExists
        ]);
    }

    /**
     * @Route("/agents/",name="grower_agent_list")
     */
    public function agentListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $growerAgents = $em->getRepository('AppBundle:GrowerAgent')
            ->findBy([
                'grower' => $grower
            ]);
        $agentIds = array();

        if ($growerAgents) {

            foreach ($growerAgents as $growerAgent) {
                $agentIds[] = $growerAgent->getAgent();
            }
        } else {
            $agentIds[] = 1;
        }


        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:agents)')
            ->setParameter('agents', $agentIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.companyType = :userType')
            ->setParameter('userType', 'Agent');

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
            ->setParameter('whoIsGrower', $grower->getMyCompany());

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
    public function agentProfileAction(Company $agent)
    {
        $em = $this->getDoctrine()->getManager();

        $agentExists = false;

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        if ($this->growerAgentExists($grower,$agent)){
            $agentExists=true;
        }


        $products = $em->getRepository("AppBundle:AuctionProduct")
            ->findAllMyActiveAgentProductsOrderByDate($agent);

        $nrProducts = $em->getRepository("AppBundle:Auction")
            ->findNrMyActiveProductsAgent($agent);

        $nrBuyers = $em->getRepository("AppBundle:BuyerAgent")
            ->getNrMyAgentBuyers($agent);
        $nrGrowers = $em->getRepository("AppBundle:GrowerAgent")
            ->getNrMyAgentGrowers($agent);

        return $this->render(':grower/agents:details.htm.twig', [
            'agent' => $agent,
            'products' => $products,
            'nrProducts' => $nrProducts,
            'agentExists' => $agentExists,
            'nrGrowers' => $nrGrowers,
            'nrBuyers' => $nrBuyers
        ]);
    }

    /**
     * @Route("/agents/profile/{id}/view",name="agent_details")
     */
    public function agentProfileDetailsAction(Company $agent)
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $grower = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $buyerGrowers = $em->getRepository('AppBundle:BuyerGrower')
            ->findBy([
                'listOwner' => $grower
            ]);

        $buyerIds = array();

        if ($buyerGrowers) {

            foreach ($buyerGrowers as $buyerGrower) {
                $buyerIds[] = $buyerGrower->getBuyer();
            }
        } else {
            $buyerIds[] = 1;
        }

        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('company')
            ->andWhere('company.id NOT IN (:buyers)')
            ->setParameter('buyers', $buyerIds)
            ->andWhere('company.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('company.companyType = :userType')
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
            ->setParameter('whoIsGrower', $grower->getMyCompany());

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
     * @Route("/checkout/",name="grower-checkout_v1")
     *
     */
    public function billingAddressAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $billingAddressArray = $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($vendor);
        if ($billingAddressArray){
            $billingAddress= $billingAddressArray[0];
        }else {

            $billingAddress = new BillingAddress();
            $billingAddress->setUser($user);
            $billingAddress->setFirstName($user->getFirstName());
            $billingAddress->setLastName($user->getLastName());
            $billingAddress->setEmailAddress($user->getUserName());
            $billingAddress->setCompany($user->getMyCompany());
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

        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);

        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($vendor);

        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($vendor);

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
            $shippingAddress->setCompany($vendor);

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
     * @Route("/checkout/shipping-method",name="grower-checkout")
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

        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($vendor);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($vendor);
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);


        $myOrder = new UserOrder();
        $myOrder->setCreatedAt(new \DateTime());
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
        $myOrder->setPaymentStatus("Pending");


        $orderItems = new ArrayCollection();
        $cartItems = $cart[0]->getCartItems();


        foreach ( $cartItems as $cartItem){

            $product = $cartItem->getProduct();
            $vendor=$product->getVendor();

            $orderItem = new OrderItems();
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setUnitPrice($cartItem->getUnitPrice());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setLineTotal($cartItem->getLineTotal());
            $orderItem->setVendor($vendor);

            $this->updateQuantity($product,$cartItem->getQuantity());

            $orderItem->setOrder($myOrder);
            $orderItem->setItemStatus("Pending");
            $em->persist($orderItem);
            $em->remove($cartItem);

            $this->sendOrderReceivedNotification($vendor,$myOrder);
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
            return $this->redirectToRoute('grower-checkout-complete');

        }


        return $this->render(':partials:checkout-pay.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart[0]
        ]);
    }
    /**
     * @Route("/checkout/complete",name="grower-checkout-complete")
     */
    public function checkoutCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $savedOrder = $this->container->get('session')->get('order');

        $order = $em->getRepository("AppBundle:UserOrder")
            ->findOneBy(
                [
                    'id'=>$savedOrder->getId()
                ]
            );

        $form = $this->createForm(PaymentProofFormType::class,$order);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $em->persist($order);

            $em->flush();

            return $this->redirectToRoute('grower-payment-complete');

        }

        return $this->render(':partials:checkout-complete.htm.twig',[
            'order'=>$order,
            'transactionForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/payment/complete",name="grower-payment-complete")
     */
    public function paymentCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $order = $this->container->get('session')->get('order');
        $orderId= $order->getId();
        $userOrder = $em->getRepository('AppBundle:UserOrder')
            ->findBy([
                'id'=>$orderId
            ]);

        $userOrder[0]->setPaymentStatus("Complete");

        $em->persist($userOrder[0]);
        $em->flush();

        return $this->render(':partials:payment-complete.htm.twig',[
            'order'=>$userOrder[0]
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
            ->setParameter('whoOwnsList', $user->getMyCompany());

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

        return $this->render('grower/breeders/myRequests.htm.twig', [
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

        return $this->render('grower/buyers/myRequests.htm.twig', [
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
            ->setParameter('whoOwnsList', $user->getMyCompany());

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

        return $this->render('grower/agents/myrequests.html.twig', [
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
            ->setParameter('whoIsGrower', $user->getMyCompany());

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
            ->setParameter('whoIsGrower', $user->getMyCompany());

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
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user->getMyCompany())
            ->andWhere('user.grower = :whoIsGrower')
            ->setParameter('whoIsGrower', $user->getMyCompany());

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
            ->getNrBreederRequests($user->getMyCompany());
        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrBuyerRequests($user->getMyCompany());
        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrAgentRequests($user->getMyCompany());
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
            ->getNrBreederRequests($user->getMyCompany());

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
            ->getNrBuyerRequests($user->getMyCompany());
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
            ->getNrAgentRequests($user->getMyCompany());
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
            ->getNrMyBreederRequests($user->getMyCompany());

        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyAgentRequests($user->getMyCompany());
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
            ->getNrMyBreederRequests($user->getMyCompany());

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
            ->getNrMyAgentRequests($user->getMyCompany());
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
            ->getNrMyBuyerRequests($user->getMyCompany());

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
    /**
     * @Route("/orders/assigned/{id}/ship",name="grower-ship-order")
     */
    public function shipOrderAction(Request $request,OrderItems $orderItem){
        $em=$this->getDoctrine()->getManager();

        $order = $orderItem->getOrder();

        $nrUnshippedItems = $em->getRepository("AppBundle:OrderItems")
            ->findNrUnshippedItems($order);

        $orderItem->setItemStatus("Shipped");
        $orderItem->setLastProcessed(new \DateTime());

        if ($nrUnshippedItems==1){
            $order->setOrderState("Shipped");
            $order->setOrderStatus("Processed");
            $subject = "Order ID ".$order->getPrettyId()." Fully Shipped";
            $message ="Your Order ID ".$order->getPrettyId()." has now been fully Shipped";

        }else {
            $order->setOrderState("Partially Shipped");
            $order->setOrderStatus("Partially Processed");
            $subject = "An item in Order ID ".$order->getPrettyId()." has been Shipped";
            $message = $orderItem->getProduct()->getTitle().", Part of your Order ID ".$order->getPrettyId()." has been Shipped";
        }
        $em->persist($order);
        $em->persist($orderItem);

        $em->flush();


        $this->sendNotification($order->getUser()->getMyCompany(),$subject,$message);

        return new Response(null,204);
    }
    /**
     * @Route("/orders/payment/{id}/accept",name="grower-accept-payment")
     */
    public function acceptPaymentAction(Request $request,OrderItems $orderItem){
        $em=$this->getDoctrine()->getManager();

        $order = $orderItem->getOrder();
        $order->setPaymentStatus("Complete");
        $em->persist($order);

        $em->flush();
        //TODO Notify the User who Created the Order That their Payment has been Accepted

        return new Response(null,204);
    }

    /**
     * @Route("/inbox",name="grower-inbox")
     */
    public function inboxAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
                            ->getInboxThreads($user);

        return $this->render(':grower/messages:inbox.htm.twig',[
            'threads'=> $threads
        ]);
    }

    /**
     * @Route("/inbox/{id}/view",name="grower-thread-view")
     */
    public function threadViewAction(Request $request,Thread $thread){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $lastMessage = $thread->getLastMessage();

        if ($lastMessage->getSender()!= $user) {
            $lastMessage->setIsRead(true);
            $em->persist($lastMessage);
            $em->flush();
        }

        $message = new Message();
        $message->setSender($user);
        if ($user==$lastMessage->getSender()){
            $sender = $lastMessage->getParticipant();
        }else{
            $sender = $lastMessage->getSender();
        }
        $message->setParticipant($sender);
        $message->setIsSpam(false);
        $message->setIsRead(false);
        $message->setThread($thread);
        $message->setIsDeleted(false);
        $message->setSubject($lastMessage->getSubject());


        $form = $this->createForm(MessageReplyForm::class,$message);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){

            $dateTime = new \DateTime();

            $message=$form->getData();

            $thread->setLastMessage($message);
            $message->setSentAt($dateTime);
            $thread->setLastMessageDate($dateTime);
            $thread->setLastParticipantMessageDate($dateTime);

            $em->persist($message);
            $em->persist($thread);
            $em->flush();
            return new Response(null,200);
        }
        return $this->render(':grower/messages:thread.htm.twig',[
            'replyForm'=>$form->createView(),
            'thread'=>$thread
        ]);
    }
    /**
     * @Route("/sent",name="grower-sent")
     */
    public function sentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getSentThreads($user);

        return $this->render(':grower/messages:sent.htm.twig',[
            'threads'=> $threads
        ]);
    }
    /**
     * @Route("/notifications",name="grower-notifications")
     */
    public function notificationsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $messages = $em->getRepository("AppBundle:Notification")
            ->getNotifications($user->getMyCompany());

        return $this->render(':grower/messages:notification.htm.twig',[
            'messages'=> $messages
        ]);
    }
    /**
     * @Route("/notifications/{id}/view",name="grower-view-notification")
     */
    public function viewNotificationAction(Request $request,Notification $notification){
        $em=$this->getDoctrine()->getManager();

        $notification->setIsRead(true);

        $em->persist($notification);
        $em->flush();

        return $this->render(':grower/messages:viewNotification.htm.twig',[
            'notification'=>$notification
        ]);
    }
    public function buyerGrowerExists(Company $grower, Company $buyer){
        $em = $this->getDoctrine()->getManager();

        $buyerGrower = $em->getRepository('AppBundle:BuyerGrower')
            ->findOneBy([
                'buyer'=>$buyer,
                'grower'=>$grower,
            ]);
        if ($buyerGrower){
            return true;
        }else{
            return false;
        }
    }
    public function growerAgentExists(Company $grower, Company $agent){
        $em = $this->getDoctrine()->getManager();

        $growerAgent = $em->getRepository('AppBundle:GrowerAgent')
            ->findOneBy([
                'agent'=>$agent,
                'grower'=>$grower,
            ]);
        if ($growerAgent){
            return true;
        }else{
            return false;
        }
    }
    public function growerBreederExists(Company $grower, Company $breeder){
        $em = $this->getDoctrine()->getManager();

        $buyerGrower = $em->getRepository('AppBundle:GrowerBreeder')
            ->findOneBy([
                'breeder'=>$breeder,
                'grower'=>$grower,
            ]);
        if ($buyerGrower){
            return true;
        }else{
            return false;
        }
    }
    public function sendAssignmentNotification(Company $agent){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message ="You have been assigned a Consignment of Roses for Auction by ".$grower;
        $message.=" Check your Pending Assignments to Accept or Decline the Assignment";

        $notification = new Notification();
        $notification->setSubject("Product Assignment by ".$grower);
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);
        //$notification->setIsSpam(false);
        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($agent);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
    /**
     * @Route("/agent/ship/{id}/consigment",name="ship-consignment")
     */
    public function shipConsignmentAction(Auction $product){

        $product->setStatus("Shipped");
        $product->setShippedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $this->sendShippingNotification($product->getSellingAgent(),$product);

        return new Response(null, 204);

    }
    public function sendShippingNotification(Company $agent,Auction $product){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message =$grower." has shipped a consignment of your Product";
        $message.="<p> The Grower <b>". $grower." </b> has shipped a consignment of the Auction Product <b><a href='/agent/auction/market/".$product->getId()."/edit'>".$product->getProduct()->getTitle()."</a></b></p>";

        $notification = new Notification();
        $notification->setSubject("Product Shipped: ".$product->getProduct()->getTitle());
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($agent);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }

    private function notifyAgents(Array $agents,Company $grower)
    {

        $em = $this->getDoctrine()->getManager();

        foreach ($agents as $agent){

            $message="<p> The Grower <b>". $grower." </b> has posted a <b><i>new</i></b> consignment of Auction Products</p>";

            $notification = new Notification();
            $notification->setSubject("New Auction Products Available");
            $notification->setIsRead(false);
            $notification->setIsDeleted(false);

            $notification->setSentAt(new \DateTime());
            $notification->setParticipant($agent);
            $notification->setMessage($message);

            $em->persist($notification);

        }
        $em->flush();
    }
    private function updateQuantity(Direct $product, $quantity)
    {
        $em = $this->getDoctrine()->getManager();

        $product->setNumberOfStems($product->getNumberOfStems() - $quantity);

        $em->persist($product);
        $em->flush();

    }
    public function sendAuctionOrderReceivedNotification(Company $agent,AuctionOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message="<p> You have received a new Order #".$order->getPrettyId()." in the Auction and the order has been Automatically added to your list of Received Orders</p>";

        $notification = new Notification();
        $notification->setSubject("New Auction Order Received: ".$order->getPrettyId());
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($agent);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
    public function sendOrderReceivedNotification(Company $vendor,UserOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message="<p> You have received a new Order #".$order->getPrettyId()." and the order has been Automatically added to your list of Received Orders</p>";

        $notification = new Notification();
        $notification->setSubject("New Order Received: ".$order->getPrettyId());
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($vendor);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
    public function sendNotification(Company $receiver,$subject,$message){

        $em = $this->getDoctrine()->getManager();


        $notification = new Notification();
        $notification->setSubject($subject);
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($receiver);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
}