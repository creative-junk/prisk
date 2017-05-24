<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recording;
use AppBundle\Form\RecordingForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    /**
     * @Route("/home",name="home")
     */
    public function dashboardAction(){
        return $this->render('home/home.htm.twig');
    }
    /**
     * @Route("/home/recordings",name="my-recordings")
     */
    public function myRecordings(Request $request){

        $user = $this->get('security.token_storage')->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $queryBuilder = $em->getRepository('AppBundle:Recording')
            ->createQueryBuilder('recording')
            ->andWhere('recording.createdBy = :createdBy')
            ->setParameter('createdBy', $user);

        $query = $queryBuilder->getQuery();
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 9)
        );
        return $this->render('home/recording/list.html.twig', [
            'recordings' => $result,
        ]);
    }
    /**
     * @Route("/home/recording/new",name="add-recording")
     */
    public function addRecordingAction(Request $request){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $recording = new Recording();
        $recording->setMainArtist($user);
        $recording->setCreatedBy($user);
        $recording->setCreatedAt(new \DateTime());
        $recording->setUpdatedBy($user);
        $recording->setUpdatedAt(new \DateTime());
        $form = $this->createForm(RecordingForm::class, $recording);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recording = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($recording);
            $em->flush();

            $this->addFlash('success', 'Product Updated Successfully!');

            return $this->redirectToRoute('my-recordings');
        }

        return $this->render('home/recording/new.html.twig', [
            'recordingForm' => $form->createView()
        ]);

    }
    /**
     * @Route("/home/recording/{id}/edit",name="edit-recording")
     */
    public function editRecordingAction(Request $request,Recording $recording){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $recording->setUpdatedBy($user);
        $recording->setUpdatedAt(new \DateTime());
        $form = $this->createForm(RecordingForm::class, $recording);

        //only handles data on POST
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $recording = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($recording);
            $em->flush();

            $this->addFlash('success', 'Recording Updated Successfully!');

            return $this->redirectToRoute('my-recordings');
        }

        return $this->render('home/recording/update.html.twig', [
            'recordingForm' => $form->createView()
        ]);

    }
    /**
     * @Route("/home/recording/view/{id}",name="view-recording")
     */
    public function viewRecordingAction(Request $request,Recording $recording){
        $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('home/recording/details.htm.twig', [
            'recording' => $recording,


        ]);

    }

}
