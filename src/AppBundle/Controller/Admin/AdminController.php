<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Airline;
use AppBundle\Entity\Category;
use AppBundle\Entity\Airport;
use AppBundle\Entity\Company;
use AppBundle\Entity\Product;
use AppBundle\Entity\ShippingRate;
use AppBundle\Entity\User;
use AppBundle\Form\ActivateSubscriptionForm;
use AppBundle\Form\AirlineForm;
use AppBundle\Form\CategoryFormType;
use AppBundle\Form\AirportForm;
use AppBundle\Form\NewAdministratorForm;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\ShippingRateForm;
use AppBundle\Form\UserFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/",name="admin_dashboard")
     */
    public function listAction(){

        $em = $this->getDoctrine()->getManager();

        $nrDirectProducts = $em->getRepository("AppBundle:Direct")
            ->findNrActiveProducts();
        $nrAuctionProducts = $em->getRepository("AppBundle:AuctionProduct")
            ->findNrActiveProducts();
        $nrOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrOrders();
        $nrAuctionOrders = $em->getRepository('AppBundle:AuctionOrder')
            ->findNrOrders();
        $nrUsers = $em->getRepository('AppBundle:Company')
            ->getNrUsers();
        $nrBuyers = $em->getRepository('AppBundle:Company')
            ->getNrBuyers();
        $nrAgents = $em->getRepository('AppBundle:Company')
            ->getNrAgents();
        $nrGrowers = $em->getRepository('AppBundle:Company')
            ->getNrGrowers();
        $nrBreeders = $em->getRepository('AppBundle:Company')
            ->getNrBreeders();

        $nrChangeOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrChangeOrders();
        $nrChangeUsers = $em->getRepository('AppBundle:Company')
            ->getNrChangeUsersThisWeek();
        $nrChangeBuyers = $em->getRepository('AppBundle:Company')
            ->getNrChangeBuyersThisWeek();
        $nrChangeAgents = $em->getRepository('AppBundle:Company')
            ->getNrChangeAgentsThisWeek();
        $nrChangeGrowers = $em->getRepository('AppBundle:Company')
            ->getNrChangeGrowersThisWeek();
        $nrChangeBreeders = $em->getRepository('AppBundle:Company')
            ->getNrChangeBreedersThisWeek();
        if ($nrOrders >0) {
            $percentChangeOrders = ($nrChangeOrders / $nrOrders) * 100;
        }else{
            $percentChangeOrders='0';
        }
        if ($nrUsers >0){
        $percentChangeUsers = ($nrChangeUsers/$nrUsers)*100;
        }else{
            $percentChangeUsers='0';
        }
        if ($nrBuyers >0){
        $percentChangeBuyers = ($nrChangeBuyers/$nrBuyers)*100;
        }else{
            $percentChangeBuyers='0';
        }
        if ($nrAgents >0){
        $percentChangeAgents = ($nrChangeAgents/$nrAgents)*100;
        }else{
            $percentChangeAgents='0';
        }
        if ($nrGrowers >0){
        $percentChangeGrowers = ($nrChangeGrowers/$nrGrowers)*100;
        }else{
            $percentChangeGrowers='0';
        }
        if ($nrBreeders >0) {
            $percentChangeBreeders = ($nrChangeBreeders / $nrBreeders) * 100;
        }else{
            $percentChangeBreeders='0';
        }
        return $this->render(':admin:dashboard.htm.twig',[
            'nrUsers'=>$nrUsers,
            'nrOrders'=>$nrOrders,
            'nrAuctionOrders'=>$nrAuctionOrders,
            'nrBuyers' =>$nrBuyers,
            'nrAgents' => $nrAgents,
            'nrGrowers' => $nrGrowers,
            'nrBreeders' => $nrBreeders,
            'nrDirectProducts'=>$nrDirectProducts,
            'nrAuctionProducts'=>$nrAuctionProducts,
            'percentChangeUsers'=>$percentChangeUsers,
            'percentChangeOrders'=>$percentChangeOrders,
            'percentChangeBuyers'=>$percentChangeBuyers,
            'percentChangeAgents'=>$percentChangeAgents,
            'percentChangeGrowers'=>$percentChangeGrowers,
            'percentChangeBreeders'=>$percentChangeBreeders,

        ]);


    }
    /**
     * @Route("/payments",name="payments")
     */
    public function paymentsAction(){
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository("AppBundle:Payment")
            ->findAll();
        return $this->render('admin/payments/all.htm.twig',[
            'payments'=>$payments
        ]);
    }
    /**
     * @Route("/payments/direct",name="direct-market-payments")
     */
    public function directMarketPaymentsAction(){
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository("AppBundle:Payment")
            ->findDirectPayments();
        return $this->render('admin/payments/direct.htm.twig',[
            'payments'=>$payments
        ]);
    }
    /**
     * @Route("/payments/auction",name="auction-market-payments")
     */
    public function auctionMarketPaymentsAction(){
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository("AppBundle:Payment")
            ->findAuctionPayments();
        return $this->render('admin/payments/auction.htm.twig',[
            'payments'=>$payments
        ]);
    }
    /**
     * @Route("/payments/paypal",name="paypal-payments")
     */
    public function paypalPaymentsAction(){
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository("AppBundle:Payment")
            ->findBy(
                [
                'gateway'=>'paypal'
                ]
            );
        return $this->render('admin/payments/all.htm.twig',[
            'payments'=>$payments
        ]);
    }
    /**
     * @Route("/payments/offline",name="offline-payments")
     */
    public function offlinePaymentsAction(){
        $em = $this->getDoctrine()->getManager();

        $payments = $em->getRepository("AppBundle:Payment")
            ->findBy(
                [
                    'gateway'=>'offline'
                ]
            );
        return $this->render('admin/payments/all.htm.twig',[
            'payments'=>$payments
        ]);
    }
    /**
     * @Route("/direct-market",name="direct-market")
     */
    public function directMarketAction(){
        $em = $this->getDoctrine()->getManager();

        $markets = $em->getRepository("AppBundle:Direct")
            ->findAll();
        return $this->render('admin/directmarket/market.htm.twig',[
            'markets'=>$markets
        ]);
    }
    /**
     * @Route("/auction-market",name="auction-market")
     */
    public function auctionMarketAction(){
        $em = $this->getDoctrine()->getManager();

        $markets = $em->getRepository("AppBundle:AuctionProduct")
            ->findAll();
        return $this->render('admin/auctionmarket/all.htm.twig',[
            'markets'=>$markets
        ]);
    }
    /**
     * @Route("/buyers",name="all-buyers")
     */
    public function allBuyersAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Buyer'
            ]);
        return $this->render(':admin/buyers:all.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/buyers/pending",name="pending-buyers")
     */
    public function pendingBuyersAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Buyer',
                'status'=>'Pending'
            ]);
        return $this->render(':admin/buyers:pending.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/growers",name="all-growers")
     */
    public function allGrowersAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Grower'
            ]);
        return $this->render(':admin/growers:all.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/growers/pending",name="pending-growers")
     */
    public function pendingGrowersAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Grower',
                'status'=>'Pending'
            ]);
        return $this->render(':admin/growers:pending.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/breeders",name="all-breeders")
     */
    public function allBreederAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Breeder'
            ]);
        return $this->render(':admin/breeders:all.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/breeders/pending",name="pending-breeders")
     */
    public function pendingBreederAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Breeder',
                'status'=>'Pending'
            ]);
        return $this->render(':admin/breeders:pending.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/agents",name="all-agents")
     */
    public function allAgentsAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Agent'
            ]);
        return $this->render(':admin/agents:all.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }
    /**
     * @Route("/agents/pending",name="pending-agents")
     */
    public function pendingAgentsAction(){
        $em = $this->getDoctrine()->getManager();
        $buyers = $em->getRepository("AppBundle:Company")
            ->findBy([
                'companyType'=>'Agent',
                'status'=>'Pending'
            ]);
        return $this->render(':admin/agents:pending.htm.twig',
            [
                'buyers'=>$buyers
            ]);
    }

    /**
     * @Route("/subscription/{id}/activate",name="activate-subscription")
     */
    public function activateSubscriptionAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ActivateSubscriptionForm::class,$company);

        $form->handleRequest($request);

        if ($form->isSubmitted()&&$form->isValid()){
            $subscriber = $form->getData();
            $companyType =$subscriber->getCompanyType();
           // var_dump($companyType);exit;
            $subscriber->setIsPaid(true);
            $subscriber->setIsActive(true);
            $subscriber->setStatus("Active");
            $company->setUpdatedBy($admin);
            $em->persist($subscriber);
            $em->flush();

            $this->sendEmail($company->getCompanyName(),"Company Account Activated",$company->getEmail(),"companyActivated.htm.twig",null);


            if ($companyType=='Buyer'){
                return $this->redirectToRoute('pending-buyers');
            }elseif ($companyType=='Grower'){
                 return $this->redirectToRoute('pending-growers');
            }elseif ($companyType=='Breeder'){
                return $this->redirectToRoute('pending-breeders');
            }elseif($companyType=='Agent'){
                return $this->redirectToRoute('pending-agents');
            }
        }
        return $this->render(':admin:activate.htm.twig',[
            'activationForm'=>$form->createView(),
            'company'=>$company
        ]);
    }
    /**
     * @Route("/categories",name="category_list")
     */
    public function categoryAction(){
        $em=$this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAllCategoriesOrderByName();

        return $this->render(':admin/category:list.html.twig',[
            'categories'=>$categories,
        ]);
    }
    /**
     * @Route("/airports",name="airports")
     */
    public function airportsAction(){
        $em=$this->getDoctrine()->getManager();
        $airports = $em->getRepository('AppBundle:Airport')
            ->findAllAirportsOrderByName();

        return $this->render(':admin/airport:list.html.twig',[
            'airports'=>$airports,
        ]);
    }
    /**
     * @Route("/airlines",name="airlines")
     */
    public function airlineAction(){
        $em=$this->getDoctrine()->getManager();
        $airlines = $em->getRepository('AppBundle:Airline')
            ->findAllAirlinesOrderByName();

        return $this->render(':admin/airline:list.html.twig',[
            'airlines'=>$airlines,
        ]);
    }
    /**
     * @Route("/shipping-rates",name="shipping-rates")
     */
    public function shippingRatesAction(){
        $em=$this->getDoctrine()->getManager();
        $rates = $em->getRepository('AppBundle:ShippingRate')
            ->findAll();

        return $this->render(':admin/rates:list.html.twig',[
            'rates'=>$rates,
        ]);
    }
    /**
     * @Route("/category/new",name="new-category")
     */
    public function newCategoryAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new Category();

        $category->setCreatedBy($user);

        $form = $this->createForm(CategoryFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success','Category Created!');

            return $this->redirectToRoute('category_list');
        }

        return $this->render(':admin/category:new.html.twig',[
            'categoryForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/category/{id}/edit",name="edit-category")
     */
    public function editCategoryAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $category = new Category();

        $category->setCreatedBy($user);

        $form = $this->createForm(CategoryFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success','Category Created!');

            return $this->redirectToRoute('category_list');
        }

        return $this->render(':admin/category:new.html.twig',[
            'categoryForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/airport/new",name="new-airport")
     */
    public function newCityAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $airport = new Airport();
        $airport->setCreatedBy($user);


        $form = $this->createForm(AirportForm::class,$airport);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $airport = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();

            $this->addFlash('success','Airport Created!');

            return $this->redirectToRoute('airports');
        }

        return $this->render(':admin/airport:new.html.twig',[
            'airportForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/airport/{id}/edit",name="edit-airport")
     */
    public function editCityAction(Request $request, Airport $airport)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(AirportForm::class,$airport);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $airport = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($airport);
            $em->flush();

            $this->addFlash('success','Airport Created!');

            return $this->redirectToRoute('airports');
        }

        return $this->render(':admin/airport:edit.html.twig',[
            'airportForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/airline/new",name="new-airline")
     */
    public function newAirlineAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $airline = new Airline();
        $airline->setCreatedBy($user);
        $form = $this->createForm(AirlineForm::class,$airline);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $airline = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($airline);
            $em->flush();

            $this->addFlash('success','Airline Created!');

            return $this->redirectToRoute('airlines');
        }

        return $this->render(':admin/airline:new.html.twig',[
            'airlineForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/airline/{id}/edit",name="edit-airline")
     */
    public function editAirlineAction(Request $request, Airline $airline){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(AirlineForm::class,$airline);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $airline = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($airline);
            $em->flush();

            $this->addFlash('success','Airline Created!');

            return $this->redirectToRoute('airlines');
        }

        return $this->render(':admin/airline:edit.html.twig',[
            'airlineForm' => $form->createView()
        ]);

    }
    /**
     * @Route("/shipping-rates/new",name="new-rate")
     */
    public function newRateAction(Request $request)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $rate = new ShippingRate();
        $rate->setCreatedBy($user);
        $rate->setUpdatedBy($user);

        $form = $this->createForm(ShippingRateForm::class,$rate);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success','City Created!');

            return $this->redirectToRoute('shipping-rates');
        }

        return $this->render(':admin/rates:new.html.twig',[
            'rateForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/shipping-rates/{id}/edit",name="edit-rate")
     */
    public function editRateAction(Request $request,ShippingRate $shippingRate)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $shippingRate->setUpdatedBy($user);

        $form = $this->createForm(ShippingRateForm::class,$shippingRate);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            $this->addFlash('success','City Created!');

            return $this->redirectToRoute('shipping-rates');
        }

        return $this->render(':admin/rates:edit.html.twig',[
            'rateForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/orders/",name="admin_order_list")
     */
    public function ordersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllUserOrdersOrderByDate();
        return $this->render('admin/order/list.html.twig',[
            'orders'=>$orders,
        ]);

    }
    /**
     * @Route("/orders/active",name="admin_active_order_list")
     */
    public function activeOrdersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllUserOrdersOrderByDate();
        return $this->render('admin/order/list.html.twig',[
            'orders'=>$orders,
        ]);

    }
    /**
     * @Route("/orders/auction",name="admin_auction_order_list")
     */
    public function auctionOrdersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:AuctionOrder')
            ->findAllUserOrdersOrderByDate();
        return $this->render('admin/order/auctionlist.html.twig',[
            'orders'=>$orders,
        ]);

    }

    /**
     * @Route("/auction/products/unassigned",name="unassigned-products")
     */
    public function unassignedProductsAction(){
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository("AppBundle:Auction")
            ->findAllUnassignedAuctionOrderByDate();
        return $this->render('admin/auctionmarket/market.htm.twig',[
            'markets'=>$products
        ]);
    }
    /**
     * @Route("/auction/products/assigned",name="assigned-products")
     */
    public function assignedProductsAction(){
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository("AppBundle:Auction")
            ->findAllAssignedAuctionOrderByDate();
        return $this->render('admin/auctionmarket/assigned.htm.twig',[
            'markets'=>$products
        ]);
    }
    /**
     * @Route("/auction/products/accepted",name="accepted-products")
     */
    public function acceptedProductsAction(){
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository("AppBundle:Auction")
            ->findAllAcceptedAuctionOrderByDate();
        return $this->render('admin/auctionmarket/accepted.htm.twig',[
            'markets'=>$products
        ]);
    }
    /**
     * @Route("/auction/products/shipped",name="shipped-products")
     */
    public function shippedProductsAction(){
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository("AppBundle:Auction")
            ->findAllShippedAuctionOrderByDate();
        return $this->render('admin/auctionmarket/shipped.htm.twig',[
            'markets'=>$products
        ]);
    }
    public function activeProductsAction(){

    }

    /**
     * @Route("/",name="admin-home")
     */
    public function adminHomeAction(){

        $em = $this->getDoctrine()->getManager();

        $nrOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrOrders();
        $nrAuctionOrders = $em->getRepository('AppBundle:AuctionOrder')
            ->findNrOrders();
        $nrUsers = $em->getRepository('AppBundle:Company')
            ->getNrUsers();
        $nrBuyers = $em->getRepository('AppBundle:Company')
            ->getNrBuyers();
        $nrAgents = $em->getRepository('AppBundle:Company')
            ->getNrAgents();
        $nrGrowers = $em->getRepository('AppBundle:Company')
            ->getNrGrowers();
        $nrBreeders = $em->getRepository('AppBundle:Company')
            ->getNrBreeders();

        $nrChangeOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrChangeOrders();
        $nrChangeUsers = $em->getRepository('AppBundle:Company')
            ->getNrChangeUsersThisWeek();
        $nrChangeBuyers = $em->getRepository('AppBundle:Company')
            ->getNrChangeBuyersThisWeek();
        $nrChangeAgents = $em->getRepository('AppBundle:Company')
            ->getNrChangeAgentsThisWeek();
        $nrChangeGrowers = $em->getRepository('AppBundle:Company')
            ->getNrChangeGrowersThisWeek();
        $nrChangeBreeders = $em->getRepository('AppBundle:Company')
            ->getNrChangeBreedersThisWeek();
        if ($nrOrders >0) {
            $percentChangeOrders = ($nrChangeOrders / $nrOrders) * 100;
        }else{
            $percentChangeOrders='0';
        }
        if ($nrUsers >0){
            $percentChangeUsers = ($nrChangeUsers/$nrUsers)*100;
        }else{
            $percentChangeUsers='0';
        }
        if ($nrBuyers >0){
            $percentChangeBuyers = ($nrChangeBuyers/$nrBuyers)*100;
        }else{
            $percentChangeBuyers='0';
        }
        if ($nrAgents >0){
            $percentChangeAgents = ($nrChangeAgents/$nrAgents)*100;
        }else{
            $percentChangeAgents='0';
        }
        if ($nrGrowers >0){
            $percentChangeGrowers = ($nrChangeGrowers/$nrGrowers)*100;
        }else{
            $percentChangeGrowers='0';
        }
        if ($nrBreeders >0) {
            $percentChangeBreeders = ($nrChangeBreeders / $nrBreeders) * 100;
        }else{
            $percentChangeBreeders='0';
        }
        return $this->render(':admin:dashboard.htm.twig',[
            'nrUsers'=>$nrUsers,
            'nrOrders'=>$nrOrders,
            'nrAuctionOrders'=>$nrAuctionOrders,
            'nrBuyers' =>$nrBuyers,
            'nrAgents' => $nrAgents,
            'nrGrowers' => $nrGrowers,
            'nrBreeders' => $nrBreeders,
            'percentChangeUsers'=>$percentChangeUsers,
            'percentChangeOrders'=>$percentChangeOrders,
            'percentChangeBuyers'=>$percentChangeBuyers,
            'percentChangeAgents'=>$percentChangeAgents,
            'percentChangeGrowers'=>$percentChangeGrowers,
            'percentChangeBreeders'=>$percentChangeBreeders,

        ]);


    }
    /**
     * @Route("/users",name="user_list")
     */
    public function listUserAction(){


        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('admin/user/list.htm.twig',[
            'users'=>$users,
        ]);
        //dump($products);die;
        //return new Response('Product Saved');
    }
    /**
     * @Route("/user/new",name="new_user")
     */
    public function newUserAction(Request $request)
    {

        $form = $this->createForm(UserFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','User Created, Yaay!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/new.html.twig',[
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/user/{id}/edit",name="user_edit")
     */
    public function editAction(Request $request,User $user)
    {
        $form = $this->createForm(UserFormType::class,$user);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Updated, Yaay!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit.html.twig',[
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile",name="my_profile")
     */
    public function profileAction(){

    }
    /**
     * @Route("/settings",name="app_settings")
     */
    public function settingsAction(){

    }

    /**
     * @Route("/companies/pending",name="pending-company-accounts")
     */
    public function pendingCompanyAction(){
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository("AppBundle:Company")
            ->findAllPendingCompanyAccounts();

        return $this->render('admin/company/pending-accounts.htm.twig',[
            'companies'=>$companies
        ]);
    }
    /**
     * @Route("/companies/approved",name="approved-company-accounts")
     */
    public function approvedCompaniesAction(){
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository("AppBundle:Company")
            ->findAllActiveCompanyAccounts();

        return $this->render('admin/company/approved-accounts.htm.twig',[
            'companies'=>$companies
        ]);
    }
    /**
     * @Route("/company/account/{id}/approve",name="approve-company-account")
     */
    public function approveCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(true);

        $company->setApprovedBy($admin);

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Approved",$company->getEmail(),"companyApproved.htm.twig",$company->getId());

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/activate",name="activate-company-account")
     */
    public function activateCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(true);

        $company->setUpdatedBy($admin);
        $company->setCreatedAt(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Activated",$company->getEmail(),"companyActivated.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/suspend",name="suspend-company-account")
     */
    public function suspendCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(false);

        $company->setUpdatedBy($admin);
        $company->setCreatedAt(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Suspended",$company->getEmail(),"companySuspended.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/reject",name="reject-company-account")
     */
    public function rejectCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(false);
        $company->setIsPaid(false);

        $company->setApprovedBy($admin);
        $company->setApprovedOn(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Denied",$company->getEmail(),"companyDenied.htm.twig",null);

        return new Response(null,204);
    }
    /**
     * @Route("/users/",name="user-accounts")
     */
    public function userAccountsAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllUsers();

        return $this->render('admin/user/users.htm.twig',[
            'users'=>$users
        ]);
    }

    /**
     * @Route("/user/account/{id}/activate",name="activate-user-account")
     */
    public function activateUserAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(true);

        $user->setUpdatedBy($admin);
        $user->setUpdatedAt(new \DateTime());

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"User Account Activated",$user->getEmail(),"userActivated.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/user/account/{id}/suspend",name="suspend-user-account")
     */
    public function suspendUserAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"User Account Suspended",$user->getEmail(),"userSuspended.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/users/admin/pending",name="pending-admin-accounts")
     */
    public function pendingAdminAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllPendingAdminUsers();

        return $this->render('admin/pending-admin-accounts.htm.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/users/administrators",name="admin-accounts")
     */
    public function adminAccountsAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllAdministratorUsers();

        return $this->render('admin/admin-users.htm.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/user/account/{id}/approve",name="approve-admin-account")
     */
    public function approveAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(true);
        $user->setIsPasswordCreated(true);
        $user->setApprovedBy($admin);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Administrator Account Approved",$user->getEmail(),"accountApproved.htm.twig",null);

        return new Response(null,204);
    }
    /**
     * @Route("/administrator/new",name="new-administrator")
     */
    public function newAdministratorAction(Request $request){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $accountToken = base64_encode(random_bytes(10));

        $user = new User();
        $user->setIsActive(true);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPlainPassword(base64_encode(random_bytes(10)));
        $user->setAccountCreatedBy($admin);
        $user->setPasswordResetToken($accountToken);

        $form = $this->createForm(NewAdministratorForm::class,$user);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $user=$form->getData();

            $em->persist($user);
            $em->flush();

            $this->sendEmail($user->getFirstName(),"Iflora Portal Administrator Account",$user->getEmail(),"accountCreated.htm.twig",$accountToken);

            return $this->redirectToRoute('admin-accounts');
        }

        return $this->render(':admin:new.htm.twig',[
            'adminForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/user/account/{id}/deactivate",name="deactivate-account")
     */
    public function deactivateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        return new Response(null,204);
    }
    /**
     * @Route("/user/account/{id}/activate",name="activate-account")
     */
    public function activateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setPasswordResetToken($resetToken);
        $user->setIsResetTokenValid(true);
        $user->setIsActive(true);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Password Reset",$user->getEmail(),"passwordReset.htm.twig",$resetToken);

        return new Response(null,204);
    }

    /**
     * @Route("/user/account/{id}/reset",name="request-password-reset")
     */
    public function requestPasswordResetAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setPasswordResetToken($resetToken);
        $user->setIsResetTokenValid(true);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Password Reset",$user->getEmail(),"passwordReset.htm.twig",$resetToken);

        return new Response(null,204);
    }
    protected function sendEmail($firstName,$subject,$emailAddress,$twigTemplate,$code){
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('iflora@iflora.biz','Iflora Team')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                    'Emails/'.$twigTemplate,
                    array(
                        'name' => $firstName,
                        'code' => $code
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }

    /**
     * @Route("/member/update",name="update-member")
     */
    public function updateRoleFunction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $memberId = $request->request->get('pk');
        $roleValue = $request->request->get('value');

        switch ($roleValue) {
            case 1:
                $role = ["ROLE_ADMIN"];
                break;
            case 2:
                $role = ["ROLE_FREIGHTERS"];
                break;
            default:
                $role = ["ROLE_ADMIN"];
                break;
        }

        $member = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'id'=>$memberId
            ]);

        if ($member){
            $member->setRoles($role);
            $em->persist($member);
            $em->flush();
            return new Response(null,200);
        }else{
            return new Response(null,500);
        }
    }

}