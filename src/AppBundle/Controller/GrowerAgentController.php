<?php

namespace AppBundle\Controller;

use AppBundle\Entity\GrowerAgent;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GrowerAgentController extends Controller
{
    /**
     * @Route("/grower/agent/{id}/request",name="request-agent-grower")
     */

    public function requestAgentAction(User $agent)
    {
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->growerAgentExists($grower,$agent,$grower)){
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

            return new Response(null, 204);
        }
    }
    /**
     * @Route("/agent/grower/{id}/request",name="request-grower-agent")
     */
    public function requestGrowerAction(User $grower){
        $agent = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->growerAgentExists($grower,$agent,$agent)){
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

            return new Response(null, 204);
        }
    }

    public function growerAgentExists(User $grower, User $agent,User $whoseList){
        $em = $this->getDoctrine()->getManager();

        $growerAgent = $em->getRepository('AppBundle:growerAgent')
            ->findOneBy([
                'buyer'=>$grower,
                'agent'=>$agent,
                'listOwner'=> $whoseList
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
        $growerAgent->setStatus("Accepted");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/agent/{id}/request",name="reject-grower-agent-request")
     */

    public function rejectGrowerAgentRequestAction(GrowerAgent $growerAgent)
    {
        $growerAgent->setStatus("Rejected");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/agent/{id}/cancel",name="cancel-grower-agent-request")
     */

    public function cancelGrowerAgentRequestAction(GrowerAgent $growerAgent)
    {
        $growerAgent->setStatus("Cancelled");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/grower/accept/{id}/request",name="accept-agent-grower-request")
     */
    public function acceptGrowerRequest(GrowerAgent $growerAgent){

        $growerAgent->setStatus("Accepted");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        return new Response(null, 204);
    }
    /**
     * @Route("/grower/reject/{id}/request",name="reject-agent-grower-request")
     */
    public function rejectBuyerRequest(GrowerAgent $growerAgent){

        $growerAgent->setStatus("Rejected");
        $growerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerAgent);
        $em->flush();

        return new Response(null, 204);
    }

}
