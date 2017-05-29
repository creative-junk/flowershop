<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/18/2017
 ********************************************************************************/

namespace AppBundle\Security;


use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{

    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    private $redirectFailureUrl;
    private $redirectSuccessUrl;

    /**
     * LoginFormAuthenticator constructor.
     */
    public function __construct(FormFactoryInterface $formFactory, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, $redirectFailureUrl = null, $redirectSuccessUrl = null)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->redirectFailureUrl = $redirectFailureUrl;
        $this->redirectSuccessUrl = $redirectSuccessUrl;
    }

    public function getCredentials(Request $request)
    {
        $isLoginSubmit = ($request->getPathInfo() == '/login/buyer' || $request->getPathInfo() == '/login/grower' || $request->getPathInfo() == '/login/breeder' || $request->getPathInfo() == '/login/agent') && $request->isMethod('POST');
        // $isGrowerLoginSubmit = $request->getPathInfo()=='/login/grower' && $request->isMethod('POST');

        if(!$isLoginSubmit){
            return;
        }
        if ($request->request->get('_failure_path')) {
            $this->redirectFailureUrl = $request->request->get('_failure_path');
        }else{
            $this->redirectFailureUrl ='login';
        }
        $this->redirectSuccessUrl = $request->request->get('_target_path');

        $form = $this->formFactory->create(LoginForm::class);
        $form->handleRequest($request);
        $data = $form->getData();

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
       $username = $credentials['_username'];

        return $this->em->getRepository('AppBundle:User')
            ->findOneBy(['email'=> $username]);

    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];

        /*if($this->passwordEncoder->isPasswordValid($user,$password)){
            return true;
        }
        return false;*/
        return true;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate($this->redirectFailureUrl);
    }
    protected function getDefaultSuccessRedirectUrl(){

        return $this->router->generate($this->redirectSuccessUrl);
    }

}