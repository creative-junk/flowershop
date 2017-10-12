<?php

namespace AppBundle\Controller\Buyer;

use AppBundle\Entity\BuyerAgent;
use AppBundle\Entity\BuyerGrower;
use AppBundle\Entity\Company;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BuyerGrowerController extends Controller
{
    /**
     * @Route("/home/grower/{id}/request",name="request-grower-buyer")
     */

    public function requestGrowerAction(Request $request,Company $grower)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();

        if ($this->buyerGrowerExists($buyer,$grower)){
            return new Response(null,500);
        }else {
            $buyerGrower = new BuyerGrower();
            $buyerGrower->setGrower($grower);
            $buyerGrower->setBuyer($buyer);
            $buyerGrower->setStatus('Requested');
            $buyerGrower->setListOwner($buyer);
            $buyerGrower->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($buyerGrower);
            $em->flush();

            $subject ="You have a new Buyer Request";
            $message = "You have received a new Connect request from ".$buyer->getCompanyName();
            $this->sendNotification($grower,$subject,$message);

            return new Response(null, 204);
        }
    }
    /**
     * @Route("/grower/buyer/{id}/request",name="request-buyer-grower")
     */
    public function requestBuyerAction(Request $request,Company $buyer){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        if ($this->buyerGrowerExists($buyer,$grower)){
            return new Response(null,500);
        }else {
            $buyerGrower = new BuyerGrower();
            $buyerGrower->setGrower($grower);
            $buyerGrower->setBuyer($buyer);
            $buyerGrower->setStatus('Requested');
            $buyerGrower->setListOwner($grower);
            $buyerGrower->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($buyerGrower);
            $em->flush();

            $subject ="You have a new Grower Request";
            $message = "You have received a new Connect request from ".$grower->getCompanyName();
            $this->sendNotification($buyer,$subject,$message);


            return new Response(null, 204);
        }
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
    /**
     * @Route("/buyer/request/grower/{id}/cancel",name="cancel-buyer-grower-request")
     */

    public function cancelGrowerBuyerRequestAction(BuyerGrower $buyerGrower)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($buyerGrower);
        $em->flush();

        return new Response(null, 204);
    }
    /**
     * @Route("/grower/accept/{id}/request",name="accept-buyer-grower-request")
     */
    public function acceptGrowerRequest(BuyerGrower $buyerGrower){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $grower = $buyerGrower->getGrower();
        $buyer = $buyerGrower->getBuyer();


        $buyerGrower->setStatus("Accepted");
        $buyerGrower->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($buyerGrower);
        $em->flush();


        if ($me == $grower){
            $who = $grower->getCompanyName();
        }else{
            $who = $buyer->getCompanyName();
        }

        $subject ="Connect Request Accepted";
        $message = $who." has accepted your Connect request";
        $this->sendNotification($who,$subject,$message);


        return new Response(null, 204);
    }
    /**
     * @Route("/grower/reject/{id}/request",name="reject-buyer-grower-request")
     */
    public function rejectBuyerRequest(BuyerGrower $buyerGrower){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $grower = $buyerGrower->getGrower();
        $buyer = $buyerGrower->getBuyer();


        $buyerGrower->setStatus("Rejected");
        $buyerGrower->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->remove($buyerGrower);
        $em->flush();

        if ($me == $grower){
            $who = $grower->getCompanyName();
        }else{
            $who = $buyer->getCompanyName();
        }

        $subject ="Connect Request Rejected";
        $message = $who." has rejected your Connect request";
        $this->sendNotification($who,$subject,$message);



        return new Response(null, 204);
    }

    public function sendNotification(Company $receiver,$subject,$message){

        $em = $this->getDoctrine()->getManager();


        $notification = new Notification();
        $notification->setSubject($subject);
        $notification->setIsRead(false);
        $notification->setIsDeleted(false);

        $notification->setSentAt(new \DateTime());
        $notification->setParticipant($receiver);
        $notification->setMessage($message);

        $em->persist($notification);
        $em->flush();


    }
}
