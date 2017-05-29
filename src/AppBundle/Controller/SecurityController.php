<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;

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
     * @Route("/logout",name="security_logout")
     */
    public function logoutAction(){
        throw new \Exception('This should not be reached');
    }
}
