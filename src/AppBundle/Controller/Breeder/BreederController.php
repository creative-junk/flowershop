<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Breeder;


use AppBundle\Entity\Auction;
use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use AppBundle\Entity\Message;
use AppBundle\Entity\Notification;
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\Product;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\Thread;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AccountFormType;
use AppBundle\Form\AuctionProductForm;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\DirectProductForm;
use AppBundle\Form\EditDirectProductForm;
use AppBundle\Form\MessageReplyForm;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\SeedlingFormType;
use AppBundle\Form\ShippingAddressFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/breeder")
 * @Security("is_granted('ROLE_BREEDER')")
 *
 */
class BreederController extends Controller
{

    /**
     * @Route("/",name="breeder_dashboard")
     */
    public function dashboardAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $nrMyReceivedOrders = $em->getRepository('AppBundle:OrderItems')
            ->findNrAllMyReceivedOrders($user->getMyCompany());

        $nrMyGrowers = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrMyGrowers($user->getMyCompany());

        $nrMyProducts = $em->getRepository('AppBundle:Product')
            ->findNrAllMyActiveProducts($user->getMyCompany());



        return $this->render(':breeder:home.htm.twig',[
            'nrMyReceivedOrders'=>$nrMyReceivedOrders,
            'nrMyGrowers' =>$nrMyGrowers,
            'nrMySeedlings' => $nrMyProducts
        ]);

    }
    /**
     * @Route("/account",name="my-breeder-profile")
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

        return $this->render('breeder/account/account.htm.twig',[
            'billingAddress'=>$billingAddress,
            'shippingAddress'=>$shippingAddress
        ]);
    }

    /**
     * @Route("/account/edit",name="breeder-edit-account")
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

            return $this->redirectToRoute('my-breeder-profile');
        }
        return $this->render('breeder/account/edit-basic.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/add/billing",name="breeder-add-billing-address")
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

            return $this->redirectToRoute('my-breeder-profile');
        }

        return $this->render('breeder/account/add-billing.htm.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/account/edit/billing",name="breeder-edit-billing-address")
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

            return $this->redirectToRoute('my-breeder-profile');
        }

        return $this->render('breeder/account/edit-billing.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/account/add/shipping",name="breeder-add-shipping-address")
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

            return $this->redirectToRoute('my-breeder-profile');
        }

        return $this->render('breeder/account/add-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/edit/shipping",name="breeder-edit-shipping-address")
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

            return $this->redirectToRoute('my-breeder-profile');
        }

        return $this->render('breeder/account/edit-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/seedling/new",name="breeder_product_new")
     */
    public function newRoseAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $product = new Product();
        $product->setUser($user);
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setVendor($user->getMyCompany());
        $product->setIsSeedling(true);
        $form = $this->createForm(ProductFormType::class, $product);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product Created Successfully!');

            return $this->redirectToRoute('my_breeder_seedling_list');
        }

        return $this->render('breeder/product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/market/product/new",name="breeder_direct_new")
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

            return $this->redirectToRoute('my_breeder_direct_list');
        }

        return $this->render('breeder/market/new.html.twig', [
            'productForm' => $form->createView(),
            'roses'=>$roses
        ]);
    }
    /**
     * @Route("/direct/my/products",name="my_breeder_direct_list")
     */
    public function myDirectMarketListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('product')
            //->andWhere('product.isActive = :isActive')
            //->setParameter('isActive', true)
            ->andWhere('product.vendor = :isBreeder')
            ->setParameter('isBreeder', $vendor)
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
        return $this->render('breeder/market/myProductlist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/product/{id}/edit",name="breeder_edit_product")
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

            return $this->redirectToRoute('my_breeder_direct_list');
        }

        return $this->render('breeder/market/edit.html.twig', [
            'productForm' => $form->createView(),
            'product' => $product,
            'roses' => $roses
        ]);
    }
    /**
     * @Route("/seedling/{id}/edit",name="breeder_edit_rose")
     */
    public function editRoseAction(Request $request, Product $product)
    {
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setIsSeedling(true);

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

        return $this->render('breeder/seedlings/edit.html.twig', [
            'productForm' => $form->createView(),
            'product' => $product
        ]);
    }

    /**
     * @Route("/seedling/my",name="my_breeder_seedling_list")
     */
    public function myProductListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

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
        return $this->render('breeder/seedlings/mylist.html.twig',[
            'products' => $result,
        ]);

    }
    /**
     * @Route("/seedling/new",name="breeder_seedlings_new")
     */
    public function newAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $vendor = $user->getMyCompany();

        $product = new Product();
        $product->setUser($user);
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setIsSeedling(true);
        $product->setVendor($vendor);

        $form = $this->createForm(SeedlingFormType::class, $product);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Created Successfully!');

            return $this->redirectToRoute('my_breeder_seedling_list');
        }

        return $this->render('breeder/seedlings/new.html.twig',[
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/seedling/{id}/edit",name="breeder_seedling_edit")
     */
    public function editAction(Request $request,Product $product)
    {
        $form = $this->createForm(SeedlingFormType::class,$product);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Updated Successfully!');

            return $this->redirectToRoute('my_breeder_seedling_list');
        }

        return $this->render('breeder/seedlings/edit.html.twig',[
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/growers",name="breeder_growers_list")
     */
    public function buyerGrowersAction(Request $request = null)
    {
        $breeder = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $breederGrowers = $em->getRepository('AppBundle:GrowerBreeder')
            ->findBy([
                'listOwner' => $breeder->getMyCompany()
            ]);
        $growerIds = array();

        if ($breederGrowers) {

            foreach ($breederGrowers as $breederGrower) {
                $growerIds[] = $breederGrower->getGrower();
            }
        }else{
            $growerIds[] = 1;
        }

        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:growers)')
            ->setParameter('growers',$growerIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.companyType = :userType')
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

        return $this->render('breeder/growers/list.html.twig', [
            'growers' => $result,
        ]);

    }


    /**
     * @Route("/growers/my",name="my_breeder_growers")
     */
    public function myGrowersAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerBreeder')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.breeder = :whoIsBreeder')
            ->setParameter('whoIsBreeder', $user->getMyCompany());

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
        return $this->render('breeder/growers/mylist.html.twig', [
            'breederGrowers' => $result,
        ]);
    }
    /**
     * @Route("/growers/{id}/view",name="grower_profile")
     */
    public function breederProfileAction(Request $request,Company $grower)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $exists = false;

        $buyer = $user->getMyCompany();
        if ($this->breederGrowerExists($buyer,$grower)){
            $exists=true;
        }
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("AppBundle:Direct")
            ->findAllMyActiveProductsOrderByDate($grower);
        $auctionProducts = $em->getRepository("AppBundle:Auction")
            ->findAllMyActiveAuctionProductsOrderByDate($grower);

        $nrproducts = $em->getRepository('AppBundle:Direct')
            ->findMyActiveProducts($grower);

        $nrAuctionProducts = $em->getRepository('AppBundle:Auction')
            ->findMyActiveAuctionProducts($grower);

        return $this->render('breeder/growers/details.htm.twig', [
            'grower' => $grower,
            'products'=>$products,
            'nrProducts' => $nrproducts,
            'nrAuctionProducts' => $nrAuctionProducts,
            'auctionProducts' => $auctionProducts,
            'growerExists' => $exists
        ]);
    }

    /**
     * @Route("/orders/",name="breeder_order_list")
     */
    public function ordersListAction(){


        $em = $this->getDoctrine()->getManager();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $orderItems = $em->getRepository("AppBundle:OrderItems")
            ->findVendorReceivedOrders($vendor);

        return $this->render('breeder/order/list.html.twig', [
            'orderItems' => $orderItems,
        ]);

    }
    /**
     * @Route("/orders/my",name="my_seedlings_order_list")
     */
    public function myOrdersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyOrdersOrderByDate($user->getMyCompany());
        return $this->render('breeder/order/mylist.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/received/{id}/view",name="breeder-order-item-details")
     */
    public function orderItemDetailsAction(Request $request, OrderItems $orderItem){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(':breeder/order:order-item-details.htm.twig',[
            'order'=>$orderItem->getOrder(),
            'orderItem'=>$orderItem
        ]);
    }
    /**
     * @Route("/orders/{id}/update",name="breeder_order_update")
     */
    public function updateOrderStatusAction(Request $request,UserOrder $order)
    {
        $form = $this->createForm(ProductFormType::class,$order);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Order Status Updated Successfully!');

            return $this->redirectToRoute('grower_order_list');
        }

        return $this->render('grower/order/update.html.twig',[
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/orders/assigned/{id}/ship",name="breeder-ship-order")
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

        }else {
            $order->setOrderState("Partially Shipped");
            $order->setOrderStatus("Partially Processed");
        }
        $em->persist($order);
        $em->persist($orderItem);

        $em->flush();
        //TODO Notify the User who Created the Order That their Order has been Shipped

        return new Response(null,204);
    }
    /**
     * @Route("/orders/payment/{id}/accept",name="breeder-accept-payment")
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
     * @Route("/requests/growers",name="breeder_grower_requests")
     */
    public function getBreederRequestsAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerBreeder')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.breeder = :whoIsBreeder')
            ->setParameter('whoIsBreeder', $user->getMyCompany())
            ->andWhere('user.listOwner <> :whoOwnsList')
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

        return $this->render('breeder/growers/requests.html.twig', [
            'breederRequests' => $result,
        ]);
    }
    /**
     * @Route("/requests/my/growers",name="my_grower_requests")
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

        return $this->render('breeder/growers/myRequests.htm.twig', [
            'breederRequests' => $result,
        ]);
    }

    public function getMyTotalGrowerRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrMyGrowerRequests($user->getMyCompany());

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getTotalGrowerRequestsAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrGrowerRequests($user->getMyCompany());

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    /**
     * @Route("/inbox",name="breeder-inbox")
     */
    public function inboxAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getInboxThreads($user);

        return $this->render(':breeder/messages:inbox.htm.twig',[
            'threads'=> $threads
        ]);
    }

    /**
     * @Route("/inbox/{id}/view",name="breeder-thread-view")
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
        return $this->render(':breeder/messages:thread.htm.twig',[
            'replyForm'=>$form->createView(),
            'thread'=>$thread
        ]);
    }
    /**
     * @Route("/sent",name="breeder-sent")
     */
    public function sentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getSentThreads($user);

        return $this->render(':breeder/messages:sent.htm.twig',[
            'threads'=> $threads
        ]);
    }
    /**
     * @Route("/notifications",name="breeder-notifications")
     */
    public function deletedAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $messages = $em->getRepository("AppBundle:Notification")
            ->getNotifications($user->getMyCompany());

        return $this->render(':breeder/messages:notification.htm.twig',[
            'messages'=> $messages
        ]);
    }

    /**
     * @Route("/notifications/{id}/view",name="view-notification")
     */
    public function viewNotificationAction(Request $request,Notification $notification){
        return $this->render(':breeder/messages:viewNotification.htm.twig',[
            'notification'=>$notification
        ]);
    }
    public function breederGrowerExists(Company $breeder, Company $grower){
        $em = $this->getDoctrine()->getManager();

        $breederGrower = $em->getRepository('AppBundle:GrowerBreeder')
            ->findOneBy([
                'breeder'=>$breeder,
                'grower'=>$grower,
            ]);
        if ($breederGrower){
            return true;
        }else{
            return false;
        }
    }
}