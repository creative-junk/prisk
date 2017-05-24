<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Onboard;
use AppBundle\Entity\Profile;
use AppBundle\Form\ProfileForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/updated",name="profile_updated")
     */
    public function profileCompleteAction(){
        return $this->render('profile/updated.htm.twig');
    }
    /**
     * @Route("/profile/{id}/update")
     */
    public function profileAction(Request $request,Onboard $onboard)
    {
        $profile = new Profile();
        $profile->setFirstName($onboard->getFirstName());
        $profile->setLastName($onboard->getLastName());
        $profile->setIdNumber($onboard->getIdNumber());
        $profile->setStageName($onboard->getStageName());
        $profile->setEmailAddress($onboard->getEmail());

        $profile->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(ProfileForm::class,$profile);
        $form->handleRequest($request);

        if($form->isValid()){
            $profile = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();

            $this->sendWelcomeEmail($onboard->getfirstName(),$onboard->getEmail(),$onboard->getId());

            return $this->redirectToRoute('profile_updated',array(
                'profileId'=>$profile->getId()
            ));
        }else{
            $errors = $form->getErrors();
        }

        return $this->render(':profile:new.htm.twig',[
            'profileForm'=>$form->createView(),
            'profile'=>$profile,
            'errors'=>$errors
        ]);
    }
    /**
     * @Route("/profile/{id}/edit")
     */
    public function editProfileAction(Request $request,Profile $profile)
    {
        $form = $this->createForm(ProfileForm::class,$profile);
        $form->handleRequest($request);

        if($form->isValid()){
            $profile = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();


            return $this->redirectToRoute('profile_updated',array(
                'profileId'=>$profile->getId()
            ));
        }else{
            $errors = $form->getErrors();
        }

        return $this->render(':profile:new.htm.twig',[
            'profileForm'=>$form->createView(),
            'profile'=>$profile,
            'errors'=>$errors
        ]);
    }
    /**
     * @Route("/profile/{id}/pay")
     */
    public function makePaymentAction(Request $request,Profile $profile)
    {
        $form = $this->createForm(ProfileForm::class,$profile);
        $form->handleRequest($request);

        if($form->isValid()){
            $profile = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();

            $this->sendWelcomeEmail($profile->getfirstName(),$profile->getEmail(),$profile->getId());

            return $this->redirectToRoute('profile_updated',array(
                'profileId'=>$profile->getId()
            ));
        }else{
            $errors = $form->getErrors();
        }

        return $this->render(':profile:new.htm.twig',[
            'profileForm'=>$form->createView(),
            'profile'=>$profile,
            'errors'=>$errors
        ]);
    }
    public function sendWelcomeEmail($firstName,$emailAddress,$code){
        $message = \Swift_Message::newInstance()
            ->setSubject('PRISK Online Portal Profile')
            ->setFrom('prisk@creative-junk.com','PRISK Online Portal Team')
            ->setTo($emailAddress)
            ->setBody(
                $this->renderView(
                // app/Resources/views/Emails/onboard.htm.twig
                    'Emails/profile.htm.twig',
                    array(
                        'name' => $firstName
                    )
                ),
                'text/html'
            );
        $this->get('mailer')->send($message);
    }
}
