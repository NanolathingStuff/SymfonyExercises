<?php
// src/Controller/FiscalCodeController.php
namespace App\Controller;

use App\Files\FiscalCode;   //custom class to calculate code
use App\Entity\FiscalData;
use App\Form\FiscalDataType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;


class FiscalCodeController extends AbstractController{

    #[Route('/fiscal/code', name:"FiscalCode")]   //http://127.0.0.1:8000/fiscal/code 
    public function show(Environment $twig, Request $request, EntityManagerInterface $entityManager) {
        
        $code = new FiscalData();
        $form = $this->createForm(FiscalDataType::class, $code);
        //Request to handle the submission of the form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
        
            //generate fiscal code
            $surname = $form->get('surname')->getData();
            $name = $form->get('name')->getData();
            $gender = $form->get('gender')->getData();
            $born_place = $form->get('born_place')->getData();  //TODO fix with DB
            $province = $form->get('province')->getData();
            $birth_day = $form->get('birth_day')->getData();

            //dd($surname, $name, $gender, $born_place, $birth_day);  //var_dump();

            $container = new FiscalCode($surname, $name, $gender, date_format($birth_day, 'Y'),
                date_format($birth_day, 'm'), date_format($birth_day, 'd'), $born_place, $province);
                //TODO TOFIX
            //$code = $container->calculate_code();//TODO fix exception in the class

            //EntityManagerInterface = DB

            //$entityManager->persist( $code ); //save
            $entityManager->flush();    //synchronizes the in-memory state of managed objects with the database.

            return new Response($twig->render('fiscal/code.html.twig', [
                'fiscal_form' => $form->createView(),
                'valid' => $code->getId()//$code
            ]));
        }

        return new Response($twig->render('fiscal/code.html.twig', [
            'fiscal_form' => $form->createView(),
            'valid' => '',
        ]));
    }
}

?>