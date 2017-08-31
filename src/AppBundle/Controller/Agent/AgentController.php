<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Agent;


use AppBundle\Entity\Auction;
use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\Cart;
use AppBundle\Entity\Company;
use AppBundle\Entity\Message;
use AppBundle\Entity\MyList;
use AppBundle\Entity\Notification;
use AppBundle\Entity\PayOptions;
use AppBundle\Entity\Product;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\Thread;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AccountFormType;
use AppBundle\Form\addToCartFormType;
use AppBundle\Form\AgentProductForm;
use AppBundle\Form\AuctionProductForm;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\BuyerCompanyForm;
use AppBundle\Form\GalleryForm;
use AppBundle\Form\MessageReplyForm;
use AppBundle\Form\PayOptionType;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\RecommendFormType;
use AppBundle\Form\ShippingAddressFormType;;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/agent")
 * @Security("is_granted('ROLE_AGENT')")
 *
 */
class AgentController extends Controller
{

    /**
     * @Route("/",name="agent_dashboard")
     */
    public function dashboardAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        if ($agent->getIsFirstLogin()&&$user->getIsMainAccount()){
            return $this->redirectToRoute("agent-update-profile",['id'=>$agent->getId()]);
        }

        $em = $this->getDoctrine()->getManager();
        $agent = $em->getRepository("AppBundle:Company")
            ->findOneBy([
                'id'=>$user->getMyCompany()->getId()
            ]);
       $nrReceivedAgentOrders = $em->getRepository('AppBundle:AuctionOrder')
            ->findNrAllMyAgentReceivedOrders($agent);
        $nrMyOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrAllMyOrdersAgent($agent);
        $nrBuyers = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyAgentBuyers($agent);
        $nrGrowers = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyAgentGrowers($agent);



