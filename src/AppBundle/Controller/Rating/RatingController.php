<?php

namespace AppBundle\Controller\Rating;

use AppBundle\Entity\Product;
use AppBundle\Entity\Rating;
use AppBundle\Form\RatingFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends Controller
{
    /**
     * @Route("rate/rose",name="rate-rose")
     */
    public function roseRatingAction(Request $request,$roseId=null)
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

        $reviews = $em->getRepository("AppBundle:Rating")
            ->findRoseReviews($rose);

        $rating = new Rating();
        $rating->setRose($rose);
        $rating->setRatedBy($user);

        $form = $this->createForm(RatingFormType::class,$rating);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $fullRating = $form->getData();

            $rose_id =  $request->request->get('rose');

            $rose = $em->getRepository("AppBundle:Product")
                ->findOneBy([
                    'id'=>$rose_id
                ]);

            $rating->setRose($rose);

            $em->persist($fullRating);
            $em->flush();

            return new Response(null,204);
        }

        return $this->render('rating/rating.htm.twig',[
            'reviews'=>$reviews,
            'ratingForm'=>$form->createView(),
            'rose'=>$rose
        ]);
    }
 /**
 * @Route("rate/user",name="rate-user")
 */
    public function userRatingAction(Request $request,$companyId=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $company = $em->getRepository("AppBundle:Company")
            ->findOneBy([
                'id'=>$companyId
            ]);

        $rating = new Rating();
        $rating->setVendor($company);
        $rating->setRatedBy($user);

        $form = $this->createForm(RatingFormType::class,$rating);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $rating = $form->getData();

            $grower_id =  $request->request->get('grower');

            $grower = $em->getRepository("AppBundle:Company")
                ->findOneBy([
                    'id'=>$grower_id
                ]);

            $rating->setVendor($grower);

            $em->persist($rating);
            $em->flush();

            return new Response(null,204);
        }
        $reviews = $em->getRepository("AppBundle:Rating")
            ->findVendorReviews($company);

        return $this->render('rating/company-rating.htm.twig',[
            'reviews'=>$reviews,
            'ratingForm'=>$form->createView(),
            'grower'=>$company
        ]);
    }

    /**
     * @Route("rate/agent",name="rate-agent")
     */
    public function agentRatingAction(Request $request,$agentId=null)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $agent = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'id'=>$agentId
            ]);
        $reviews = $em->getRepository("AppBundle:Rating")
            ->findAgentReviews($agent);


        $rating = new Rating();
        $rating->setAgent($agent);
        $rating->setRatedBy($user);

        $form = $this->createForm(RatingFormType::class,$rating);

        $form->handleRequest($request);

        if ($form->isValid()&&$form->isSubmitted()){

            $rating = $form->getData();

            $agent_id =  $request->request->get('grower');

            $agent = $em->getRepository("AppBundle:User")
                ->findOneBy([
                    'id'=>$agent_id
                ]);

            $rating->setAgent($agent);

            $em->persist($rating);
            $em->flush();

            return new Response(null,204);
        }

        return $this->render('rating/agent-rating.htm.twig',[
            'reviews'=>$reviews,
            'ratingForm'=>$form->createView(),
            'agent'=>$agent
        ]);
    }


    /**
     * @Route("rate/listing/rose",name="rate-rose-listing")
     */
    public function roseRatingListingAction(Request $request,$roseId)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em= $this->getDoctrine()->getManager();

        $rose = $em->getRepository("AppBundle:Product")
            ->findOneBy([
                'id'=>$roseId
            ]);

        $reviews = $em->getRepository("AppBundle:Rating")
            ->findRoseReviews($rose);

        return $this->render('rating/listing.htm.twig',[
            'reviews'=>$reviews,

        ]);
    }

    /**
     * @Route("rating/average",name="average-rose-rating")
     */
    public function averageRatingAction(Request $request,$roseId){

        $em= $this->getDoctrine()->getManager();

        $rose = $em->getRepository("AppBundle:Product")
            ->findOneBy([
                'id'=>$roseId
            ]);


        $nrRatings = $em->getRepository("AppBundle:Rating")
            ->nrRatingsForRose($rose);

        if ($nrRatings >0){
            $qualityRating = $em->getRepository("AppBundle:Rating")
                ->sumQualityRatingsForRose($rose);
            $valueRating = $em->getRepository("AppBundle:Rating")
                ->sumValueRatingsForRose($rose);
            $priceRating = $em->getRepository("AppBundle:Rating")
                ->sumPriceRatingsForRose($rose);

            $averageQualityRating =$qualityRating[0];
            $averageValueRating = $valueRating[0];
            $averagePriceRating = $priceRating[0];

            $sumRating = $averageQualityRating['sumQuality']+$averagePriceRating['sumQuality']+$averageValueRating['sumQuality'];

            $averageRating=$sumRating/3;

            $average=intval(round($averageRating));


        }else{
            $average=0;
        }
        return $this->render(":partials/iflora:rating.htm.twig",[
            'nrItems'=>$average
        ]);
    }
}
