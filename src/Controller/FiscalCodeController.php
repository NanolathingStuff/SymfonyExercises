<?php
// src/Controller/FiscalCodeController.php
namespace App\Controller;

use App\Files\FiscalCode;   //custom class to calculate code
use App\Entity\FiscalData;
use App\Entity\ListaComuni;
use App\Form\FiscalDataType;
use Doctrine\Persistence\ManagerRegistry;   //access DB
use Symfony\Component\Validator\Validator\ValidatorInterface;   //validate
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;


class FiscalCodeController extends AbstractController{

    #[Route('/fiscal/code', name:"FiscalCode")]   //http://127.0.0.1:8000/fiscal/code 
    public function show(Environment $twig, Request $request, ManagerRegistry $doctrine, ValidatorInterface $validator) {
        
        $code = new FiscalData();
        $form = $this->createForm(FiscalDataType::class, $code);
        //Request to handle the submission of the form
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $fiscal_data = new FiscalData();

            //generate fiscal code
            $surname = $form->get('surname')->getData();
            $name = $form->get('name')->getData();
            $gender = $form->get('gender')->getData();
            $born_place = $form->get('born_place')->getData();  //TODO fix with DB
            $province = $form->get('province')->getData();
            $birth_day = $form->get('birth_day')->getData();

            //EntityManagerInterface = DB
            //The ManagerRegistry $doctrine argument tells Symfony to inject the Doctrine service into the controller method.
            $entityManager = $doctrine->getManager();   //gets Doctrine's entity manager object, which is the most important object in Doctrine. It's responsible for saving objects to, and fetching objects from, the database.
            
            $city = $doctrine->getRepository(ListaComuni::class)->findOneBy(['Comune' => $born_place, 'Provincia' => $province]);
            if($city){  //found
                $city_code = $city->getCodFisco();
            }else{
                $city_code = '';
            }

            $container = new FiscalCode($surname, $name, $gender, date_format($birth_day, 'Y'),
                date_format($birth_day, 'm'), date_format($birth_day, 'd'), $city_code);
            $code = $container->calculate_code();

            /*  TODO
            $fiscal_data->setSurname($surname );
            $fiscal_data->setName($name);
            $fiscal_data->setGender($gender);
            $fiscal_data->setBornPlace($born_place);
            $fiscal_data->setProvince($province);
            $fiscal_data->setBirthDay($birth_day);
            $fiscal_data->setCode($code);
            $fiscal_data->setGenerationDate('Keyboard');
            $fiscal_data->setIp('Keyboard');
             */
            $errors = $validator->validate($fiscal_data);
            if (count($errors) > 0) {
                return new Response((string) $errors, 400);
            }
            /*   
            $entityManager->persist( $code ); //save
            $entityManager->flush();    //synchronizes the in-memory state of managed objects with the database.
            */
            return new Response($twig->render('fiscal/code.html.twig', [
                'fiscal_form' => $form->createView(),
                'valid' => $code,
            ]));
        }

        return new Response($twig->render('fiscal/code.html.twig', [
            'fiscal_form' => $form->createView(),
            'valid' => 'NULL',
        ]));
    }
}

?>