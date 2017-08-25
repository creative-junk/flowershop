<?php

namespace AppBundle\Controller\Buyer;

use AppBundle\Entity\BuyerAgent;
use AppBundle\Entity\Company;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

class BuyerAgentController extends Controller
{
    /**
     * @Route("/home/buyer/{id}/request",name="request-agent-buyer")
     */

    public function requestAgentAction(Company $agent)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $buyer = $user->getMyCompany();

        if ($this->buyerAgentExists($buyer,$agent)){
            return new Response(null,500);
        }else {
            $buyerAgent = new BuyerAgent();
            $buyerAgent->setAgent($agent);
            $buyerAgent->setBuyer($buyer);
            $buyerAgent->setStatus('Requested');
            $buyerAgent->setListOwner($buyer);
            $buyerAgent->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($buyerAgent);
            $em->flush();

            return new Response(null, 204);
        }
    }
    /**
     * @Route("/agent/agent/{id}/request",name="request-buyer-agent")
     */
    public function requestBuyerAction(Company $buyer){
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $agent = $user->getMyCompany();

        if ($this->buyerAgentExists($buyer,$agent)){
            return new Response(null,500);
        }else {
            $buyerAgent = new BuyerAgent();
            $buyerAgent->setAgent($agent);
            $buyerAgent->setBuyer($buyer);
            $buyerAgent->setStatus('Requested');
            $buyerAgent->setListOwner($agent);
            $buyerAgent->setUpdatedAt(new \DateTime());

            $em = $this->getDoctrine()->getManager();
            $em->persist($buyerAgent);
            $em->flush();

            return new Response(null, 204);
        }
    }
    public function buyerAgentExists(Company $buyer, Company $agent){
        $em = $this->getDoctrine()->getManager();

        $buyerAgent = $em->getRepository('AppBundle:BuyerAgent')
            ->findOneBy([
                'buyer'=>$buyer,
                'agent'=>$agent
            ]);
        if ($buyerAgent){
            return true;
        }else{
            return false;
        }
    }
    /**
     * @Route("/buyer/request/agent/{id}/cancel",name="cancel-buyer-agent-request")
     */

    public function cancelGrowerBreederRequestAction(BuyerAgent $buyerAgent)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($buyerAgent);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/buyer/accept/{id}/request",name="accept-agent-buyer-request")
     */
    public function acceptBuyerRequest(BuyerAgent $buyerAgent){

        $buyerAgent->setStatus("Accepted");
        $buyerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($buyerAgent);
        $em->flush();

        return new Response(null, 204);
    }
    /**
     * @Route("/buyer/reject/{id}/request",name="reject-agent-buyer-request")
     */
    public function rejectBuyerRequest(BuyerAgent $buyerAgent){

        $buyerAgent->setStatus("Rejected");
        $buyerAgent->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($buyerAgent);
        $em->flush();

        return new Response(null, 204);
    }
}
