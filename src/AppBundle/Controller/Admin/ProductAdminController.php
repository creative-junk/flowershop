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
use AppBundle\Form\AdminProductFormType;
use AppBundle\Form\CategoryFormType;
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
class ProductAdminController extends Controller
{
    /**
     * @Route("/product/",name="admin_product_list")
     */
    public function listAction(){


        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Product')
            ->findAllActiveProductsOrderByDate();

        return $this->render('admin/product/list.html.twig',[
            'products'=>$products,
        ]);
        //dump($products);die;
        //return new Response('Product Saved');
    }
    /**
     * @Route("/auction/",name="admin_auction_list")
     *
     */
    public function listAuctionAction()
    {
        $em=$this->getDoctrine()->getManager();
        $products = $em->getRepository('AppBundle:Auction')
            ->findAllActiveAuctionProductsOrderByDate();

        return $this->render('admin/auction/list.html.twig',[
            'auctionProducts'=>$products,
        ]);
    }
    /**
     * @Route("/product/new",name="admin_product_new")
     */
    public function newAction(Request $request)
    {
        $product = new Product();
        $product->setUser($this->get('security.token_storage')->getToken()->getUser());

        $form = $this->createForm(AdminProductFormType::class, $product);

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
     * @Route("/product/{id}/edit",name="admin_product_edit")
     */
    public function editAction(Request $request,Product $product)
    {
        $form = $this->createForm(AdminProductFormType::class,$product);

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
     * @Route("/category/",name="admin_category_list")
     */
    public function listCategoryAction(){
        $em = $this->getDoctrine()->getManager();
        $categories=$em->getRepository('AppBundle:Category')
            ->findAll();

        return $this->render('admin/category/list.html.twig',[
            'categories'=>$categories,
        ]);
        //dump($categories);die;
        //return new Response('Category Saved');
    }
    /**
     * @Route("/category/new",name="admin_category_new")
     */
    public function newCategoryAction(Request $request)
    {
        $form = $this->createForm(CategoryFormType::class);

        //only handles data on POST
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $category = $form ->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_category_list');
        }

        return $this->render('admin/category/new.html.twig',[
            'categoryForm' => $form->createView()
        ]);
    }
}