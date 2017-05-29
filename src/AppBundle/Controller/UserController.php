<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @Route("/register",name="user_register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $user->setUserType('buyer');
        $user->setRoles(["ROLE_BUYER"]);
        $user->setIsActive(true);
        $user->setCurrency('KSH');

        $form = $this->createForm(UserRegistrationForm::class, $user);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // $this->addFlash('success','Welcome '.$user->getEmail().' to Iflora');

            /*return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );*/
            return $this->redirectToRoute('user-registered');
        }
        return $this->render('user/register.htm.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/registered",name="user-registered")
     */
    public function userRegisteredAction()
    {
        return $this->render('user/user-registered.htm.twig');
    }

    /**
     * @Route("/register/grower",name="grower_register")
     */
    public function registerGrowerAction(Request $request)
    {
        $user = new User();
        $user->setUserType('grower');
        $user->setRoles(["ROLE_GROWER"]);
        $user->setIsActive(true);
        $user->setCurrency('KSH');

        $form = $this->createForm(UserRegistrationForm::class, $user);


        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            /*  $this->addFlash('success','Welcome '.$user->getEmail().' to Iflora');

              return $this->get('security.authentication.guard_handler')
                  ->authenticateUserAndHandleSuccess(
                      $user,
                      $request,
                      $this->get('app.security.login_form_authenticator'),
                      'main'
                  );*/
            return $this->redirectToRoute('grower-registered');
        }
        return $this->render('user/grower-register.htm.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/grower/registered",name="grower-registered")
     */
    public function growerRegisteredAction()
    {
        return $this->render('user/grower-registered.htm.twig');
    }

    /**
     * @Route("/register/breeder",name="breeder_register")
     */
    public function registerBreederAction(Request $request)
    {
        $user = new User();
        $user->setUserType('breeder');
        $user->setRoles(["ROLE_BREEDER"]);
        $user->setIsActive(true);
        $user->setCurrency('KSH');

        $form = $this->createForm(UserRegistrationForm::class, $user);


        $form->handleRequest($request);
        if($form->isValid()){
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            /*     $this->addFlash('success','Welcome '.$user->getEmail().' to Iflora');

                 return $this->get('security.authentication.guard_handler')
                     ->authenticateUserAndHandleSuccess(
                         $user,
                         $request,
                         $this->get('app.security.login_form_authenticator'),
                         'main'
                     );
            */
            return $this->redirectToRoute('breeder-registered');
        }
        return $this->render('user/breeder-register.htm.twig', [
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/breeder/registered",name="breeder-registered")
     */
    public function breederRegisteredAction()
    {
        return $this->render('user/breeder-registered.htm.twig');
    }
    /**
     * @Route("/register/agent",name="agent_register")
     */
    public function registerAgentAction(Request $request)
    {
        $user = new User();
        $user->setUserType('agent');
        $user->setRoles(["ROLE_AGENT"]);
        $user->setIsActive(true);
        $user->setCurrency('KSH');

        $form = $this->createForm(UserRegistrationForm::class, $user);


        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            /*  $this->addFlash('success', 'Welcome ' . $user->getEmail() . ' to Iflora');

             return $this->get('security.authentication.guard_handler')
                  ->authenticateUserAndHandleSuccess(
                      $user,
                      $request,
                      $this->get('app.security.login_form_authenticator'),
                      'main'
                  );
             */
            return $this->redirectToRoute('agent-registered');
        }
        return $this->render('user/agent-register.htm.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/agent/registered",name="agent-registered")
     */
    public function agentRegisteredAction()
    {
        return $this->render('user/agent-registered.htm.twig');
    }
    /**
     * @Route("/forgot-password",name="password_restore")
     */
    public function forgotPasswordAction(){
        return $this->render('home.htm.twig');
    }
    /**
     * @Route("/",name="homepage")
     */
    public function homeAction(){
        return $this->render('home.htm.twig');
    }
    /**
     * @Route("/about",name="about")
     */
    public function aboutAction(){
        return $this->render('about.htm.twig');
    }
    /**
     * @Route("/contact",name="contact")
     */
    public function contactAction(){
        return $this->render('contact.htm.twig');
    }


}
