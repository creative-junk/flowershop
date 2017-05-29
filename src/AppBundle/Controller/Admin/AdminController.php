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
use AppBundle\Form\CategoryFormType;
use AppBundle\Form\ProductFormType;
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

        return $this->render(':admin:dashboard.htm.twig');
        //dump($products);die;
        //return new Response('Product Saved');
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

        $form = $this->createForm(ProductFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Created, Yaay!');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/new.html.twig',[
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/user/{id}/edit",name="user_edit")
     */
    public function editAction(Request $request,Product $product)
    {
        $form = $this->createForm(ProductFormType::class,$product);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Updated, Yaay!');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/edit.html.twig',[
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