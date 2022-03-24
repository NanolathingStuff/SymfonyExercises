<?php
// src/Controller/DBController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;

/*DB usage following https://www.youtube.com/watch?v=NwmBZugOyHQ&list=PLFHz2csJcgk-t8ErN1BHUUxTNj45dkSqS&index=9 */
class DBController extends AbstractController{

    #[Route('/db/database', name:"DB", methods: ['GET', 'HEAD'])]
    public function index(ManagerRegistry $doctrine): Response {
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
            (Comune, Provincia, CodFisco)";
        $result = $mysqli->query($query);*/
        
        //try normal SQL because IDK how to symfony
        try{    // Connect to the database server
            $password = '';    //DELETE THIS BEFOR EACH COMMIT
            $mysqli = new \mysqli('localhost', 'root', $password);  
            if ($mysqli->connect_errno) {
                return $this->render('db/database.html.twig', [
                    'error' => 'Unable to connect to the database:<br /> %s', $mysqli->connect_error,
                ]);
            }
        }catch(\Exception $e) {
            return $this->render('db/database.html.twig', [
                'error' => 'Connection error: ' .$e->getMessage(),
            ]);
        }
        try{    // Select the database
            $mysqli->select_db('codes');
        }catch(\Exception $e) {
            die();
        };
        $query = "SELECT COUNT(*) FROM listacomuni;";
        $result = $mysqli->query($query);
        $rows = mysqli_fetch_all($result);
        
        //path to page to render, use single quotes (' ') for variables
        return $this->render('db/database.html.twig', [
            'error' => '',
            'title' => 'movie',
            'results' => $rows[0],
        ]);
    }
    //connect to DATABASE_URL="mysql://root:password54321@127.0.0.1:3306/fiscal_code?serverVersion=5.7&charset=utf8mb4"
}