        return $this->render(':agent:home.htm.twig',[
            'nrAgentReceivedOrders'=>$nrReceivedAgentOrders,
            'nrMyOrders' =>$nrMyOrders,
            'nrMyBuyers' => $nrBuyers,
            'nrMyGrowers' => $nrGrowers,
            'nrAssignedProducts' =>''
        ]);

    }

    /**
     * @Route("/users/pending",name="agent-pending-users")
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
     * @Route("/users/active",name="agent-active-users")
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
     * @Route("/update/{id}",name="agent-update-profile")
     */
    public function updateProfileAction(Request $request,Company $company){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsFirstLogin(false);

        $form = $this->createForm(BuyerCompanyForm::class,$company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $company = $form->getData();

            $em->persist($company);
            $em->flush();

            return $this->redirectToRoute("agent_dashboard");
        }

        return $this->render("companyProfile/agent.htm.twig",[
            'form'=>$form->createView()
        ]);


    }
    /**
     * @Route("/update/gallery/{id}",name="agent-update-gallery")
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

            return $this->redirectToRoute("my-agent-profile");
        }

        return $this->render("companyProfile/gallery.htm.twig",[
            'form'=>$form->createView(),
            'gallery'=>$gallery
        ]);


    }

    /**
     * @Route("/payment-options/add",name="agent-add-payment-option")
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

            return $this->redirectToRoute('my-agent-profile');

        }
        return $this->render("companyProfile/payment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/payment-options/{id}/update",name="agent-update-payment-option")
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

            return $this->redirectToRoute('my-agent-profile');

        }
        return $this->render("companyProfile/updatePayment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }


    /**
     * @Route("/account",name="my-agent-profile")
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

        return $this->render('agent/account/account.htm.twig',[
            'billingAddress'=>$billingAddress,
            'shippingAddress'=>$shippingAddress
        ]);
    }

    /**
     * @Route("/account/edit",name="agent-edit-account")
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

            return $this->redirectToRoute('my-agent-profile');
        }
        return $this->render('agent/account/edit-basic.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/add/billing",name="agent-add-billing-address")
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

            return $this->redirectToRoute('my-agent-profile');
        }

        return $this->render('agent/account/add-billing.htm.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/account/edit/billing",name="agent-edit-billing-address")
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

            return $this->redirectToRoute('my-agent-profile');
        }

        return $this->render('agent/account/edit-billing.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/account/add/shipping",name="agent-add-shipping-address")
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

            return $this->redirectToRoute('my-agent-profile');
        }

        return $this->render('agent/account/add-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/edit/shipping",name="agent-edit-shipping-address")
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

            return $this->redirectToRoute('my-agent-profile');
        }

        return $this->render('agent/account/edit-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/product/my",name="my_assigned_product_list")
     */

    public function myAssignedProductListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();
        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:AuctionProduct')
            ->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('auctionProduct.sellingAgent = :agentIs')
            ->setParameter('agentIs',$agent)
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

        return $this->render('agent/product/mylist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/product/requests/my",name="my_assigned_product_requests")
     */

    public function myAssignedProductRequestListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('auction.status = :productStatus')
            ->setParameter('productStatus', "Pending Agent")
            ->andWhere('auction.sellingAgent = :agentIs')
            ->setParameter('agentIs',$user->getMyCompany())
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

        return $this->render('agent/product/mylist.html.twig', [
            'products' => $result,
        ]);

    }
    /**
     * @Route("/product/{id}/edit",name="assigned_product_edit")
     */
    public function editAssignedProductAction(Request $request, Auction $product)
    {
        $form = $this->createForm(AgentProductForm::class, $product);
        $product->setIsActive(true);
        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();
            $product->setStatus("Agent Accepted");

            //TODO Notify the user that the Agent has accepted the Requested
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product in Auction Updated Successfully!');

            return $this->redirectToRoute('my_assigned_product_list');
        }

        return $this->render('agent/product/processProductRequest.htm.twig', [
            'productForm' => $form->createView(),
            'product'=>$product
        ]);
    }

    /**
     * @Route("/orders/",name="agent_order_list")
     */
    public function ordersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:AuctionOrder')
            ->findAllMyReceivedOrdersOrderByDate($agent);
        return $this->render('agent/order/list.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/auction/view/{id}",name="agent_view_auction_order")
     */
    public function viewAuctionOrderAction(Request $request,AuctionOrder $order)
    {
        return $this->render(':agent/order:order-details.htm.twig', ['order' => $order,]);
    }
        /**
     * @Route("/orders/my/assigned",name="my_agent_assigned_order_list")
     */
    public function myAssignedOrdersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:AuctionOrder')
            ->findAllMyOrderAssignmentRequests($user->getMyCompany());
        return $this->render('agent/order/myAssignedlist.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/my",name="my_agent_order_list")
     */
    public function myOrdersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllMyOrdersOrderByDate($user);
        return $this->render('agent/order/mylist.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/received/my",name="my_agent_received_order_list")
     */
    public function myReceivedOrdersListAction(Request $request){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $user->getMyReceivedAgencyOrders();
        return $this->render('agent/order/myReceivedList.html.twig', [
            'orders' => $orders,
        ]);

    }
    /**
     * @Route("/orders/process/{id}/update",name="agent_process_order")
     */
    public function agentProcessOrderAction(Request $request,AuctionOrder $order)
    {
        $user = $order->getWhoseOrder();
        $product=$order->getOrderItems();

        $em = $this->getDoctrine()->getManager();
        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user);

        return $this->render(':agent/order:processOrder.htm.twig',[
            'order'=>$order,
            'orderItems'=>$order->getOrderItems(),
            'billingAddress'=>$billingAddress[0],
            'shippingAddress'=>$shippingAddress[0],
            'product'=>$product[0]->getProduct()
        ]);
    }
    /**
     * @Route("/orders/{id}/update",name="agent_order_update")
     */
    public function updateOrderStatusAction(Request $request,AuctionOrder $order)
    {
        $user = $order->getWhoseOrder();

        $em = $this->getDoctrine()->getManager();
        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user);
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user);

        return $this->render(':agent/order:orderRequest.htm.twig',[
            'order'=>$order,
            'orderItems'=>$order->getOrderItems(),
            'billingAddress'=>$billingAddress[0],
            'shippingAddress'=>$shippingAddress[0],
        ]);
    }
    /**
     * @Route("/auction/",name="agent_auction_product_list")
     */
    public function auctionListAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:AuctionProduct')
            ->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
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

        return $this->render('agent/auction/list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/my",name="my_agent_auction_list")
     */
    public function myAuctionListAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.sellingAgent =:sellingAgent')
            ->setParameter('sellingAgent',$agent)
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

        return $this->render('agent/auction/my/list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/assigned",name="agent_assigned_auction_list")
     */
    public function assignedAuctionAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.sellingAgent =:sellingAgent')
            ->setParameter('sellingAgent',$agent)
            ->andWhere('auction.status = :auctionStatus')
            ->setParameter('auctionStatus','Pending Agent')
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

        return $this->render('agent/auction/my/assigned-list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/accepted",name="agent_accepted_auction_list")
     */
    public function acceptedAuctionAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.sellingAgent =:sellingAgent')
            ->setParameter('sellingAgent',$agent)
            ->andWhere('auction.status = :auctionStatus')
            ->setParameter('auctionStatus','Accepted')
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

        return $this->render('agent/auction/my/accepted-list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/shipped",name="agent_shipped_auction_list")
     */
    public function shippedAuctionAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.sellingAgent =:sellingAgent')
            ->setParameter('sellingAgent',$agent)
            ->andWhere('auction.status = :auctionStatus')
            ->setParameter('auctionStatus','Shipped')
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

        return $this->render('agent/auction/my/shipped-list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/market",name="agent_active_auction_list")
     */
    public function activeAuctionAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:AuctionProduct')
            ->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auctionProduct.sellingAgent =:sellingAgent')
            ->setParameter('sellingAgent',$agent)
            ->andWhere('auctionProduct.status = :auctionStatus')
            ->setParameter('auctionStatus','Active')
            ->andWhere('auctionProduct.isActive = :isActive')
            ->setParameter('isActive', true)
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

        return $this->render('agent/auction/my/myList.htm.twig', [
            'products' => $result,
        ]);


    }

    /**
     * @Route("/auction/market/{id}/edit",name="edit-auction-product")
     */
    public function editAuctionProduct(Request $request, AuctionProduct $product){

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(AgentProductForm::class,$product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('agent_active_auction_list');

        }
        return $this->render('agent/auction/my/edit.htm.twig',[
            'product'=>$product,
            'productForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/auction/floating",name="agent_floating_auction_list")
     */
    public function floatingAuctionAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $queryBuilder = $em->getRepository('AppBundle:Auction')
            ->createQueryBuilder('auction')
            ->innerJoin('auction.product','product')
            ->andWhere('auction.status = :auctionStatus')
            ->setParameter('auctionStatus','Unassigned')
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

        return $this->render('agent/auction/list.htm.twig', [
            'products' => $result,
        ]);


    }
    /**
     * @Route("/auction/accept/shipped/{id}/product",name="agent_accept_shipped")
     */
    public function acceptShippedAuctionAction(Request $request,Auction $auction)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $auctionProduct = new AuctionProduct();
        $auctionProduct->setWhichAuction($auction);
        $auctionProduct->setIsActive(true);
        $auctionProduct->setAvailableStock($auction->getNumberOfStems());
        $auctionProduct->setSellingAgent($agent);
        $auctionProduct->setpricePerStem($auction->getPricePerStem());

        $form = $this->createForm(AgentProductForm::class,$auctionProduct);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $auctionProduct = $form->getData();

            $auction->setStatus("Active");
            $auctionProduct->setStatus("Active");
            $auction->setAcceptedAt(new \DateTime());

            $em->persist($auction);
            $em->persist($auctionProduct);
            $em->flush();

            $this->sendAuctionProductResponseNotification($auction->getVendor(),"Rejected",$auction);


           return $this->redirectToRoute("agent_shipped_auction_list");
        }

        $em = $this->getDoctrine()->getManager();


        return $this->render('agent/auction/my/new.htm.twig', [
            'productForm' => $form->createView(),
            'auction'=>$auction
        ]);


    }
    /**
     * @Route("/auction/reject/shipped/{id}/product",name="agent_reject_shipped")
     */
    public function rejectShippedAuctionAction(Request $request,Auction $auction)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $auctionProduct = new AuctionProduct();
        $auctionProduct->setWhichAuction($auction);
        $auctionProduct->setIsActive(true);
        $auctionProduct->setAvailableStock($auction->getNumberOfStems());
        $auctionProduct->setSellingAgent($agent);
        $auctionProduct->setpricePerStem($auction->getPricePerStem());

        $form = $this->createForm(AgentProductForm::class,$auctionProduct);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $auctionProduct = $form->getData();

            $auction->setStatus("Defective");
            $auction->setAcceptedAt(new \DateTime());

            $auctionProduct->setWhichAuction($auction);

            $em->persist($auction);
            $em->persist($auctionProduct);
            $em->flush();

            return $this->redirectToRoute("agent_shipped_auction_list");
        }

        $em = $this->getDoctrine()->getManager();


        return $this->render('agent/auction/my/reject.htm.twig', [
            'productForm' => $form->createView(),
            'auction'
        ]);


    }
    /**
     * @Route("/auction/my/{id}/view",name="my_agent_auction_product_details")
     */
    public function myAuctionProductDetailsAction(Request $request, Auction $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('agent/auction/my/product-details.htm.twig', [
            'product' => $product,

        ]);
    }
    /**
     * @Route("/auction/{id}/view",name="agent_auction_product_details")
     */
    public function auctionProductDetailsAction(Request $request, AuctionProduct $product)
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

            return $this->redirectToRoute('agent_auction_product_list');
        }
        return $this->render('agent/auction/product-details.htm.twig', [
            'product' => $product,
            'form' => $form->createView()

        ]);
    }
    /**
     * @Route("/auction/{id}/buy",name="agent_auction_buy")
     */
    public function buyAction(Request $request, Product $product)
    {
        return $this->render('agent/auction/buy.htm.twig');
    }
    /**
     * @Route("/auction/{id}/recommend",name="agent_auction_recommend")
     */
    public function recommendRosesAction(Request $request, AuctionProduct $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(RecommendFormType::class);
        $em = $this->getDoctrine()->getManager();

        $agentBuyers = $em->getRepository('AppBundle:BuyerAgent')
            ->getMyAgentBuyers($user->getMyCompany());

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ids= $request->request->get('buyer');
            return $this->render('agent/auction/recommend.htm.twig',[
                'product'=>$product,
                'agentBuyers'=>$agentBuyers,
                'form'=>$form->createView(),
                'buyers'=>$ids
            ]);
        }

        return $this->render('agent/auction/recommend.htm.twig',[
            'product'=>$product,
            'agentBuyers'=>$agentBuyers,
            'form'=>$form->createView()
        ]);
    }
    /**
     * @Route("/growers",name="agent_growers_list")
     */
    public function agentGrowersAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $agentGrowers = $em->getRepository('AppBundle:GrowerAgent')
            ->findBy([
                'agent' => $agent
            ]);
        $growerIds = array();

        if ($agentGrowers) {

            foreach ($agentGrowers as $agentGrower) {
                $growerIds[] = $agentGrower->getGrower();
            }
        }else{
            $growerIds[] = 1;
        }
        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('company')
            ->andWhere('company.id NOT IN (:growers)')
            ->setParameter('growers',$growerIds)
            ->andWhere('company.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('company.companyType = :userType')
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

        return $this->render('agent/growers/list.html.twig', [
            'growers' => $result,
        ]);

    }

    /**
     * @Route("/growers/{id}/view",name="agent_grower_profile")
     */
    public function growerProfileAction(Request $request, Company $grower)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $growerExists = false;

        $agent = $user->getMyCompany();

        if ($this->agentGrowerExists($agent,$grower)){
            $growerExists = true;
        }
        $em = $this->getDoctrine()->getManager();

        $products = $em->getRepository("AppBundle:Direct")
            ->findAllMyActiveProductsOrderByDate($grower);
        $auctionProducts = $em->getRepository("AppBundle:AuctionProduct")
            ->findAllMyActiveAuctionProductsOrderByDate($grower);

        $nrproducts = $em->getRepository('AppBundle:Direct')
            ->findMyActiveProducts($grower);

        $nrAuctionProducts = $em->getRepository('AppBundle:Auction')
            ->findMyActiveAuctionProducts($grower);

        return $this->render('agent/growers/details.htm.twig', [
            'grower' => $grower,
            'products'=>$products,
            'nrProducts' => $nrproducts,
            'nrAuctionProducts' => $nrAuctionProducts,
            'auctionProducts' => $auctionProducts,
            'growerExists' => $growerExists
        ]);

    }



    /**
     * @Route("/buyers",name="agent_buyer_list")
     */
    public function buyerListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $agentBuyers = $em->getRepository('AppBundle:BuyerAgent')
            ->findBy([
                'agent' => $agent
            ]);
        $buyerIds = array();

        if ($agentBuyers) {

            foreach ($agentBuyers as $agentBuyer) {
                $buyerIds[] = $agentBuyer->getBuyer();
            }
        }else{
            $buyerIds[] = 1;
        }

        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:buyers)')
            ->setParameter('buyers',$buyerIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.companyType = :userType')
            ->setParameter('userType', 'Buyer');

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

        return $this->render('agent/buyers/list.html.twig', [
            'buyers' => $result,
        ]);
    }



    public function getTotalBuyerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrBuyerRequests($user->getMyCompany());
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyTotalBuyerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyBuyerRequests($user->getMyCompany());
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    /**
     * @Route("/buyers/requests/my",name="my_agent_buyer_requests")
     */
    public function getMyBuyerRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('AppBundle:BuyerAgent')
            ->getMyBuyerRequests($user->getMyCompany());
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );

        return $this->render('agent/buyers/myRequests.htm.twig', [
            'buyerRequests' => $result,
        ]);
    }
    /**
     * @Route("/growers/requests/my",name="my_agent_grower_requests")
     */
    public function getMyGrowerRequestsAction(Request $request){
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

        return $this->render('agent/growers/myRequests.htm.twig', [
            'growerRequests' => $result,
        ]);
    }
    /**
     * @Route("/growers/requests/",name="agent_grower_requests")
     */
    public function getGrowerRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $whoseListIds[]=array();
        $whoseListIds[]=$user->getMyCompany();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user->getMyCompany())
            ->andWhere('user.agentListOwner NOT IN (:agents)')
            ->setParameter('agents',$whoseListIds);

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


        return $this->render('agent/growers/requests.html.twig', [
            'growerRequests' => $result,
        ]);
    }
    public function getMyTotalGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrGrowerRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyGrowerRequests($user->getMyCompany());
        $totalRequests += $nrGrowerRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    /**
     * @Route("/buyers/requests/",name="agent_buyer_requests")
     */
    public function getBuyerRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $whoseListIds[]=array();
        $whoseListIds[]=$user->getMyCompany();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user->getMyCompany())
            ->andWhere('user.listOwner NOT IN (:agents)')
            ->setParameter('agents',$whoseListIds);

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


        return $this->render('agent/buyers/requests.html.twig', [
            'buyerRequests' => $result,
        ]);
    }
    /**
     * @Route("/buyers/{id}/view",name="agent_buyer_profile")
     */
    public function buyerProfileAction(Request $request,Company $buyer)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $buyerExists = false;

        if ($this->buyerAgentExists($agent,$buyer)){
            $buyerExists=true;
        }

        return $this->render('agent/buyers/details.htm.twig', [
            'buyer' => $buyer,
            'buyerExists' => $buyerExists,
        ]);
    }

    /**
     * @Route("/buyers/my/",name="my-agent-buyers")
     */
    public function myBuyersAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user->getMyCompany());

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
        return $this->render('agent/buyers/mylist.html.twig', [
            'agentBuyers' => $result,
        ]);
    }
    /**
     * @Route("/growers/my/",name="my-agent-growers")
     */
    public function myGrowersAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:GrowerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Accepted')
            ->andWhere('user.agent = :whoIsAgent')
            ->setParameter('whoIsAgent', $user->getMyCompany());

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
        return $this->render('agent/growers/mylist.html.twig', [
            'agentGrowers' => $result,
        ]);
    }
    public function getTotalGrowerRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();

        $nrAgentRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrGrowerRequests($user->getMyCompany());
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getTotalRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrGrowerRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrGrowerRequests($user->getMyCompany());

        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrBuyerRequests($user->getMyCompany());

        $totalRequests += $nrGrowerRequests;
        $totalRequests += $nrBuyerRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyTotalRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $totalRequests = 0;

        $em = $this->getDoctrine()->getManager();

        $nrBuyerRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyBuyerRequests($user->getMyCompany());

        $nrGrowerRequests = $em->getRepository('AppBundle:GrowerAgent')
            ->getNrMyGrowerRequests($user->getMyCompany());

        $totalRequests += $nrBuyerRequests;
        $totalRequests += $nrGrowerRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    public function getMyAuctionAgencyRequestsAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $totalRequests = 0;

        $em = $this->getDoctrine()->getManager();

        $nrProductRequests = $em->getRepository('AppBundle:Auction')
            ->findMyActiveProductRequests($user->getMyCompany());

        $totalRequests += $nrProductRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);
    }
    public function getMyOrderAssignmentRequestAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;

        $em = $this->getDoctrine()->getManager();

        $nrOrderRequests = $em->getRepository('AppBundle:AuctionOrder')
            ->findMyAuctionAgencyRequests($user->getMyCompany());

        $totalRequests += $nrOrderRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);
    }
    public function getMyTotalAgencyRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;

        $em = $this->getDoctrine()->getManager();

        $nrProductRequests = $em->getRepository('AppBundle:Auction')
            ->findMyActiveProductRequests($user->getMyCompany());

        $nrOrderRequests = $em->getRepository('AppBundle:AuctionOrder')
            ->findMyAuctionAgencyRequests($user->getMyCompany());

        $totalRequests += $nrProductRequests;
        $totalRequests += $nrOrderRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);
    }

    /**
     * @Route("/recommend/buyer/{id}/product/{product}",name="agent-recommend-product")
     */
    public function recommendProduct(Company $buyer,Product $product){
        $agent = $this->get('security.token_storage')->getToken()->getUser();

        $myList = new MyList();
        $myList->setProduct($product);
        $myList->setRecommendedBy($agent);
        $myList->setListType("Agent Recommendations");
        $myList->setListOwner($buyer);
        $myList->setCreatedAt(new \DateTime());
        $myList->setUpdatedAt(new \DateTime());
        $myList->setProductType("Direct");

        $em = $this->getDoctrine()->getManager();
        $em->persist($myList);
        $em->flush();

        return new Response(null, 204);
    }
    /**
     * @Route("/recommend/buyer/{id}/auction/{auction}",name="agent-recommend-auction")
     */
    public function recommendAuction(Company $buyer,AuctionProduct $auction){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $myList = new MyList();
        $myList->setAuctionProduct($auction);
        $myList->setRecommendedBy($agent);
        $myList->setListType("Agent Recommendations");
        $myList->setListOwner($buyer);
        $myList->setCreatedAt(new \DateTime());
        $myList->setUpdatedAt(new \DateTime());
        $myList->setProductType("Auction");

        $em = $this->getDoctrine()->getManager();
        $em->persist($myList);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/recommendations/my",name="my-agent-recommendations")
     */
    public function myRecommendationsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em=$this->getDoctrine()->getManager();

        $recommendations = $em->getRepository('AppBundle:MyList')
            ->getMyRecommendations($agent);
        return $this->render(':agent/myList:recommend.htm.twig',[
            'recommendations' => $recommendations
        ]);


    }
    /**
     * @Route("/wishlist/my",name="my-agent-wishlist")
     */
    public function myAuctionWishlistAction(){
        $agent = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();

        $wishlist = $em->getRepository('AppBundle:MyList')
            ->getMyWishlist($agent);
        return $this->render(':agent/myList:wishlist.htm.twig',[
            'wishlist' => $wishlist[0]
        ]);


    }
    /**
     * @Route("/wishlist/auction/my",name="my-agent-auction-wishlist")
     */
    public function myWishlistAction(){
        $agent = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();

        $wishlist = $em->getRepository('AppBundle:MyList')
            ->getMyAuctionWishlist($agent);
        return $this->render(':agent/myList:auctionWishlist.htm.twig',[
            'wishlist' => $wishlist[0]
        ]);


    }

    /**
     * @Route("/orders/assigned/{id}/accept",name="accept-order-assignment")
     */
    public function acceptOrderAssignmentAction(Request $request,AuctionOrder $order){
        $em=$this->getDoctrine()->getManager();

        $order->setOrderStatus("Agent Accepted");
        $order->setOrderState("Active");

        $em->persist($order);
        $em->flush();
        //TODO Notify the Agent Assigned the Product and the Owner of the Product at this stage

        return new Response(null,204);
    }
    /**
     * @Route("/orders/assigned/{id}/reject",name="reject-order-assignment")
     */
    public function rejectOrderAssignmentAction(Request $request,AuctionOrder $order){
        $em=$this->getDoctrine()->getManager();

        $order->setOrderStatus("Rejected");
        $order->setOrderState("Inactive");

        $em->persist($order);
        $em->flush();
        //TODO Notify the User who Created the Order That their Order has been Rejected


        return new Response(null,204);
    }

    /**
     * @Route("/orders/assigned/{id}/ship",name="agent-ship-order")
     */
    public function shipOrderAction(Request $request,AuctionOrder $order){
        $em=$this->getDoctrine()->getManager();

        $order->setOrderState("Shipped");
        $order->setOrderStatus("Processed");

        $em->persist($order);
        $em->flush();
                //TODO Notify the User who Created the Order That their Order has been Shipped

        return new Response(null,204);
    }
    /**
     * @Route("/orders/assigned/{id}/request",name="agent-request-supply")
     */
    public function requestSupplyAction(Request $request,Auction $product){
        $em=$this->getDoctrine()->getManager();
        /*
                $product->setOrderStatus("Rejected");
                $order->setOrderState("Inactive");

                $em->persist($order);
                $em->flush();
                //TODO Notify the Grower that more Product is required

        */
        return new Response(null,204);
    }

    /**
     * @Route("/inbox",name="agent-inbox")
     */
    public function inboxAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getInboxThreads($user);

        return $this->render(':agent/messages:inbox.htm.twig',[
            'threads'=> $threads
        ]);
    }

    /**
     * @Route("/inbox/{id}/view",name="agent-thread-view")
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
        return $this->render(':agent/messages:thread.htm.twig',[
            'replyForm'=>$form->createView(),
            'thread'=>$thread
        ]);
    }
    /**
     * @Route("/sent",name="agent-sent")
     */
    public function sentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getSentThreads($user);

        return $this->render(':agent/messages:sent.htm.twig',[
            'threads'=> $threads
        ]);
    }
    /**
     * @Route("/notifications",name="agent-notifications")
     */
    public function deletedAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $messages = $em->getRepository("AppBundle:Notification")
            ->getNotifications($user->getMyCompany());

        return $this->render(':agent/messages:notification.htm.twig',[
            'messages'=> $messages
        ]);
    }

    /**
     * @Route("/notifications/{id}/view",name="agent-view-notification")
     */
    public function viewNotificationAction(Request $request,Notification $notification){
        $em = $this->getDoctrine()->getManager();

        $notification->setIsRead(true);
        $em->persist($notification);
        $em->flush();

        return $this->render(':agent/messages:viewNotification.htm.twig',[
            'notification'=>$notification
        ]);
    }
    public function buyerAgentExists(Company $agent, Company $buyer){
        $em = $this->getDoctrine()->getManager();

        $buyerAgent = $em->getRepository('AppBundle:BuyerAgent')
            ->findOneBy([
                'buyer'=>$buyer,
                'agent'=>$agent,
            ]);
        if ($buyerAgent){
            return true;
        }else{
            return false;
        }
    }
    public function agentGrowerExists(Company $agent, Company $grower){
        $em = $this->getDoctrine()->getManager();

        $buyerAgent = $em->getRepository('AppBundle:GrowerAgent')
            ->findOneBy([
                'grower'=>$grower,
                'agent'=>$agent,
            ]);
        if ($buyerAgent){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @Route("/agent/accept/{id}/request",name="accept-assignment-request")
     */
    public function acceptAssignmentAction(Auction $product){

        $product->setStatus("Accepted");

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $this->sendAssignmentResponseNotification($product->getVendor(),"Accepted",$product);

        return new Response(null, 204);

    }
    /**
     * @Route("/agent/reject/{id}/request",name="reject-assignment-request")
     */
    public function rejectAssignmentAction(Auction $product){

        $product->setStatus("Rejected");

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $this->sendAssignmentResponseNotification($product->getVendor(),"Rejected",$product);

        return new Response(null, 204);

    }

    public function sendAssignmentResponseNotification(Company $grower,$response,Auction $product){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message =$agent." has ".$response." your request for Product Assignment";
        $message.="<p> The Agent <b>". $agent." </b> has ".$response." your request for Assignment of the Auction Product <b><a href='/grower/auction/product/".$product->getId()."/edit'>".$product->getProduct()->getTitle()."</a></b></p>";

        $notification = new Notification();
        $notification->setSubject("Product Assignment Status: ".$response);
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($grower);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
    public function sendAuctionResponseNotification(Company $grower,$response,Auction $product){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message =$agent." has ".$response." your shipped Consignment";
        $message.="<p> The Agent <b>". $agent." </b> has ".$response." your shipped Consignment <b>".$product->getProduct()->getTitle()."</a></b> check the consignment for details.</p>";

        $notification = new Notification();
        $notification->setSubject("Consignment Status: ".$response);
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($grower);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }

}