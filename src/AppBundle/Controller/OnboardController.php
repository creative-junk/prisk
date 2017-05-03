<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Onboard;
use AppBundle\Entity\Profile;
use AppBundle\Form\OnboardForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OnboardController extends Controller
{
    /**
     * @Route("/",name="home")
     */
    public function indexAction(Request $request)
    {
        $onboard = new Onboard();
        $onboard->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(OnboardForm::class,$onboard);
        $form->handleRequest($request);

        if($form->isValid()){
            $onboard = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($onboard);
            $em->flush();

            $this->sendWelcomeEmail($onboard->getfirstName(),$onboard->getEmail(),$onboard->getId());

            return $this->redirectToRoute('onboarded');
        }else{
            $errors = $form->getErrors();
        }

        return $this->render('onboard/onboard.htm.twig',[
            'onboardForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/{id/step2}",name="step2")
     */
    public function profileAction(Request $request,Onboard $onboard)
    {
        $profile = new Profile();
        $profile->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(OnboardForm::class,$onboard);
        $form->handleRequest($request);

        if($form->isValid()){
            $onboard = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($onboard);
            $em->flush();

            $this->sendWelcomeEmail($onboard->getfirstName(),$onboard->getEmail(),$onboard->getId());

            return $this->redirectToRoute('onboarded');
        }else{
            $errors = $form->getErrors();
        }

        return $this->render('onboard/onboard.htm.twig',[
            'onboardForm'=>$form->createView()
        ]);
    }
    /**
     * @Route("/onboard",name="onboard")
     */
    public function onboardAction(Request $request){
        $onboard = new Onboard();
        $form = $this->createForm(OnboardForm::class,$onboard);
        $form->handleRequest($request);

        if($form->isValid()){
            $onboard = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($onboard);
            $em->flush();
            return $this->redirectToRoute('onboarded');
        }else{
            $errors = $form->getErrors();
        }
        return $this->render('onboard/onboard.htm.twig',[
            'onboardForm'=>$form->createView(),
            'errors'=>$errors
        ]);
    }
    /**
     * @Route("/onboarded",name="onboarded")
     */
    public function onboardedAction(){
        return $this->render('onboard/onboarded.htm.twig');
    }

    public function sendWelcomeEmail(String $firstName,String $emailAddress,String $code){
        $message = \Swift_Message::newInstance()
            ->setSubject('PRISK Online Portal Registration')
            ->setFrom('prisk@creative-junk.com','PRISK Online Portal Registration')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/onboard.htm.twig
                    'Emails/onboard.htm.twig',
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
