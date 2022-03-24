<?php
// src/Controller/FiscalCodeController.php
namespace App\Controller;

use App\Entity\FiscalData;
use App\Form\FiscalDataType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;


class FiscalCodeController extends AbstractController
{
    #[Route('/fiscal/code', name:"FiscalCode")]   //http://127.0.0.1:8000/fiscal/code 
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager) {
        
        $code = new FiscalData();
        $form = $this->createForm(FiscalDataType::class, $code);
        //Request to handle the submission of the form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        //EntityManagerInterface = DB
            //$entityManager->persist( $code ); //save
            $entityManager->flush();    //synchronizes the in-memory state of managed objects with the database.

            return new Response($twig->render('fiscal/code.html.twig', [
                'fiscal_form' => $form->createView(),
                'valid' => $code->getId(),
            ]));
        }

        return new Response($twig->render('fiscal/code.html.twig', [
            'fiscal_form' => $form->createView(),
            'valid' => '',
        ]));
    }
}

?>