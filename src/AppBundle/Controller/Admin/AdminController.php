<?php
/**
 * Created by PhpStorm.
 * User: Mgeni
 * Date: 5/24/2017
 * Time: 3:53 PM
 */
namespace AppBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        return $this->render('admin/dashboard.htm.twig');
    }

    /**
     * @Route("/users/onboard/step1",name="new-users")
     */
    public function onBoardAction(){
        return $this->render('admin/step-1-users.htm.twig');
    }

    /**
     * @Route("/users/onboard/step2",name="onboarded-users")
     */
    public function profileAction(){
        return $this->render('admin/step-2-users.htm.twig');
    }

    /**
     * @Route("/users/approved",name="approved-users")
     */
    public function userFunction(){
        return $this->render('admin/approved-users.htm.twig');
    }

}
