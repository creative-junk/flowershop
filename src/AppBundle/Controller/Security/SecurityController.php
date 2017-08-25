<?php

namespace AppBundle\Controller\Security;

use AppBundle\Entity\User;
use AppBundle\Form\AdministratorRegistrationForm;
use AppBundle\Form\ChangePasswordFormType;
use AppBundle\Form\ForgotPasswordFormType;
use AppBundle\Form\LoginForm;
use AppBundle\Form\ResetPasswordForm;
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
     * @Route("/login/admin",name="admin-login")
     *
     */
    public function loginAdminAction()
    {
        $formArray = $this->processLogin();

        return $this->render(
            'admin/login.htm.twig',
            array(
                'loginForm' => $formArray['form']->createView(),
                'error' => $formArray['error'],
            ));
    }

    /**
     * @Route("/login/buyer",name="user_login")
     *
     */
    public function loginUserAction()
    {
        $formArray = $this->processLogin();

        return $this->render(
            'user/login.htm.twig',
            array(
                'loginform' => $formArray['form']->createView(),
                'error' => $formArray['error'],
            ));
    }

    /**
     * @Route("login/grower",name="grower_login")
     *
     */
    public function loginGrowerAction()
    {

        $formArray = $this->processLogin();

        return $this->render(
            'user/login-grower.htm.twig',
            array(
                'loginform' => $formArray['form']->createView(),
                'error' => $formArray['error'],
            ));
    }

    /**
     * @Route("login/breeder",name="breeder_login")
     *
     */
    public function loginBreederAction()
    {
        $formArray = $this->processLogin();

        return $this->render(
            'user/login-breeder.htm.twig',
            array(
                'loginform' => $formArray['form']->createView(),
                'error' => $formArray['error'],
            ));
    }

    /**
     * @Route("login/agent",name="agent_login")
     *
     */
    public function loginAgentAction()
    {
        $formArray = $this->processLogin();

        return $this->render(
            'user/login-agent.htm.twig',
            array(
                'loginform' => $formArray['form']->createView(),
                'error' => $formArray['error'],
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

    /**
     * @Route("/account/admin/{id}/activate",name="user-activate-account")
     */
    public function adminFirstLoginAction(Request $request,User $user){
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ResetPasswordForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setIsPasswordCreated(true);
            $em->persist($user);
            $em->flush();

            return $this->render('user/adminAccountUpdated.htm.twig');
        }

        if ($user->getIsPasswordCreated()){
            $activated = true;
        }else{
            $activated = false;
        }

        return $this->render('user/adminActivate.htm.twig',[
            'user'=>$user,
            'activationForm'=>$form->createView(),
            'isActivated'=>$activated
        ]);
    }
    /**
     * @Route("/account/{code}/reset-password",name="user-reset-password")
     */
    public function resetPasswordAction(Request $request,$code){
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'passwordResetToken'=>$code
            ]);

        $form = $this->createForm(ResetPasswordForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $user->setIsResetTokenValid(false);
            $em->persist($user);
            $em->flush();

            return $this->render('user/passwordUpdated.htm.twig');
        }

        if ($user->getIsResetTokenValid()){
            $validToken = true;
        }else{
            $validToken = false;
        }

        return $this->render('user/reset-password.htm.twig',[
            'user'=>$user,
            'passwordResetForm'=>$form->createView(),
            'isTokenValid'=>$validToken
        ]);
    }


    /**
     * @Route("/account/request",name="request-admin-account")
     */
    public function requestAccountAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setIsActive(false);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setIsPasswordCreated(false);
        $user->setIsMainAccount(false);
        $myCompany = $em->getRepository("AppBundle:Company")
            ->findOneBy(
                [
                    'companyName'=>'Iflora'
                ]
            );
        $user->setMyCompany($myCompany);

        $form = $this->createForm(AdministratorRegistrationForm::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute("admin-account-requested");
        }
        return $this->render(':admin:register.htm.twig',[
            'registerForm'=>$form->createView()
        ]);

    }
    /**
     * @Route("/account/admin/requested",name="admin-account-requested")
     */
    public function accountCreatedAction(Request $request){
        return $this->render(':admin:created.htm.twig');
    }


    //Handle the Login Procedures
    protected function processLogin(){
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername
        ]);

        $formArray['form']=$form;
        $formArray['error']=$error;

        return $formArray;
    }
}
