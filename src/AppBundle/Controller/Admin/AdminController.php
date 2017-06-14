<?php
/**
 * Created by PhpStorm.
 * User: Mgeni
 * Date: 5/24/2017
 * Time: 3:53 PM
 */
namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Profile;
use AppBundle\Entity\User;
use AppBundle\Form\NewAdministratorForm;
use AppBundle\Form\ProfileReviewForm;
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
     * @Route("/",name="admin-home")
     */
    public function dashboardAction()
    {
        $em = $this->getDoctrine()->getManager();

        $nrOnboards = $em->getRepository("AppBundle:Onboard")
            ->findNrOnboards();
        $nrProfiles = $em->getRepository("AppBundle:Profile")
            ->findNrProfiles();
        $nrApproved = $em->getRepository("AppBundle:Profile")
            ->findNrApproved();
        $nrRejected = $em->getRepository("AppBundle:Profile")
            ->findNrRejected();
        $nrUnderReview = $em->getRepository("AppBundle:Profile")
            ->findNrUnderReview();
        $nrUnpaidProfiles = $em->getRepository("AppBundle:Profile")
            ->findNrUnpaidProfiles();
        $nrNew = $em->getRepository("AppBundle:Profile")
            ->findNrNew();
        $nrPendingAccounts = $em->getRepository("AppBundle:User")
            ->findNrPendingUsers();

        $openProfiles = $em->getRepository("AppBundle:Profile")
            ->findAllOpenProfilesOrderByDate();
        $unpaidProfiles = $em->getRepository("AppBundle:Profile")
            ->findAllUnpaidProfilesOrderByDate();
        $users = $em->getRepository("AppBundle:User")
            ->findAllUsers();
        $recordings = $em->getRepository("AppBundle:Recording")
            ->findAllRecordings();
        $pendingUsers = $em->getRepository("AppBundle:User")
            ->findAllPendingUsers();

        return $this->render('admin/dashboard.htm.twig',[
            'nrOnboards'=> $nrOnboards,
            'nrProfiles'=> $nrProfiles,
            'nrApproved' => $nrApproved,
            'nrRejected' => $nrRejected,
            'nrUnderReview'=> $nrUnderReview,
            'nrUnpaidProfiles'=>$nrUnpaidProfiles,
            'nrNew' => $nrNew,
            'openProfiles'=>$openProfiles,
            'unpaidProfiles' => $unpaidProfiles,
            'users'=>$users,
            'recordings'=>$recordings,
            'pendingAccounts'=>$pendingUsers,
            'nrPendingAccounts' => $nrPendingAccounts
        ]);
    }

    /**
     * @Route("/users/onboard/step1",name="new-users")
     */
    public function onBoardAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:Onboard")
            ->findAllUsers();

        return $this->render('admin/step-1-users.htm.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/users/onboard/step2",name="onboarded-users")
     */
    public function profileAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:Profile")
            ->findAllOpenProfilesOrderByDate();

        return $this->render('admin/step-2-users.htm.twig',[
            'users'=>$users
        ]);
    }

    /**
     * @Route("/profiles/approved",name="approved-users")
     */
    public function approvedProfileAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:Profile")
            ->findAllApprovedProfilesOrderByDate();

        return $this->render('admin/approved-users.htm.twig',[
            'users'=>$users
        ]);
    }
    /**
     * @Route("/profiles/pending",name="pending-accounts")
     */
    public function pendingProfileAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:User")
            ->findAllPendingUsers();

        return $this->render('admin/pending-accounts.htm.twig',[
            'users'=>$users
        ]);
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
     * @Route("/profiles/rejected",name="rejected-users")
     */
    public function rejectedProfileAction(){
        $em = $this->getDoctrine()->getManager();

        $users = $em->getRepository("AppBundle:Profile")
            ->findAllRejectedProfilesOrderByDate();

        return $this->render('admin/rejected-users.htm.twig',[
            'users'=>$users
        ]);
    }

    /**
     * @Route("/users/profile/{id}/review",name="review-profile")
     */
    public function reviewProfileAction(Request $request, Profile $profile){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(ProfileReviewForm::class);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $comment = $request->request->get('comment');
            $approval = $request->request->get('approval');

            if ($approval =="Approved"){
                $profile->setProfileStatus("Approved");
                $twigTemplate = "approved.htm.twig";
                $accountStatus = "Prisk Portal Profile Approved";
            }else{
                $profile->setProfileStatus("Rejected");
                $twigTemplate = "rejected.htm.twig";
                $accountStatus = "Prisk Portal Profile Status";
            }

            $profile->setStatusDescription($comment);
            $profile->setProcessedBy($user);
            $profile->setProcessedAt(new \DateTime());

            $em->persist($profile);
            $em->flush();

            $this->sendEmail($profile->getFirstName(),$accountStatus,$profile->getEmailAddress(),$twigTemplate,null);

            return $this->redirectToRoute('approved-users');
        }
        return $this->render('admin/profile/review.htm.twig',[
            'profile'=>$profile,
            'profileReviewForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/account/{id}/new",name="create-account")
     */
    public function createAccountAction(Request $request,Profile $profile){
       $admin = $this->get('security.token_storage')->getToken()->getUser();

       $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setIsActive(true);
        $user->setEmail($profile->getEmailAddress());
        $user->setFirstName($profile->getFirstName());
        $user->setLastName($profile->getLastName());
        $user->setIsPasswordCreated(false);
        $user->setMyProfile($profile);
        $user->setRoles(["ROLE_USER"]);
        $user->setPlainPassword($profile->getIdNumber());
        $user->setProfileLinkedAt(new \DateTime());
        $user->setAccountCreatedBy($admin);

        $profile->setAccountCreated("Created");

        $em->persist($profile);
        $em->persist($user);

        $em->flush();

        $this->sendEmail($profile->getFirstName(),"Your Prisk Portal Account",$profile->getEmailAddress(),"accountCreated.htm.twig",$user->getId());

        return new Response(null, 204);
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

        $user = new User();
        $user->setIsActive(true);
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPlainPassword(base64_encode(random_bytes(10)));
        $user->setAccountCreatedBy($admin);

        $form = $this->createForm(NewAdministratorForm::class,$user);

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()){
            $user=$form->getData();

            $em->persist($user);
            $em->flush();

            $this->sendEmail($user->getFirstName(),"Prisk Portal Administrator Account",$user->getEmail(),"accountCreated.htm.twig",$user->getId());

            return $this->redirectToRoute('admin-accounts');
        }

        return $this->render(':admin:new.htm.twig',[
           'adminForm'=>$form->createView()
        ]);
    }


    protected function sendEmail($firstName,$subject,$emailAddress,$twigTemplate,$code){
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            //->setFrom(null,'PRISK Online Portal Team')
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
