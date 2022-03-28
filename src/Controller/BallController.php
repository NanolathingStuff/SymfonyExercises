<?php
// src/Controller/BallCodeController.php
namespace App\Controller;

use App\Files\SquareFactory;   //custom class
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\SquareFormType;

class BallController extends AbstractController{

    //#[Route('/ball/ball', name:"ball")]   //http://127.0.0.1:8000/ball/ball 
    public function create(Request $request): Response{

        $square_form = $this->createForm(SquareFormType::class);
        $square_form->handleRequest($request);

       // return new Response('<html><body>Lucky number: '.$number.'</body></html>');
        return $this->render('ball/ball.html.twig', [
            'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
        ]);
    }
}

?>