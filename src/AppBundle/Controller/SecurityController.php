<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\AdministratorRegistrationForm;
use AppBundle\Form\LoginForm;
use AppBundle\Form\NewAdministratorForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{


    /**
     * @Route("/login/",name="user_login")
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
                'loginForm' => $form->createView(),
                'error' => $error,
            ));
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
    /**
     * @Route("/login/admin",name="admin-login")
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
            'admin/login.htm.twig',
            array(
                'loginForm' => $form->createView(),
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
