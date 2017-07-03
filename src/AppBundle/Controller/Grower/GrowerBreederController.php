<?php

namespace AppBundle\Controller\Grower;

use AppBundle\Entity\Company;
use AppBundle\Entity\GrowerBreeder;
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

        if ($this->growerBreederExists($grower,$breeder,$grower)){
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

            return new Response(null, 204);
        }
    }
    /**
     * @Route("/breeder/grower/{id}/request",name="request-grower-breeder")
     */
    public function requestGrowerAction(Company $grower){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $breeder = $user->getMyCompany();

        if ($this->growerBreederExists($grower,$breeder,$grower)){
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

            return new Response(null, 204);
        }
    }
    public function growerBreederExists(Company $grower, Company $breeder,Company $whoseList){
        $em = $this->getDoctrine()->getManager();

        $growerBreeder = $em->getRepository('AppBundle:GrowerBreeder')
            ->findOneBy([
                'grower'=>$grower,
                'breeder'=>$breeder,
                'listOwner'=> $whoseList
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
        $growerBreeder->setStatus("Accepted");
        $growerBreeder->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerBreeder);
        $em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/grower/request/breeder/{id}/request",name="reject-grower-breeder-request")
     */

    public function rejectGrowerAgentRequestAction(GrowerBreeder $growerBreeder)
    {
        $growerBreeder->setStatus("Rejected");
        $growerBreeder->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerBreeder);
        $em->flush();

        return new Response(null, 204);
    }
    /**
     * @Route("/grower/request/breeder/{id}/cancel",name="cancel-grower-breeder-request")
     */

    public function cancelGrowerBreederRequestAction(GrowerBreeder $growerBreeder)
    {
        $growerBreeder->setStatus("Cancelled");
        $growerBreeder->setDateSince(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($growerBreeder);
        $em->flush();

        return new Response(null, 204);
    }
}
