<?php

namespace AppBundle\Controller\Comment;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Product;
use AppBundle\Entity\Rating;
use AppBundle\Form\CommentFormType;
use AppBundle\Form\RatingFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends Controller
{
    /**
     * @Route("comment/rose",name="comment-rose")
     */
    public function roseCommentAction(Request $request,$roseId=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        if ($roseId==null){
            $roseId= $request->request->get('rose');
        }

        $rose = $em->getRepository("AppBundle:Product")
            ->findOneBy([
                'id'=>$roseId
            ]);

        $comments = $em->getRepository("AppBundle:Comment")
            ->findRoseComments($rose);

        $comment = new Comment();
        $comment->setProduct($rose);
        $comment->setAuthor($user);

        $form = $this->createForm(CommentFormType::class,$comment);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $fullRating = $form->getData();

            $rose_id =  $request->request->get('rose');

            $rose = $em->getRepository("AppBundle:Product")
                ->findOneBy([
                    'id'=>$rose_id
                ]);

            $comment->setProduct($rose);

            $em->persist($fullRating);
            $em->flush();

            return new Response(null,204);
        }

        return $this->render('comments/comment.htm.twig',[
            'comments'=>$comments,
            'commentForm'=>$form->createView(),
            'rose'=>$rose
        ]);
    }
    /**
     * @Route("comment/user",name="comment-user")
     */
    public function userCommentAction(Request $request,$companyId=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        if ($companyId==null){
            $companyId= $request->request->get('grower');
        }

        $grower = $em->getRepository("AppBundle:Company")
            ->findOneBy([
                'id'=>$companyId
            ]);

        $comments = $em->getRepository("AppBundle:Comment")
            ->findUserComments($grower);

        $comment = new Comment();
        $comment->setVendor($grower);
        $comment->setAuthor($user);

        $form = $this->createForm(CommentFormType::class,$comment);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $comment = $form->getData();

            $grower_id =  $request->request->get('grower');

            $grower = $em->getRepository("AppBundle:Company")
                ->findOneBy([
                    'id'=>$grower_id
                ]);

            $comment->setVendor($grower);

            $em->persist($comment);
            $em->flush();

            return new Response(null,204);
        }

        return $this->render('comments/company-comment.htm.twig',[
            'comments'=>$comments,
            'commentForm'=>$form->createView(),
            'company'=>$grower
        ]);
    }
    /**
     * @Route("comment/agent",name="comment-agent")
     */
    public function agentCommentAction(Request $request,$agentId=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        if ($agentId==null){
            $agentId= $request->request->get('grower');
        }

        $agent = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'id'=>$agentId
            ]);

        $comments = $em->getRepository("AppBundle:Comment")
            ->findAgentComments($agent);

        $comment = new Comment();
        $comment->setAgent($agent);
        $comment->setAuthor($user);

        $form = $this->createForm(CommentFormType::class,$comment);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $comment = $form->getData();

            $agent_id =  $request->request->get('grower');

            $agent = $em->getRepository("AppBundle:User")
                ->findOneBy([
                    'id'=>$agent_id
                ]);

            $comment->setAgent($agent);

            $em->persist($comment);
            $em->flush();

            return new Response(null,204);
        }

        return $this->render('comments/agent-comment.htm.twig',[
            'comments'=>$comments,
            'commentForm'=>$form->createView(),
            'agent'=>$agent
        ]);
    }
    /**
     * @Route("comment/listing/rose",name="comment-rose-listing")
     */
    public function roseRatingListingAction(Request $request,$roseId)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $rose = $em->getRepository("AppBundle:Product")
            ->findOneBy([
                'id'=>$roseId
            ]);

        $reviews = $em->getRepository("AppBundle:Comment")
            ->findRoseComments($rose);

        return $this->render('rating/listing.htm.twig',[
            'reviews'=>$reviews,

        ]);
    }

}
