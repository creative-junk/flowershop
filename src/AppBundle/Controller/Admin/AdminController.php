<?php
/*********************************************************************************
 * Karbon Framework is a PHP5 Framework developed by Maxx Ng'ang'a
 * (C) 2016 Crysoft Dynamics Ltd
 * Karbon V 1.0
 * Maxx
 * 4/14/2017
 ********************************************************************************/

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Company;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Form\CategoryFormType;
use AppBundle\Form\NewAdministratorForm;
use AppBundle\Form\ProductFormType;
use AppBundle\Form\UserFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/admin")
 * @Security("is_granted('ROLE_ADMIN')")
 *
 */
class AdminController extends Controller
{
    /**
     * @Route("/",name="admin_dashboard")
     */
    public function listAction(){

        $em = $this->getDoctrine()->getManager();

        $nrOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrOrders();
        $nrUsers = $em->getRepository('AppBundle:Company')
            ->getNrUsers();
        $nrBuyers = $em->getRepository('AppBundle:Company')
            ->getNrBuyers();
        $nrAgents = $em->getRepository('AppBundle:Company')
            ->getNrAgents();
        $nrGrowers = $em->getRepository('AppBundle:Company')
            ->getNrGrowers();
        $nrBreeders = $em->getRepository('AppBundle:Company')
            ->getNrBreeders();

        $nrChangeOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrChangeOrders();
        $nrChangeUsers = $em->getRepository('AppBundle:Company')
            ->getNrChangeUsersThisWeek();
        $nrChangeBuyers = $em->getRepository('AppBundle:Company')
            ->getNrChangeBuyersThisWeek();
        $nrChangeAgents = $em->getRepository('AppBundle:Company')
            ->getNrChangeAgentsThisWeek();
        $nrChangeGrowers = $em->getRepository('AppBundle:Company')
            ->getNrChangeGrowersThisWeek();
        $nrChangeBreeders = $em->getRepository('AppBundle:Company')
            ->getNrChangeBreedersThisWeek();
        if ($nrOrders >0) {
            $percentChangeOrders = ($nrChangeOrders / $nrOrders) * 100;
        }else{
            $percentChangeOrders='0';
        }
        if ($nrUsers >0){
        $percentChangeUsers = ($nrChangeUsers/$nrUsers)*100;
        }else{
            $percentChangeUsers='0';
        }
        if ($nrBuyers >0){
        $percentChangeBuyers = ($nrChangeBuyers/$nrBuyers)*100;
        }else{
            $percentChangeBuyers='0';
        }
        if ($nrAgents >0){
        $percentChangeAgents = ($nrChangeAgents/$nrAgents)*100;
        }else{
            $percentChangeAgents='0';
        }
        if ($nrGrowers >0){
        $percentChangeGrowers = ($nrChangeGrowers/$nrGrowers)*100;
        }else{
            $percentChangeGrowers='0';
        }
        if ($nrBreeders >0) {
            $percentChangeBreeders = ($nrChangeBreeders / $nrBreeders) * 100;
        }else{
            $percentChangeBreeders='0';
        }
        return $this->render(':admin:dashboard.htm.twig',[
            'nrUsers'=>$nrUsers,
            'nrOrders'=>$nrOrders,
            'nrBuyers' =>$nrBuyers,
            'nrAgents' => $nrAgents,
            'nrGrowers' => $nrGrowers,
            'nrBreeders' => $nrBreeders,
            'percentChangeUsers'=>$percentChangeUsers,
            'percentChangeOrders'=>$percentChangeOrders,
            'percentChangeBuyers'=>$percentChangeBuyers,
            'percentChangeAgents'=>$percentChangeAgents,
            'percentChangeGrowers'=>$percentChangeGrowers,
            'percentChangeBreeders'=>$percentChangeBreeders,

        ]);


    }
    /**
     * @Route("/",name="admin-home")
     */
    public function adminHomeAction(){

        $em = $this->getDoctrine()->getManager();

        $nrOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrOrders();
        $nrUsers = $em->getRepository('AppBundle:Company')
            ->getNrUsers();
        $nrBuyers = $em->getRepository('AppBundle:Company')
            ->getNrBuyers();
        $nrAgents = $em->getRepository('AppBundle:Company')
            ->getNrAgents();
        $nrGrowers = $em->getRepository('AppBundle:Company')
            ->getNrGrowers();
        $nrBreeders = $em->getRepository('AppBundle:Company')
            ->getNrBreeders();

        $nrChangeOrders = $em->getRepository('AppBundle:UserOrder')
            ->findNrChangeOrders();
        $nrChangeUsers = $em->getRepository('AppBundle:Company')
            ->getNrChangeUsersThisWeek();
        $nrChangeBuyers = $em->getRepository('AppBundle:Company')
            ->getNrChangeBuyersThisWeek();
        $nrChangeAgents = $em->getRepository('AppBundle:Company')
            ->getNrChangeAgentsThisWeek();
        $nrChangeGrowers = $em->getRepository('AppBundle:Company')
            ->getNrChangeGrowersThisWeek();
        $nrChangeBreeders = $em->getRepository('AppBundle:Company')
            ->getNrChangeBreedersThisWeek();
        if ($nrOrders >0) {
            $percentChangeOrders = ($nrChangeOrders / $nrOrders) * 100;
        }else{
            $percentChangeOrders='0';
        }
        if ($nrUsers >0){
            $percentChangeUsers = ($nrChangeUsers/$nrUsers)*100;
        }else{
            $percentChangeUsers='0';
        }
        if ($nrBuyers >0){
            $percentChangeBuyers = ($nrChangeBuyers/$nrBuyers)*100;
        }else{
            $percentChangeBuyers='0';
        }
        if ($nrAgents >0){
            $percentChangeAgents = ($nrChangeAgents/$nrAgents)*100;
        }else{
            $percentChangeAgents='0';
        }
        if ($nrGrowers >0){
            $percentChangeGrowers = ($nrChangeGrowers/$nrGrowers)*100;
        }else{
            $percentChangeGrowers='0';
        }
        if ($nrBreeders >0) {
            $percentChangeBreeders = ($nrChangeBreeders / $nrBreeders) * 100;
        }else{
            $percentChangeBreeders='0';
        }
        return $this->render(':admin:dashboard.htm.twig',[
            'nrUsers'=>$nrUsers,
            'nrOrders'=>$nrOrders,
            'nrBuyers' =>$nrBuyers,
            'nrAgents' => $nrAgents,
            'nrGrowers' => $nrGrowers,
            'nrBreeders' => $nrBreeders,
            'percentChangeUsers'=>$percentChangeUsers,
            'percentChangeOrders'=>$percentChangeOrders,
            'percentChangeBuyers'=>$percentChangeBuyers,
            'percentChangeAgents'=>$percentChangeAgents,
            'percentChangeGrowers'=>$percentChangeGrowers,
            'percentChangeBreeders'=>$percentChangeBreeders,

        ]);


    }
    /**
     * @Route("/users",name="user_list")
     */
    public function listUserAction(){


        $em=$this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('admin/user/list.htm.twig',[
            'users'=>$users,
        ]);
        //dump($products);die;
        //return new Response('Product Saved');
    }
    /**
     * @Route("/user/new",name="new_user")
     */
    public function newUserAction(Request $request)
    {

        $form = $this->createForm(UserFormType::class);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','User Created, Yaay!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/new.html.twig',[
            'productForm' => $form->createView()
        ]);
    }
    /**
     * @Route("/user/{id}/edit",name="user_edit")
     */
    public function editAction(Request $request,User $user)
    {
        $form = $this->createForm(UserFormType::class,$user);

        //only handles data on POST
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success','Product Updated, Yaay!');

            return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/user/edit.html.twig',[
            'productForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/orders/",name="admin_order_list")
     */
    public function ordersListAction(){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $em=$this->getDoctrine()->getManager();
        $orders = $em->getRepository('AppBundle:UserOrder')
            ->findAllUserOrdersOrderByDate();
        return $this->render('admin/order/list.html.twig',[
            'orders'=>$orders,
        ]);

    }
    /**
     * @Route("/profile",name="my_profile")
     */
    public function profileAction(){

    }
    /**
     * @Route("/settings",name="app_settings")
     */
    public function settingsAction(){

    }
    /**
     * @Route("/categories",name="category_list")
     */
    public function categoryAction(){
        $em=$this->getDoctrine()->getManager();
        $categories = $em->getRepository('AppBundle:Category')
            ->findAll();

        return $this->render(':partials:categories.html.twig',[
            'categoryList'=>$categories,
        ]);
    }
    /**
     * @Route("/companies/pending",name="pending-company-accounts")
     */
    public function pendingCompanyAction(){
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository("AppBundle:Company")
            ->findAllPendingCompanyAccounts();

        return $this->render('admin/company/pending-accounts.htm.twig',[
            'companies'=>$companies
        ]);
    }
    /**
     * @Route("/companies/approved",name="approved-company-accounts")
     */
    public function approvedCompaniesAction(){
        $em = $this->getDoctrine()->getManager();

        $companies = $em->getRepository("AppBundle:Company")
            ->findAllActiveCompanyAccounts();

        return $this->render('admin/company/approved-accounts.htm.twig',[
            'companies'=>$companies
        ]);
    }
    /**
     * @Route("/company/account/{id}/approve",name="approve-company-account")
     */
    public function approveCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(true);

        $company->setApprovedBy($admin);

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Approved",$company->getEmail(),"companyApproved.htm.twig",$company->getId());

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/activate",name="activate-company-account")
     */
    public function activateCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(true);

        $company->setUpdatedBy($admin);
        $company->setCreatedAt(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Activated",$company->getEmail(),"companyActivated.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/suspend",name="suspend-company-account")
     */
    public function suspendCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(false);

        $company->setUpdatedBy($admin);
        $company->setCreatedAt(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Suspended",$company->getEmail(),"companySuspended.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/company/account/{id}/reject",name="reject-company-account")
     */
    public function rejectCompanyAccountAction(Request $request, Company $company){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $company->setIsActive(false);
        $company->setIsPaid(false);

        $company->setApprovedBy($admin);
        $company->setApprovedOn(new \DateTime());

        $em->persist($company);
        $em->flush();

        $this->sendEmail($company->getCompanyName(),"Company Account Denied",$company->getEmail(),"companyDenied.htm.twig",null);

        return new Response(null,204);
    }
    /**
     * @Route("/users/",name="user-accounts")
     */
    public function userAccountsAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllUsers();

        return $this->render('admin/user/users.htm.twig',[
            'users'=>$users
        ]);
    }

    /**
     * @Route("/user/account/{id}/activate",name="activate-user-account")
     */
    public function activateUserAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(true);

        $user->setUpdatedBy($admin);
        $user->setUpdatedAt(new \DateTime());

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"User Account Activated",$user->getEmail(),"userActivated.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/user/account/{id}/suspend",name="suspend-user-account")
     */
    public function suspendUserAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"User Account Suspended",$user->getEmail(),"userSuspended.htm.twig",null);

        return new Response(null,204);
    }

    /**
     * @Route("/users/admin/pending",name="pending-admin-accounts")
     */
    public function pendingAdminAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllPendingAdminUsers();

        return $this->render('admin/pending-admin-accounts.htm.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/users/administrators",name="admin-accounts")
     */
    public function adminAccountsAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllAdministratorUsers();

        return $this->render('admin/admin-users.htm.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/user/account/{id}/approve",name="approve-admin-account")
     */
    public function approveAccountAction(Request $request, User $user){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $user->setIsActive(true);
        $user->setIsPasswordCreated(true);
        $user->setApprovedBy($admin);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Administrator Account Approved",$user->getEmail(),"accountApproved.htm.twig",null);

        return new Response(null,204);
    }
    /**
     * @Route("/administrator/new",name="new-administrator")
     */
    public function newAdministratorAction(Request $request){
        $admin = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $accountToken = base64_encode(random_bytes(10));

        $user = new User();
        $user->setIsActive(true);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPlainPassword(base64_encode(random_bytes(10)));
        $user->setAccountCreatedBy($admin);
        $user->setPasswordResetToken($accountToken);

        $form = $this->createForm(NewAdministratorForm::class,$user);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $user=$form->getData();

            $em->persist($user);
            $em->flush();

            $this->sendEmail($user->getFirstName(),"Iflora Portal Administrator Account",$user->getEmail(),"accountCreated.htm.twig",$accountToken);

            return $this->redirectToRoute('admin-accounts');
        }

        return $this->render(':admin:new.htm.twig',[
            'adminForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/user/account/{id}/deactivate",name="deactivate-account")
     */
    public function deactivateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setIsActive(false);

        $em->persist($user);
        $em->flush();

        return new Response(null,204);
    }
    /**
     * @Route("/user/account/{id}/activate",name="activate-account")
     */
    public function activateAccountAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setPasswordResetToken($resetToken);
        $user->setIsResetTokenValid(true);
        $user->setIsActive(true);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Password Reset",$user->getEmail(),"passwordReset.htm.twig",$resetToken);

        return new Response(null,204);
    }

    /**
     * @Route("/user/account/{id}/reset",name="request-password-reset")
     */
    public function requestPasswordResetAction(Request $request, User $user){

        $em = $this->getDoctrine()->getManager();

        $resetToken = base64_encode(random_bytes(10));

        $user->setPlainPassword($resetToken."12");
        $user->setPasswordResetToken($resetToken);
        $user->setIsResetTokenValid(true);

        $em->persist($user);
        $em->flush();

        $this->sendEmail($user->getFirstName(),"Password Reset",$user->getEmail(),"passwordReset.htm.twig",$resetToken);

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

    /**
     * @Route("/member/update",name="update-member")
     */
    public function updateRoleFunction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $memberId = $request->request->get('pk');
        $roleValue = $request->request->get('value');

        switch ($roleValue) {
            case 1:
                $role = ["ROLE_MEMBERSHIP"];
                break;
            case 2:
                $role = ["ROLE_MUSIC_DIRECTOR"];
                break;
            case 3:
                $role = ["ROLE_ACTOR_DIRECTOR"];
                break;
            case 4:
                $role = ["ROLE_ADMINISTRATOR"];
                break;
            default:
                $role = ["ROLE_MEMBERSHIP"];
                break;
        }

        $member = $em->getRepository("AppBundle:User")
            ->findOneBy([
                'id'=>$memberId
            ]);

        if ($member){
            $member->setRoles($role);
            $em->persist($member);
            $em->flush();
            return new Response(null,200);
        }else{
            return new Response(null,500);
        }
    }

}