<?php

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Form\CompanyCodeFormType;
use AppBundle\Form\CompanyRegistrationForm;
use AppBundle\Form\UserRegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use ReCaptcha\ReCaptcha;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/register/buyer",name="register-buyer")
     */
    public function registerBuyerAction(Request $request)
    {
        //Create a Form Array or Handle the Submitted Request
        $formArray = $this->registerCompany($request, 'Buyer');
        if (empty($formArray)){
           return $this->redirectToRoute('company-registered');
        }
        return $this->render('user/register-buyer.htm.twig',
            [
                'form' => $formArray['form']->createView(),
                'error' => $formArray['error'],
                'errorMessage' => $formArray['errorMessage']

            ]
        );
    }

    /**
     * @Route("/register/grower",name="register-grower")
     */
    public function registerGrowerAction(Request $request)
    {
        //Create a Form Array or Handle the Submitted Request
        $formArray = $this->registerCompany($request, 'Grower');
        if (empty($formArray)){
           return $this->redirectToRoute('company-registered');
        }
        return $this->render('user/register-grower.htm.twig',
            [
                'form' => $formArray['form']->createView(),
                'error' => $formArray['error'],
                'errorMessage' => $formArray['errorMessage']
            ]
        );
    }

    /**
     * @Route("/register/breeder",name="register-breeder")
     */
    public function registerBreederAction(Request $request)
    {
        //Create a Form Array or Handle the Submitted Request
        $formArray = $this->registerCompany($request, 'Breeder');
        if (empty($formArray)){
           return $this->redirectToRoute('company-registered');
        }
        return $this->render('user/register-breeder.htm.twig', ['form' => $formArray['form']->createView(), 'error' => $formArray['error'], 'errorMessage' => $formArray['errorMessage']]);
    }

    /**
     * @Route("/register/agent",name="register-agent")
     */
    public function registerAgentAction(Request $request)
    {
        //Create a Form Array or Handle the Submitted Request
        $formArray = $this->registerCompany($request, 'Agent');
        if (empty($formArray)){
          return  $this->redirectToRoute('company-registered');
        }
        return $this->render('user/register-agent.htm.twig', ['form' => $formArray['form']->createView(), 'error' => $formArray['error'], 'errorMessage' => $formArray['errorMessage']]);
    }

    /**
     * @Route("/registered",name="company-registered")
     */
    public function companyRegisteredAction()
    {
        return $this->render('user/user-registered.htm.twig');
    }
    /**
     * @Route("/registered/active",name="company-active")
     */
    public function companyActiveAction()
    {
        return $this->render('user/user-active.htm.twig');
    }

    /**
     * @Route("/company/user/register",name="register-user")
     */
    public function registerUserAction(Request $request)
    {
        $form = $this->createForm(CompanyCodeFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $companyCode = $request->request->get('companyCode');
            $user = $em->getRepository("AppBundle:Company")->findOneBy(['companyCode' => $companyCode]);
            if ($user) {
                // return $this->redirectToRoute('new-user',['id'=>$user->getId()]);
                $route = $this->generateUrl('new-user', ['id' => $user->getId()]);
                return new Response($route, 200);
            } else {
                return new Response(null, 500);
            }
        }
        return $this->render('user/user-register-code.htm.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/company/{id}/new-user",name="new-user")
     */
    public function newUserAction(Request $request, Company $company)
    {
        $error = false;
        $errorMessage = '';
        $user = new User();
        $role = $company->getCompanyType();
        if ($role == "Buyer") {
            $user->setRoles(["ROLE_BUYER"]);
        } elseif ($role == "Grower") {
            $user->setRoles(["ROLE_GROWER"]);
        } elseif ($role == "Breeder") {
            $user->setRoles(["ROLE_BREEDER"]);
        } elseif ($role == "Agent") {
            $user->setRoles(["ROLE_AGENT"]);
        }
        $user->setIsActive(false);
        $user->setMyCompany($company);
        $user->setIsMainAccount(false);

        $form = $this->createForm(UserRegistrationForm::class, $user);

        $form->handleRequest($request);

        // Verify the Recaptcha
        $recaptcha = new ReCaptcha('6LdU2CkUAAAAAN9xccXst7YbBiyqMp1_h1WV0wB0');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        //But only if the form is submitted
        if ($form->isSubmitted() && !$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $error = true;
            $errorMessage = "Verification Failed. The reCAPTCHA wasn't entered correctly.";
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('company-registered');
            }
        }
        return $this->render(':user:register-user.htm.twig', ['form' => $form->createView(), 'company' => $company, 'error' => $error, 'errorMessage' => $errorMessage]);
    }
    /**
     * @Route("/company/{id}/new-manager-user",name="new-manager-user")
     */
    public function newManagerUserAction(Request $request, Company $company)
    {
        $error = false;
        $errorMessage = '';
        $user = new User();
        $role = $company->getCompanyType();
        if ($role == "Buyer") {
            $user->setRoles(["ROLE_BUYER"]);
        } elseif ($role == "Grower") {
            $user->setRoles(["ROLE_GROWER"]);
        } elseif ($role == "Breeder") {
            $user->setRoles(["ROLE_BREEDER"]);
        } elseif ($role == "Agent") {
            $user->setRoles(["ROLE_AGENT"]);
        }
        $user->setIsActive(true);
        $user->setMyCompany($company);
        $user->setIsMainAccount(true);

        $form = $this->createForm(UserRegistrationForm::class, $user);

        $form->handleRequest($request);

        // Verify the Recaptcha
        $recaptcha = new ReCaptcha('6LdU2CkUAAAAAN9xccXst7YbBiyqMp1_h1WV0wB0');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());

        //But only if the form is submitted
        if ($form->isSubmitted() && !$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $error = true;
            $errorMessage = "Verification Failed. The reCAPTCHA wasn't entered correctly.";
        } else {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $company->setIsFirstLogin(true);
                $em->persist($user);
                $em->persist($company);
                $em->flush();
                return $this->redirectToRoute('company-active');
            }
        }
        return $this->render(':user:register-user.htm.twig', ['form' => $form->createView(), 'company' => $company, 'error' => $error, 'errorMessage' => $errorMessage]);
    }

    /**
     * @Route("/forgot-password",name="password_restore")
     */
    public function forgotPasswordAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if ($user){
            $this->redirectToRoute("user_logout");
        }
        return $this->render('home.htm.twig');
    }

    /**
     * @Route("/",name="homepage")
     */
    public function homeAction()
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
       // var_dump($user);exit;
        if ($user && $user != "anon."){
            $company = $user->getMyCompany();
            $role = $company->getCompanyType();
            if ($role == "Buyer") {
                return $this->redirectToRoute("home");
            } elseif ($role == "Grower") {
                return $this->redirectToRoute("grower_dashboard");
            } elseif ($role == "Breeder") {
                return $this->redirectToRoute("breeder_dashboard");
            } elseif ($role == "Agent") {
                return $this->redirectToRoute("agent_dashboard");
            }
        }
        return $this->render('home.htm.twig');
    }

    /**
     * @Route("/about",name="about")
     */
    public function aboutAction()
    {
        return $this->render('about.htm.twig');
    }

    /**
     * @Route("/contact",name="contact")
     */
    public function contactAction()
    {
        return $this->render('contact.htm.twig');
    }

    private function generateCode()
    {
        $unique = FALSE;
        $length = 7;
        $chrDb = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $str = '';
        while (!$unique) {
            for ($count = 0; $count < $length; $count++) {
                $chr = $chrDb[rand(0, count($chrDb) - 1)];
                if (rand(0, 1) == 0) {
                    $chr = strtolower($chr);
                }
                $str .= $chr;
            }
            /* check if unique */
            $em = $this->getDoctrine()->getManager();
            $existingCode = $em->getRepository("AppBundle:Company")->findOneBy(['companyCode' => $str]);
            if (!$existingCode) {
                $unique = TRUE;
            }
        }
        return $str;
    }

    protected function registerCompany(Request $request, $type)
    {
        $error = false;
        $errorMessage = '';
        $company = new Company();
        $company->setCompanyCode($this->generateCode());
        $company->setCompanyType($type);
        $company->setStatus("Pending");
        $company->setIsActive(false);
        $form = $this->createForm(CompanyRegistrationForm::class, $company);
        $form->handleRequest($request);
        // Verify the Recaptcha
        $recaptcha = new ReCaptcha('6LdU2CkUAAAAAN9xccXst7YbBiyqMp1_h1WV0wB0');
        $resp = $recaptcha->verify($request->request->get('g-recaptcha-response'), $request->getClientIp());
        //But only if the form is submitted
        if ($form->isSubmitted() && !$resp->isSuccess()) {
            // Do something if the submit wasn't valid ! Use the message to show something
            $error = true;
            $errorMessage = "Verification Failed. The reCAPTCHA wasn't entered correctly.";
        } else if($form->isSubmitted() && $resp->isSuccess()) {
            if ($form->isSubmitted() && $form->isValid()) {
                /** @var User $user */
                $user = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $formArray=[];
            }
        }
        $formArray['form'] = $form;
        $formArray['error'] = $error;
        $formArray['errorMessage'] = $errorMessage;
        return $formArray;
    }
    /**
     * @Route("/logout",name="user_logout")
     */
    public function logoutAction(){
        throw new \Exception('This should not be reached');
    }
    /**
     * @Route("/account/user/{id}/deactivate",name="deactivate-user-account")
     */
    public function deactivateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();
        $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        return new Response(null,204);
    }
    /**
     * @Route("/account/user/{id}/activate",name="activate-user-account")
     */
    public function activateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setIsActive(true);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Account Activated",$user->getEmail(),"userAccountApproved.htm.twig",$resetToken);

        return new Response(null,204);
    }
    protected function sendEmail($firstName,$subject,$emailAddress,$twigTemplate,$code){
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('iflora@iflora.biz','Iflora Team')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                    'Emails/'.$twigTemplate,
                    array(
                        'name' => $firstName,
                        'code' => $code
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
