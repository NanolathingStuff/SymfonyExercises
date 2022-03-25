<?php
// src/Controller/DBController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;;
use App\Entity\ListaComuni;

/*DB usage following https://www.youtube.com/watch?v=NwmBZugOyHQ&list=PLFHz2csJcgk-t8ErN1BHUUxTNj45dkSqS&index=9 */
class DBController extends AbstractController{

    function read_csv($csv){
        $file = fopen($csv, 'r');
        while (!feof($file) ) {
            $line[] = fgetcsv($file, 1024);
        }
        fclose($file);
        return $line;
    }

    #[Route('/db/database', name:"DB", methods: ['GET', 'HEAD'])]
    public function index(ManagerRegistry $doctrine): Response {

        $entityManager = $doctrine->getManager();
         //use codes [DB]
        //CREATE TABLE IF NOT EXISTS listacomuni (Comune varchar(255), Provincia varchar(255), CodFisco varchar(255));
        //LOAD DATA INFILE '/home/nanolathingstuff/demo_project/src/files/listacomuni.csv' into table listacomuni FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS;
        /*after movinf file:  LOAD DATA INFILE '/var/lib/mysql-files/listacomuni.csv' into table listacomuni 
            FIELDS TERMINATED BY ',' ENCLOSED BY '"' LINES TERMINATED BY '\n' IGNORE 1 ROWS;*/
        /*$file = "../files/listacomuni.csv";
        $query = "LOAD DATA INFILE '".$file."'
            INTO TABLE listacomuni
            FIELDS TERMINATED BY ','
            OPTIONALLY ENCLOSED BY '\"' 
            LINES TERMINATED BY ',,,\r\n'
            IGNORE 1 LINES 
            (Comune, Provincia, CodFisco)";*/
        $file = "/home/nanolathingstuff/demo_project/src/Files/listacomuni.csv";    //App\Files
        $result = $this->read_csv($file);
        //dd(array_slice($result, 1, count($result)-2));

        //path to page to render, use single quotes (' ') for variables
        return $this->render('db/database.html.twig', [
            'error' => '',
            'title' => 'movie',
            'results' => array_slice($result, 1, count($result)-2),
        ]);
    }
    //connect to DATABASE_URL="mysql://root:password54321@127.0.0.1:3306/fiscal_code?serverVersion=5.7&charset=utf8mb4"
    
    /* I HAVE NO IDEA WHY THIS CRASHES THE SERVER
    #[Route('/db/insert/{file}', name:"create_city")]
    public function InsertFromFile(ManagerRegistry $doctrine, $file = "/home/nanolathingstuff/demo_project/src/Files/listacomuni.csv"): Response{
        $entityManager = $doctrine->getManager();
   
        $result = $this->read_csv($file);
        $data = array_slice($result, 1, count($result)-2);

        foreach ($data as $code) {
            $product = new ListaComuni();
            $product->setComune($code[0]);
            $product->setProvincia($code[1]);
            $product->setCodFisco($code[2]);

            dd($product->getComune(), $product->getProvincia(),$product->getCodFisco());
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            //$entityManager->persist($product);

            // actually executes the queries (i.e. the INSERT query)
            //$entityManager->flush();
        } 
    
        return $this->render('db/insert.html.twig', [
            'error' => '',
            'title' => 'movie',
            'results' => array_slice($result, 1, count($result)-2),
        ]);
    }*/
}