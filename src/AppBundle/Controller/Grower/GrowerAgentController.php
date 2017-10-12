<?php

namespace AppBundle\Controller\Grower;

use AppBundle\Entity\Company;
use AppBundle\Entity\GrowerAgent;
use AppBundle\Entity\Notification;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GrowerAgentController extends Controller
{
    /**
     * @Route("/grower/agent/{id}/request",name="request-agent-grower")
     */

    public function requestAgentAction(Company $agent)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $grower = $user->getMyCompany();

        if ($this->growerAgentExists($grower,$agent)){
            return new Response(null,500);
        }else {
            $growerAgent = new GrowerAgent();
            $growerAgent->setAgent($agent);
            $growerAgent->setGrower($grower);
            $growerAgent->setStatus('Requested');
            $growerAgent->setListOwner($grower);
            $growerAgent->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($growerAgent);
            $em->flush();

            $subject ="You have a new Buyer Request";
            $message = "You have received a new Connect request from ".$grower->getCompanyName();
            $this->sendNotification($agent,$subject,$message);


            return new Response(null, 204);
        }
    }
    /**
     * @Route("/agent/grower/{id}/request",name="request-grower-agent")
     */
    public function requestGrowerAction(Company $grower){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $agent = $user->getMyCompany();

        if ($this->growerAgentExists($grower,$agent)){
            return new Response(null,500);
        }else {
            $growerAgent = new GrowerAgent();
            $growerAgent->setAgent($agent);
            $growerAgent->setGrower($grower);
            $growerAgent->setStatus('Requested');
            $growerAgent->setListOwner($agent);
            $growerAgent->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($growerAgent);
            $em->flush();

            $subject ="You have a new Agent Request";
            $message = "You have received a new Connect request from ".$agent->getCompanyName();
            $this->sendNotification($grower,$subject,$message);

            return new Response(null, 204);
        }
    }

    public function growerAgentExists(Company $grower, Company $agent){
        $em = $this->getDoctrine()->getManager();

        $growerAgent = $em->getRepository('AppBundle:growerAgent')
            ->findOneBy([
                'grower'=>$grower,
                'agent'=>$agent
            ]);
        if ($growerAgent){
            return true;
        }else{
            return false;
        }
    }

    /**
     * @Route("/grower/request/agent/{id}/accept",name="accept-grower-agent-request")
     */

    public function acceptGrowerAgentRequestAction(GrowerAgent $growerAgent)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $agent = $growerAgent->getAgent();
        $grower = $growerAgent->getGrower();

        $growerAgent->setStatus("Accepted");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        if ($me == $agent){
            $who = $agent->getCompanyName();
        }else{
            $who = $grower->getCompanyName();
        }

        $subject ="Connect Request Accepted";
        $message = $who." has accepted your Connect request";
        $this->sendNotification($who,$subject,$message);


        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/agent/{id}/request",name="reject-grower-agent-request")
     */

    public function rejectGrowerAgentRequestAction(GrowerAgent $growerAgent)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $me = $user->getMyCompany();

        $agent = $growerAgent->getAgent();
        $grower = $growerAgent->getGrower();

        $em = $this->getDoctrine()->getManager();
        $em->remove($growerAgent);
        $em->flush();


        if ($me == $agent){
            $who = $agent->getCompanyName();
        }else{
            $who = $grower->getCompanyName();
        }

        $subject ="Connect Request Rejected";
        $message = $who." has rejected your Connect request";
        $this->sendNotification($who,$subject,$message);


        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/agent/{id}/cancel",name="cancel-grower-agent-request")
     */

    public function cancelGrowerAgentRequestAction(GrowerAgent $growerAgent)
    {

        $em = $this->getDoctrine()->getManager();
        $em->remove($growerAgent);
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
