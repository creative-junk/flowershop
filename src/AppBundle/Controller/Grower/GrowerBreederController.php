<?php

namespace AppBundle\Controller\Grower;

use AppBundle\Entity\Company;
use AppBundle\Entity\GrowerBreeder;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GrowerBreederController extends Controller
{
    /**
     * @Route("/grower/breeder/{id}/request",name="request-breeder-grower")
     */

    public function requestBreederAction(Request $request,Company $breeder)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        if ($this->growerBreederExists($grower,$breeder)){
            return new Response(null,500);
        }else {
            $growerBreeder = new GrowerBreeder();
            $growerBreeder->setBreeder($breeder);
            $growerBreeder->setGrower($grower);
            $growerBreeder->setStatus('Requested');
            $growerBreeder->setListOwner($grower);
            $growerBreeder->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($growerBreeder);
            $em->flush();

            $subject ="You have a new Grower Request";
            $message = "You have received a new Connect request from ".$grower->getCompanyName();
            $this->sendNotification($breeder,$subject,$message);


            return new Response(null, 204);
        }
    }
    /**
     * @Route("/breeder/grower/{id}/request",name="request-grower-breeder")
     */
    public function requestGrowerAction(Company $grower){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $breeder = $user->getMyCompany();

        if ($this->growerBreederExists($grower,$breeder)){
            return new Response(null,500);
        }else {
            $growerBreeder = new GrowerBreeder();
            $growerBreeder->setBreeder($breeder);
            $growerBreeder->setGrower($grower);
            $growerBreeder->setStatus('Requested');
            $growerBreeder->setListOwner($breeder);
            $growerBreeder->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($growerBreeder);
            $em->flush();


            $subject ="You have a new Breeder Request";
            $message = "You have received a new Connect request from ".$breeder->getCompanyName();
            $this->sendNotification($grower,$subject,$message);

            return new Response(null, 204);
        }
    }
    public function growerBreederExists(Company $grower, Company $breeder){
        $em = $this->getDoctrine()->getManager();

        $growerBreeder = $em->getRepository('AppBundle:GrowerBreeder')
            ->findOneBy([
                'grower'=>$grower,
                'breeder'=>$breeder
            ]);
        if ($growerBreeder){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @Route("/grower/request/breeder/{id}/accept",name="accept_grower-breeder-request")
     */

    public function acceptGrowerAgentRequestAction(GrowerBreeder $growerBreeder)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $agent = $growerBreeder->getAgent();
        $breeder = $growerBreeder->getBreeder();


        $growerBreeder->setStatus("Accepted");
        $growerBreeder->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerBreeder);
        $em->flush();


        if ($me == $agent){
            $who = $agent->getCompanyName();
        }else{
            $who = $breeder->getCompanyName();
        }

        $subject ="Connect Request Accepted";
        $message = $who." has accepted your Connect request";
        $this->sendNotification($who,$subject,$message);


        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/breeder/{id}/request",name="reject-grower-breeder-request")
     */

    public function rejectGrowerAgentRequestAction(GrowerBreeder $growerBreeder)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $grower = $growerBreeder->getGrower();
        $breeder = $growerBreeder->getBreeder();

        $em = $this->getDoctrine()->getManager();
        $em->remove($growerBreeder);
        $em->flush();


        if ($me == $breeder){
            $who = $breeder->getCompanyName();
        }else{
            $who = $grower->getCompanyName();
        }

        $subject ="Connect Request Rejected";
        $message = $who." has rejected your Connect request";
        $this->sendNotification($who,$subject,$message);

        return new Response(null, 204);
    }
    /**
     * @Route("/grower/request/breeder/{id}/cancel",name="cancel-grower-breeder-request")
     */

    public function cancelGrowerBreederRequestAction(GrowerBreeder $growerBreeder)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($growerBreeder);
        $em->flush();

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
