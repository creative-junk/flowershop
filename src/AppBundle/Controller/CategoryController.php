<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/categories",name="category_list")
     */
    public function listAction(){
        $em=$this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAll();

        return $this->render(':partials:categories.html.twig',[
            'categoryList'=>$categories,
        ]);
    }
}
