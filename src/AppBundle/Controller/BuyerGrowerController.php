<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BuyerGrower;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BuyerGrowerController extends Controller
{
    /**
     * @Route("/home/grower/{id}/request",name="request-grower-buyer")
     */

    public function requestGrowerAction(User $grower)
    {
        $buyer = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->buyerGrowerExists($buyer,$grower,$buyer)){
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

            return new Response(null, 204);
        }
    }
    /**
     * @Route("/grower/buyer/{id}/request",name="request-buyer-grower")
     */
    public function requestBuyerAction(User $buyer){
        $grower = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->buyerGrowerExists($buyer,$grower,$grower)){
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

            return new Response(null, 204);
        }
    }
    public function buyerGrowerExists(User $buyer, User $grower,User $whoseList){
        $em = $this->getDoctrine()->getManager();

        $buyerGrower = $em->getRepository('AppBundle:BuyerGrower')
            ->findOneBy([
                'buyer'=>$buyer,
                'grower'=>$grower,
                'listOwner'=> $whoseList
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

    public function cancelGrowerBreederRequestAction(BuyerGrower $buyerGrower)
    {
        $buyerGrower->setStatus("Cancelled");
        $buyerGrower->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($buyerGrower);
        $em->flush();

        return new Response(null, 204);
    }
}
