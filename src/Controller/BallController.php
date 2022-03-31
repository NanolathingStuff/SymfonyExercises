<?php
// src/Controller/BallCodeController.php
namespace App\Controller;

use App\Files\SquareFactory;   //custom class
use App\Files\Ball;   //custom class
use App\Files\Obstacle;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\SquareFormType;

class BallController extends AbstractController{

    //#[Route('/ball/ball', name:"ball")]   //http://127.0.0.1:8000/ball/ball 
    public function create(Request $request): Response{

        $square_form = $this->createForm(SquareFormType::class);
        $square_form->handleRequest($request);
        $width = 600;
        $height = 600;
        //since they are 2 divs, obstacle is by default put below the ball (left if you keep only the images)
        $obstacle_top = rand(0, $height - 40*2);  //$this->obstacle->getSize()
        $obstacle_left = rand(0, $width - 40);  //$this->obstacle->getSize()
        $ball_top = rand(0, (int)( $height - 50)/10) * 10;   // must be multiple of 10
        $ball_left = rand(0, (int)( $width - 50)/10) * 10; //$this->ball->getSize()

        $ball = new Ball($ball_left, $ball_top);//$factory->getBall();
        $obj = new Obstacle($obstacle_top, $obstacle_left); //new Obstacle($obstacle_top, $obstacle_left);
        $ball->setImg('/Img/ball.jpeg');

        if($square_form->isSubmitted() && $square_form->isValid()){

            $width = $square_form->get('width')->getData();
            $height = $square_form->get('height')->getData();
            
            $obstacle_top = rand(0, $height - 40*2);  
            $obstacle_left = rand(0, $width - 40);  
            $ball_top = rand(0, (int)( $height - 50)/10) * 10;   
            $ball_left = rand(0, (int)( $width - 50)/10) * 10; 

            return $this->render('ball/ball.html.twig', [
                'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
                'square_width' => $width,
                'square_height' => $height,
                'obstacle_top' => $obstacle_top,  
                'obstacle_left' => $obstacle_left,  
                'ball_top' => $ball_top,  
                'ball_left' => $ball_left,  
                'img' => $ball->getImg(),
                'obj' => $obj->getImg(),
            ]);
        }


       // return new Response('<html><body>Lucky number: '.$number.'</body></html>');
        return $this->render('ball/ball.html.twig', [
            'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
            'square_width' => $width,
            'square_height' => $height,  
            'obstacle_top' => $obstacle_top,  
            'obstacle_left' => $obstacle_left,  
            'ball_top' => $ball_top,  
            'ball_left' => $ball_left,  
            'img' => $ball->getImg(),
            'obj' => $obj->getImg(),
        ]);
    }
}

?>