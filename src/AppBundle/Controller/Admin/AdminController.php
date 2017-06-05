<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryFormType;
use AppBundle\Form\ProductFormType;
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

        $nrOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrOrders();
        $nrUsers = $em->getRepository('AppBundle:User')
            ->getNrUsers();
        $nrBuyers = $em->getRepository('AppBundle:User')
            ->getNrBuyers();
        $nrAgents = $em->getRepository('AppBundle:User')
            ->getNrAgents();
        $nrGrowers = $em->getRepository('AppBundle:User')
            ->getNrGrowers();
        $nrBreeders = $em->getRepository('AppBundle:User')
            ->getNrBreeders();

        $nrChangeOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrChangeOrders();
        $nrChangeUsers = $em->getRepository('AppBundle:User')
            ->getNrChangeUsersThisWeek();
        $nrChangeBuyers = $em->getRepository('AppBundle:User')
            ->getNrChangeBuyersThisWeek();
        $nrChangeAgents = $em->getRepository('AppBundle:User')
            ->getNrChangeAgentsThisWeek();
        $nrChangeGrowers = $em->getRepository('AppBundle:User')
            ->getNrChangeGrowersThisWeek();
        $nrChangeBreeders = $em->getRepository('AppBundle:User')
            ->getNrChangeBreedersThisWeek();

        $percentChangeOrders = ($nrChangeOrders/$nrOrders)*100;
        $percentChangeUsers = ($nrChangeUsers/$nrUsers)*100;
        $percentChangeBuyers = ($nrChangeBuyers/$nrBuyers)*100;
        $percentChangeAgents = ($nrChangeAgents/$nrAgents)*100;
        $percentChangeGrowers = ($nrChangeGrowers/$nrGrowers)*100;
        $percentChangeBreeders = ($nrChangeBreeders/$nrBreeders)*100;

        return $this->render(':admin:dashboard.htm.twig',[
            'nrUsers'=>$nrUsers,
            'nrOrders'=>$nrOrders,
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
     * @Route("/profile",name="my_profile")
     */
    public function profileAction(){

    }
    /**
     * @Route("/settings",name="app_settings")
     */
    public function settingsAction(){

    }
}