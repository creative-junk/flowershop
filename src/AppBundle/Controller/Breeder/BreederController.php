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
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AuctionProductForm;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\SeedlingFormType;
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
            ->findNrAllMyReceivedOrders($user);

        $nrMyGrowers = $em->getRepository('AppBundle:GrowerBreeder')
            ->getNrMyGrowers($user);

        $nrMyProducts = $em->getRepository('AppBundle:Product')
            ->findNrAllMyActiveProducts($user);



        return $this->render(':breeder:home.htm.twig',[
            'nrMyReceivedOrders'=>$nrMyReceivedOrders,
            'nrMyGrowers' =>$nrMyGrowers,
            'nrMySeedlings' => $nrMyProducts
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
        $product = new Product();
        $product->setUser($this->get('security.token_storage')->getToken()->getUser());
        $product->setIsActive(true);
        $product->setIsAuthorized(true);
        $product->setIsFeatured(false);
        $product->setIsOnSale(false);
        $product->setIsSeedling(true);

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
                'listOwner' => $breeder
            ]);
        $growerIds = array();

        if ($breederGrowers) {

            foreach ($breederGrowers as $breederGrower) {
                $growerIds[] = $breederGrower->getGrower();
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
            ->setParameter('whoIsBreeder', $user);

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
    public function breederProfileAction(Request $request,User $grower)
    {
        $em= $this->getDoctrine()->getManager();

        $products = $grower->getProducts();

        $nrproducts = $em->getRepository('AppBundle:Product')
            ->findMyActiveProducts($grower);

        $nrAuctionProducts = $em->getRepository('AppBundle:Auction')
            ->findMyActiveAuctionProducts($grower);

        return $this->render('breeder/growers/view.htm.twig',[
            'grower'=>$grower,
            'nrProducts'=>$nrproducts,
            'products'=>$products,
            'nrAuctionProducts' => $nrAuctionProducts
        ]);
    }

    /**
     * @Route("/orders/",name="breeder_order_list")
     */
    public function ordersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $orderItems = $user->getMyOrderItems();
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
            ->findAllMyOrdersOrderByDate($user);
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
            ->setParameter('whoIsBreeder', $user);

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
            ->getNrMyGrowerRequests($user);

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
            ->getNrGrowerRequests($user);

        $totalRequests += $nrBreederRequests;


        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }

}