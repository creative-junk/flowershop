<?php

namespace AppBundle\Controller\Api;

use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class APIController extends Controller
{
    /**
     * @Rest\Get("/api/login")
     */
    public function loginAction(Request $request){
        $user = $this->getDoctrine()
            ->getRepository("AppBundle:User")
            ->findOneBy([
                'email'=>$request->getUser()
            ]);

        if (!$user){
            //throw new BadCredentialsException('Invalid Login');
            return new Response('Authorization Required',401);
        }

        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user,$request->getPassword());

        if (!$isValid){
            //throw new BadCredentialsException();
            return new Response('Authorization Required',401);
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'role'     => $user->getMyCompany()->getCompanyType(),
                'companyName'  => $user->getMyCompany()->getCompanyName(),
                'exp'      => time() + 3600
            ]);
        return new JsonResponse(['token' => $token]);
    }

}
