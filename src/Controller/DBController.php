<?php
// src/Controller/DBController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/*DB usage following https://www.youtube.com/watch?v=NwmBZugOyHQ&list=PLFHz2csJcgk-t8ErN1BHUUxTNj45dkSqS&index=9 */
class DBController extends AbstractController{
    #[Route('/db/database', name:"DB", methods: ['GET', 'HEAD'])]
    public function index(): Response {
        //path to page to render, use single quotes (' ') for variables
        return $this->render('db/database.html.twig', [
            'title' => 'Movie',
        ]);
    }
    //connect to DATABASE_URL="mysql://root:password54321@127.0.0.1:3306/fiscal_code?serverVersion=5.7&charset=utf8mb4"
}