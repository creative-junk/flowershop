<?php

namespace AppBundle\Controller;

use AppBundle\Form\ChangePasswordFormType;
use AppBundle\Form\ForgotPasswordFormType;
use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login",name="security_login")
     *
     */
    public function loginAction()
    {
        return $this->render('home.htm.twig');
    }
    /**
     * @Route("/login/admin",name="admin_login")
     *
     */
    public function loginAdminAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'user/admin-login.htm.twig',
            array(
                'buyerform' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("/login/buyer",name="user_login")
     *
     */
    public function loginUserAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class,[
            '_username' => $lastUsername
        ]);

        return $this->render(
            'user/login.htm.twig',
            array(
                'buyerform' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("login/grower",name="grower_login")
     *
     */
    public function loginGrowerAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render(
            'user/grower-login.htm.twig',
            array(
                'loginform' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("login/breeder",name="breeder_login")
     *
     */
    public function loginBreederAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render(
            'user/breeder-login.htm.twig',
            array(
                'loginform' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("login/agent",name="agent_login")
     *
     */
    public function loginAgentAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        return $this->render(
            'user/agent-login.htm.twig',
            array(
                'loginform' => $form->createView(),
                'error' => $error,
            ));
    }

    /**
     * @Route("forgot-password",name="forgot-password")
     */
    public function forgotPasswordAction(Request $request=null){
        $form = $this->createForm(ForgotPasswordFormType::class);
        $error="";
        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email =$form["_username"]->getData();
           // var_dump($email);exit;
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')
                ->findOneBy([
                    'email'=>$email
                ]);

            if ($user){
                $this->container->get('session')->set('user', $user);
                return $this->redirectToRoute('change-password');
            }else{
                $error="Invalid User";
            }

        }
        return $this->render('user/forgot-password.htm.twig',[
            'buyerform'=> $form->createView(),
            'error'=>$error
        ]);
    }

    /**
     * @Route("/change-password",name="change-password")
     */
    public function changePasswordAction(Request $request=null){
        $owner = $this->container->get('session')->get('user');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy([
                'id'=>$owner->getId()
            ]);

        $form = $this->createForm(ChangePasswordFormType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('password-changed');

        }
        return $this->render('user/change-password.htm.twig',[
            'form'=>$form->createView(),

        ]);
    }

    /**
     * @Route("/password-changed",name="password-changed")
     */
    public function passwordChangedAction(){
        return $this->render(':user:password-changed.htm.twig');
    }
    /**
     * @Route("/logout",name="security_logout")
     */
    public function logoutAction(){
        throw new \Exception('This should not be reached');
    }
}
