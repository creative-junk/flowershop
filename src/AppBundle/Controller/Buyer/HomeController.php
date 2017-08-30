<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/27/2017
 ********************************************************************************/

namespace AppBundle\Controller\Buyer;


use AppBundle\Entity\Auction;
use AppBundle\Entity\AuctionCart;
use AppBundle\Entity\AuctionOrder;
use AppBundle\Entity\AuctionOrderItems;
use AppBundle\Entity\AuctionProduct;
use AppBundle\Entity\BillingAddress;
use AppBundle\Entity\Cart;
use AppBundle\Entity\CartItems;
use AppBundle\Entity\Company;
use AppBundle\Entity\Direct;
use AppBundle\Entity\GrowersList;
use AppBundle\Entity\Message;
use AppBundle\Entity\Notification;
use AppBundle\Entity\OrderItems;
use AppBundle\Entity\PayOptions;
use AppBundle\Entity\Product;
use AppBundle\Entity\ShippingAddress;
use AppBundle\Entity\Thread;
use AppBundle\Entity\ThreadMetadata;
use AppBundle\Entity\User;
use AppBundle\Entity\UserOrder;
use AppBundle\Form\AccountFormType;
use AppBundle\Form\AddGrowerForm;
use AppBundle\Form\addToCartFormType;
use AppBundle\Form\AuctionBuyForm;
use AppBundle\Form\AuctionPaymentProofForm;
use AppBundle\Form\BillingAddressFormType;
use AppBundle\Form\BuyerAgentFormType;
use AppBundle\Form\BuyerCompanyForm;
use AppBundle\Form\CheckoutForm;
use AppBundle\Form\CompanyRegistrationForm;
use AppBundle\Form\FilterFormType;
use AppBundle\Form\GalleryForm;
use AppBundle\Form\MessageFormType;
use AppBundle\Form\MessageReplyForm;
use AppBundle\Form\PaymentMethodFormType;
use AppBundle\Form\PaymentProofFormType;
use AppBundle\Form\PayOptionType;
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $buyer = $user->getMyCompany();

        if ($buyer->getIsFirstLogin()&&$user->getIsMainAccount()){
            return $this->redirectToRoute("update-profile",['id'=>$buyer->getId()]);
        }

        return $this->render('home/home.htm.twig');
    }

    /**
     * @Route("/update/{id}",name="update-profile")
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

            return $this->redirectToRoute("home");
        }

        return $this->render("companyProfile/buyer.htm.twig",[
            'form'=>$form->createView()
        ]);


    }
    /**
     * @Route("/update/gallery/{id}",name="update-gallery")
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

            return $this->redirectToRoute("my-buyer-profile");
        }

        return $this->render("companyProfile/gallery.htm.twig",[
            'form'=>$form->createView(),
            'gallery'=>$gallery
        ]);


    }

    /**
     * @Route("/payment-options/add",name="add-payment-option")
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

            return $this->redirectToRoute('my-buyer-profile');

        }
        return $this->render("companyProfile/payment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/payment-options/{id}/update",name="update-payment-option")
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

            return $this->redirectToRoute('my-buyer-profile');

        }
        return $this->render("companyProfile/updatePayment.htm.twig",[
            'form'=>$form->createView(),
        ]);
    }
    /**
     * @Route("/account",name="my-buyer-profile")
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

        return $this->render('home/account/account.htm.twig',[
            'billingAddress'=>$billingAddress,
            'shippingAddress'=>$shippingAddress
        ]);
    }

    /**
     * @Route("/account/edit",name="buyer-edit-account")
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

            return $this->redirectToRoute('my-buyer-profile');
        }
        return $this->render('home/account/edit-basic.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/add/billing",name="buyer-add-billing-address")
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

            return $this->redirectToRoute('my-buyer-profile');
        }

        return $this->render('home/account/add-billing.htm.twig',[
            'form'=>$form->createView()
        ]);


    }

    /**
     * @Route("/account/edit/billing",name="buyer-edit-billing-address")
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

            return $this->redirectToRoute('my-buyer-profile');
        }

        return $this->render('home/account/edit-billing.htm.twig',[
            'form'=>$form->createView()
        ]);

    }

    /**
     * @Route("/account/add/shipping",name="buyer-add-shipping-address")
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

            return $this->redirectToRoute('my-buyer-profile');
        }

        return $this->render('home/account/add-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/account/edit/shipping",name="buyer-edit-shipping-address")
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

            return $this->redirectToRoute('my-buyer-profile');
        }

        return $this->render('home/account/edit-shipping.htm.twig',[
            'form'=>$form->createView()
        ]);

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

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $vendor = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $buyerGrowers = $em->getRepository("AppBundle:BuyerGrower")
            ->findBy([
                'buyer'=>$vendor,
                'status'=>"Accepted"
            ]);
        $growers=array();
        foreach ($buyerGrowers as $buyerGrower) {
            $growers[]=$buyerGrower->getGrower();
        }

        $form = $this->createForm(BuyerAgentFormType::class,null, ['growers' => $growers]);


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
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', false)
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
     * @Route("/direct-market/",name="buyer-market")
     */
    public function buyerShopListAction(Request $request = null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $cart = new Cart();

        $cart->setOwnedBy($user);

        //$form = $this->createForm(addToCartFormType::class, $cart);

        $form = $this->createForm(FilterFormType::class);

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Direct')
            ->createQueryBuilder('direct')
            ->innerJoin('direct.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('product.isSeedling = :isSeedling')
            ->setParameter('isSeedling', false)
            ->orderBy('direct.createdAt', 'DESC');
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


        return $this->render('home/list.htm.twig', [
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
    public function showAction(Request $request, Direct $product)
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
            //$price = $this->container->get('lexik_currency.converter')->convert($basePrice, $user->getMyCompany()->getCurrency(), false, $product->getVendor()->getCurrency());

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
            'direct' => $product,
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

        //Get All Growers in this Buyers List
        $buyerGrowers = $em->getRepository('AppBundle:BuyerGrower')
            ->findBy([
                'listOwner' => $buyer->getMyCompany()
            ]);
        $growerIds = array();

        if ($buyerGrowers) {

            foreach ($buyerGrowers as $buyerGrower) {
                $growerIds[] = $buyerGrower->getGrower();
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
            ->andWhere('buyer_grower.buyer = :whoOwns')
            ->setParameter('whoOwns',$buyer->getMyCompany())
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
    public function viewGrowerAction(Request $request, Company $grower)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $exists = false;

        $buyer = $user->getMyCompany();
        if ($this->buyerGrowerExists($buyer,$grower)){
            $exists=true;
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

        return $this->render('home/growers/details.htm.twig', [
            'grower' => $grower,
            'products'=>$products,
            'nrProducts' => $nrproducts,
            'nrAuctionProducts' => $nrAuctionProducts,
            'auctionProducts' => $auctionProducts,
            'growerExists' => $exists
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
                'buyer' => $buyer->getMyCompany()
            ]);
        $agentIds = array();

        if ($buyerAgents) {

            foreach ($buyerAgents as $buyerAgent) {
                $agentIds[] = $buyerAgent->getAgent();
            }
        }else{
            $agentIds[] = 1;
        }
        $queryBuilder = $em->getRepository('AppBundle:Company')
            ->createQueryBuilder('user')
            ->andWhere('user.id NOT IN (:agents)')
            ->setParameter('agents',$agentIds)
            ->andWhere('user.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('user.companyType = :companyType')
            ->setParameter('companyType', 'Agent');

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
            ->andWhere('buyer_agent.buyer = :whoOwns')
            ->setParameter('whoOwns',$buyer->getMyCompany())
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
    public function agentProfileActionAction(Request $request, Company $agent)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $buyer = $user->getMyCompany();
        $agentExists = false;

        if ($this->buyerAgentExists($buyer,$agent)){
            $agentExists = true;
        }


        $em = $this->getDoctrine()->getManager();

        $nrBuyers = $em->getRepository("AppBundle:BuyerAgent")
            ->getNrMyAgentBuyers($agent);
        $nrGrowers = $em->getRepository("AppBundle:GrowerAgent")
            ->getNrMyAgentGrowers($agent);

        $products = $em->getRepository("AppBundle:AuctionProduct")
            ->findAllMyActiveAgentProductsOrderByDate($agent);

        $nrProducts = $em->getRepository("AppBundle:AuctionProduct")
            ->findNrMyActiveProductsAgent($agent);

        return $this->render(':home/agents:details.htm.twig', [
            'agent' => $agent,
            'products' => $products,
            'nrProducts' => $nrProducts,
            'agentExists'=>$agentExists,
            'nrBuyers' => $nrBuyers,
            'nrGrowers' => $nrGrowers
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
                                ->getMyAgentRequests($user->getMyCompany());
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

        $whoseListIds[]=array();
        $whoseListIds[]=$user->getMyCompany();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerGrower')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user->getMyCompany())
            ->andWhere('user.listOwner NOT IN (:buyers)')
            ->setParameter('buyers',$whoseListIds);

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


        return $this->render('home/growers/requests.html.twig', [
            'growerRequests' => $result,
        ]);
    }
    /**
     * @Route("/requests/agents",name="buyer_agent_requests")
     */
    public function getAgentRequestsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:BuyerAgent')
            ->createQueryBuilder('user')
            ->andWhere('user.status = :isAccepted')
            ->setParameter('isAccepted', 'Requested')
            ->andWhere('user.listOwner <> :whoOwnsList')
            ->setParameter('whoOwnsList', $user->getMyCompany())
            ->andWhere('user.buyer = :whoIsBuyer')
            ->setParameter('whoIsBuyer', $user->getMyCompany());

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
     * @Route("/checkout/v1",name="shipping_method")
     */
    public function checkoutAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $billingAddressArray = $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user->getMyCompany());
        if ($billingAddressArray){
            $billingAddress= $billingAddressArray[0];
        }else {

            $billingAddress = new BillingAddress();
            $billingAddress->setUser($user);
            $billingAddress->setCompany($user->getMyCompany());
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



        $billingAddress =  $em->getRepository('AppBundle:BillingAddress')
                ->findMyBillingAddress($user->getMyCompany());

        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user->getMyCompany());

        if ($shippingAddress){

            $shippingAddress=$shippingAddress[0];

        }else if (!$shippingAddress && $billingAddress){
            $shippingAddress = new ShippingAddress();
            $shippingAddress->setUser($user);
            $shippingAddress->setCompany($user->getMyCompany());
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
     * @Route("/checkout",name="buyer_checkout")
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
            ->findMyBillingAddress($user->getMyCompany());
        $shippingAddress = $em->getRepository('AppBundle:ShippingAddress')
            ->findMyShippingAddress($user->getMyCompany());
        $cart = $em->getRepository('AppBundle:Cart')
            ->findMyCart($user);


        $myOrder = new UserOrder();
        $myOrder->setCreatedAt(new \DateTime());
        //$myOrder->setBillingAddress($billingAddress[0]);
        //$myOrder->setShippingAddress($shippingAddress[0]);

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
            $product = $cartItem->getProduct();
            $vendor=$product->getVendor();

            $orderItem = new OrderItems();

            $orderItem->setProduct($product);
            $orderItem->setUnitPrice($cartItem->getUnitPrice());
            $orderItem->setQuantity($cartItem->getQuantity());
            $orderItem->setLineTotal($cartItem->getLineTotal());
            $orderItem->setVendor($vendor);
            $orderItem->setOrder($myOrder);
            $this->updateQuantity($product,$cartItem->getQuantity());
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

            return $this->redirectToRoute('buyer-payment-complete');

        }

        return $this->render(':partials/iflora/user:checkout-complete.htm.twig',[
            'order'=>$order,
            'transactionForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/payment/complete",name="buyer-payment-complete")
     */
    public function paymentCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $order = $this->container->get('session')->get('order');

        $orderId= $order->getId();

        $userOrder = $em->getRepository('AppBundle:UserOrder')
            ->findOneBy([
                'id'=>$orderId
            ]);

        $userOrder->setPaymentStatus("Complete");

        $em->persist($userOrder);
        $em->flush();



        return $this->render('partials/iflora/user/payment-complete.htm.twig',[
            'order'=>$userOrder
        ]);
    }

    /**
     * @Route("/auction/",name="buyer_auction")
     */
    public function auctionListAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(FilterFormType::class);

        $queryBuilder = $em->getRepository('AppBundle:AuctionProduct')
            ->createQueryBuilder('auctionProduct')
            ->innerJoin('auctionProduct.whichAuction','auction')
            ->innerJoin('auction.product','product')
            ->andWhere('product.isActive = :isActive')
            ->setParameter('isActive', true)
            ->orderBy('auctionProduct.createdAt', 'DESC');

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
            'form'  =>$form->createView()
        ]);


    }

    /**
     * @Route("/auction/{id}/view",name="buyer_auction_product_details")
     */
    public function auctionProductDetailsAction(Request $request, AuctionProduct $product)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $form = $this->createForm(addToCartFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $quantity = $request->request->get('quantity');

            $em = $this->getDoctrine()->getManager();

            $auctionCart = new AuctionCart();
            $auctionCart->setProduct($product);
            $auctionCart->setWhoseCart($user);
            $auctionCart->setCompanyCart($user->getMyCompany());
            $auctionCart->setCartCurrency($user->getMyCompany()->getCurrency());
            $auctionCart->setItemPrice($product->getWhichAuction()->getPricePerStem());
            $auctionCart->setCartQuantity($quantity);

            $em->persist($auctionCart);
            $em->flush();

            return $this->redirectToRoute('auction_buyer_checkout',['id'=>$auctionCart->getId()]);

        }
        return $this->render('buyer/auction/product-details.htm.twig', [
            'product' => $product,
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/auction/buy/{id}",name="buyer_checkout_auction")
     */
    public function buyAuctionProductAction(Request $request, Auction $product){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->container->get('session')->set('auction_order','');

        $em = $this->getDoctrine()->getManager();

        $billingAddressArray = $em->getRepository('AppBundle:BillingAddress')
            ->findMyBillingAddress($user->getMyCompany());

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
        $this->container->get('session')->remove('auction_order');

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
            ->findMyBillingAddress($user->getMyCompany());

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
     * @Route("/auction/checkout/{id}/shipping-method",name="auction_buyer_checkout")
     */
    public function auctionShippingMethodAction(Request $request, AuctionCart $cart){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->container->get('session')->set('cart',$cart);

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ShippingMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $shippingCost = $request->request->get('shippingCost');

            $this->container->get('session')->set('cart', $cart);
            $this->container->get('session')->set('shippingCost',$shippingCost);
            return $this->redirectToRoute('auction_buyer_agent_checkout');

        }

        return $this->render(':partials/iflora/user/auction:shipping-method.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart'=> $cart
        ]);
    }
    /**
     * @Route("/auction/checkout-agent",name="auction_buyer_agent_checkout")
     */
    public function auctionAgentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $vendor = $user->getMyCompany();

        $cart = $this->container->get('session')->get('cart');

        $em = $this->getDoctrine()->getManager();

        $buyerAgents = $em->getRepository("AppBundle:BuyerAgent")
            ->findBy([
                'buyer'=>$vendor,
                'status'=>"Accepted"
            ]);
        $agents=array();
        foreach ($buyerAgents as $buyerAgent) {
            $agents[]=$buyerAgent->getAgent();
        }

        $form = $this->createForm(BuyerAgentFormType::class,null, ['agents' => $agents]);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $agent =$form["agent"]->getData();
            //var_dump($agent);exit;
            $this->container->get('session')->set('cart', $cart);
            $this->container->get('session')->set('agent',$agent);

            return $this->redirectToRoute('auction_buyer_payment_method');

        }

        return $this->render(':partials/iflora/user/auction:my-agent.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart'=> $cart
        ]);
    }
    /**
     * @Route("/auction/payment",name="auction_buyer_payment_method")
     */
    public function auctionPaymentAction(Request $request){

        $cart = $this->container->get('session')->get('cart');
        $shippingCost = $this->container->get('session')->get('shippingCost');
        $agent = $this->container->get('session')->get('agent');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $thisCart = $em->getRepository("AppBundle:AuctionCart")
            ->findOneBy([
                'id'=>$cart->getId()
            ]);
        $auctionProduct = $thisCart->getProduct();

        $myOwner = $em->getRepository('AppBundle:User')
            ->findOneBy([
                'id'=>$user->getId()
            ]);

        $myAgent = $em->getRepository('AppBundle:Company')
            ->findOneBy([
                'id'=>$agent->getId()
            ]);

        $myOrder = new AuctionOrder();
        $myOrder->setCreatedAt(new \DateTime());
        $myOrder->setOrderStatus("Processing");
        $myOrder->setOrderNotes("None");
        $myOrder->setWhoseOrder($myOwner);
        $myOrder->setProduct($auctionProduct);
        $myOrder->setItemPrice($thisCart->getItemPrice());
        $myOrder->setQuantity($thisCart->getCartQuantity());
        $myOrder->setBuyingAgent($myAgent);
        $myOrder->setSellingAgent($thisCart->getProduct()->getSellingAgent());
        $myOrder->setCheckoutCompletedAt(new \DateTime());
        $myOrder->setOrderState("Active");
        $myOrder->setOrderAmount(($thisCart->getCartQuantity()*$thisCart->getItemPrice()));
        $myOrder->setOrderCurrency($thisCart->getCartCurrency());
        $myOrder->setShippingCost($shippingCost);
        $myOrder->setOrderTotal(($thisCart->getCartQuantity()*$thisCart->getItemPrice())+$shippingCost);

        $form = $this->createForm(PaymentMethodFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $myOrder->setProcessingFee($request->request->get("paymentMethod"));

            $em->persist($myOrder);

            $em->flush();

            $this->container->get('session')->remove('agent');
            $this->container->get('session')->remove('product');
            $this->container->get('session')->remove('shippingCost');
            $this->container->get('session')->remove('auction_order');

            $this->container->get('session')->set('auction_order', $myOrder);
            $this->updateAuctionQty($thisCart->getCartQuantity(),$thisCart->getProduct());
            $this->sendOrderAssignmentNotification($myAgent,$myOrder);

            return $this->redirectToRoute('auction-checkout-complete');

        }
        $agent = $this->container->get('session')->get('agent');

        return $this->render(':partials/iflora/user/auction:pay.htm.twig', [
            'buyerCheckoutForm' => $form->createView(),
            'cart' => $cart,
            'agent' => $agent
        ]);
    }

    /**
     * @Route("/auction/checkout/complete",name="auction-checkout-complete")
     */
    public function auctionCheckoutCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $savedOrder = $this->container->get('session')->get('auction_order');

        $order = $em->getRepository("AppBundle:AuctionOrder")
            ->findOneBy(
                [
                    'id'=>$savedOrder->getId()
                ]
            );

        $form = $this->createForm(AuctionPaymentProofForm::class,$order);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();

            $em->persist($order);

            $em->flush();

            return $this->redirectToRoute('buyer-auction-payment-complete');

        }

        return $this->render(':partials/iflora/user/auction:checkout-complete.htm.twig',[
            'order'=>$order,
            'transactionForm'=>$form->createView()
        ]);
    }

    /**
     * @Route("/auction/payment/complete",name="buyer-auction-payment-complete")
     */
    public function auctionPaymentCompleteAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $order = $this->container->get('session')->get('auction_order');
        $orderId= $order->getId();
        $userOrder = $em->getRepository('AppBundle:AuctionOrder')
            ->findOneBy([
                'id'=>$orderId
            ]);

        $userOrder->setPaymentStatus("Complete");

        $em->persist($userOrder);
        $em->flush();

        $this->container->get('session')->clear();

        return $this->render(':partials:payment-complete.htm.twig',[
            'order'=>$userOrder
        ]);
    }


    public function getTotalRequestsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $totalRequests = 0;
        $em = $this->getDoctrine()->getManager();
        $nrBreederRequests = $em->getRepository('AppBundle:BuyerGrower')
            ->getNrGrowerRequests($user->getMyCompany());

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrAgentRequests($user->getMyCompany());
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
            ->getNrGrowerRequests($user->getMyCompany());

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
            ->getNrAgentRequests($user->getMyCompany());
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
            ->getNrMyGrowerRequests($user->getMyCompany());

        $nrAgentRequests = $em->getRepository('AppBundle:BuyerAgent')
            ->getNrMyAgentRequests($user->getMyCompany());
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
            ->getNrMyGrowerRequests($user->getMyCompany());

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
            ->getNrMyAgentRequests($user->getMyCompany());
        $totalRequests += $nrAgentRequests;

        return $this->render(':partials:totalRequests.html.twig', [
            'nrRequests' => $totalRequests,

        ]);

    }
    /**
     * @Route("/wishlist/my",name="my_buyer_wishlist")
     */
    public function myAuctionWishlistAction(){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();

        $wishlist = $em->getRepository('AppBundle:MyList')
            ->getMyWishlist($buyer);
        return $this->render(':home/myList:wishlist.htm.twig',[
            'wishlist' => $wishlist[0]
        ]);


    }
    /**
     * @Route("/wishlist/auction/my",name="my_buyer_auction_wishlist")
     */
    public function myWishlistAction(){
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        $em=$this->getDoctrine()->getManager();

        $wishlist = $em->getRepository('AppBundle:MyList')
            ->getMyAuctionWishlist($buyer);
        return $this->render(':home/myList:auctionWishlist.htm.twig',[
            'wishlist' => $wishlist[0]
        ]);


    }
    /**
     * @Route("/recommendations/my",name="my_buyer_recommendations")
     */
    public function myRecommendationsAction(){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();
        $em=$this->getDoctrine()->getManager();

        $recommendations = $em->getRepository('AppBundle:MyList')
            ->getMyUserRecommendations($buyer);
        return $this->render(':home/myList:recommend.htm.twig',[
            'recommendations' => $recommendations
        ]);


    }

    /**
     * @Route("/inbox",name="buyer-inbox")
     */
    public function inboxAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getInboxThreads($user);

        return $this->render(':home/messages:inbox.htm.twig',[
            'threads'=> $threads
        ]);
    }

    /**
     * @Route("/inbox/{id}/view",name="buyer-thread-view")
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
        return $this->render(':home/messages:thread.htm.twig',[
            'replyForm'=>$form->createView(),
            'thread'=>$thread
        ]);
    }
    /**
     * @Route("/sent",name="buyer-sent")
     */
    public function sentAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $threads = $em->getRepository("AppBundle:Thread")
            ->getSentThreads($user);

        return $this->render(':home/messages:sent.htm.twig',[
            'threads'=> $threads
        ]);
    }
    /**
     * @Route("/notifications",name="buyer-notifications")
     */
    public function deletedAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();


        $messages = $em->getRepository("AppBundle:Notification")
            ->getNotifications($user->getMyCompany());

        return $this->render(':home/messages:notification.htm.twig',[
            'messages'=> $messages
        ]);
    }

    /**
     * @Route("/notifications/{id}/view",name="view-notification")
     */
    public function viewNotificationAction(Request $request,Notification $notification){
        return $this->render(':home/messages:viewNotification.htm.twig',[
            'notification'=>$notification
        ]);
    }

    /**
     * @Route("/compare",name="compare-products")
     *
     */
    public function compareProductsAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $compareList = $em->getRepository("AppBundle:MyList")->findBy(['listOwner' => $user, 'listType' => 'Product-Compare', 'productType' => 'Rose',]);
        return $this->render('compare/compare.htm.twig', ['productList' => $compareList]);
    }
    public function buyerGrowerExists(Company $buyer, Company $grower){
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
    public function buyerAgentExists(Company $buyer, Company $agent){
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

    private function updateQuantity(Direct $product, $quantity)
    {
        $em = $this->getDoctrine()->getManager();

        $existingQty = $product->getNumberOfStems();
        $newQuantity = $existingQty - $quantity;

        $product->setNumberOfStems($newQuantity);

        $em->persist($product);
        $em->flush();

    }
    public function sendOrderAssignmentNotification(Company $agent,AuctionOrder $order){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();

        $em = $this->getDoctrine()->getManager();

        $message="<p>".$buyer." </b> has assigned Order #".$order->getPrettyId()." in the Auction to you and the order has been Automatically added to your list of Orders</p>";

        $notification = new Notification();
        $notification->setSubject("New Order Assignment: ".$order->getPrettyId());
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($agent);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }

    private function updateAuctionQty($quantity, AuctionProduct $product)
    {
        $em = $this->getDoctrine()->getManager();

        $existingQty = $product->getAvailableStock();
        $newQuantity = $existingQty - $quantity;

        $product->setAvailableStock($newQuantity);

        $em->persist($product);
        $em->flush();

    }


}