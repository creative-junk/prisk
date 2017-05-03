<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/login",name="login")
     */
    public function loginAction(){
        return $this->render('onboard/onboarded.htm.twig');
    }
}
