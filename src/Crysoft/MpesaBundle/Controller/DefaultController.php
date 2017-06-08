<?php

namespace Crysoft\MpesaBundle\Controller;

use AppBundle\Form\MpesaFormType;
use Crysoft\MpesaBundle\Helpers\Mpesa;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request=null)
    {
        $form = $this->createForm(MpesaFormType::class);
        //only handles data on POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $amount = $form["amount"]->getData();
            $phoneNumber = $form["phoneNumber"]->getData();

            $mpesa = new Mpesa($this->container);
            $response = $mpesa->request($amount)->from($phoneNumber)->usingReferenceId($mpesa->generateTransactionNumber())->transact();

            if ($response->getStatusCode()==200){

            }else{

            }

            var_dump($response);
        }
        return $this->render('CrysoftMpesaBundle:Default:index.html.twig',[
            'mpesaForm'=>$form->createView()
        ]);
    }
}
