<?php
// src/Controller/DBController.php
namespace App\Controller;

use App\Entity\Value;
use App\Form\ValueFormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;;
use App\Entity\ListaComuni;
use Symfony\Component\HttpFoundation\Request;

class DBController extends AbstractController{

    function read_csv($csv){
        $file = fopen($csv, 'r');
        while (!feof($file) ) {
            $line[] = fgetcsv($file, 1024);
        }
        fclose($file);
        return $line;
    }

    #[Route('/db/insert', name:"DB", methods: ['POST','GET', 'HEAD'])]
    public function index(ManagerRegistry $doctrine, Request $request): Response {

        $entityManager = $doctrine->getManager();

        $file = "/home/nanolathingstuff/demo_project/src/Files/listacomuni.csv";    //App\Files
        $result1 = $this->read_csv($file);
        //dd(array_slice($result, 1, count($result)-2));
        $repository = $doctrine->getRepository(ListaComuni::class);
        $result = $repository->findAll();
        //dd($result);
        $offset = count($result) + 1; //get the ones not already inserted
        $data = array_slice($result1, $offset);
        //dd(array_slice($result, 4000), $data);
        $code = new Value();
        $form = $this->createForm(ValueFormType::class, $code);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $value = $form->get('value')->getData();

            //foreach ($data as $code) {
            for ($i = 0; ($i < $value && $i < count($data)); $i++) {
                $code = $data[$i];

                if(is_array($code)){
                    $product = new ListaComuni();
                    $product->setComune($code[0]);
                    $product->setProvincia($code[1]);
                    $product->setCodFisco($code[2]);

                    //dd($product->getComune(), $product->getProvincia(),$product->getCodFisco());
                    // tell Doctrine you want to (eventually) save the Product (no queries yet)
                    $entityManager->persist($product);

                    // actually executes the queries (i.e. the INSERT query)
                    $entityManager->flush();
                }
            }
            return $this->render('db/insert.html.twig', [
                'error' => '',
                'title' => 'movie',
                'results' => $data,
                'form' => $form->createView(),
                'count' => $i,
            ]);
        }
        //path to page to render, use single quotes (' ') for variables
        return $this->render('db/insert.html.twig', [
            'error' => '',
            'title' => 'movie',
            'results' => '',
            'form' => $form->createView(),
            'count' => '',
        ]);
    }
    //connect to DATABASE_URL="mysql://root:password54321@127.0.0.1:3306/fiscal_code?serverVersion=5.7&charset=utf8mb4"
    
}