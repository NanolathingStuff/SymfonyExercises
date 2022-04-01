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

        //randomize starting ball
        $ball_top = rand(0, (int)( 600 - 50)/10) * 10;   // must be multiple of 10
        $ball_left = rand(0, (int)( 600 - 50)/10) * 10; //  $this->ball->getSize()

        $factory = new SquareFactory($ball_top, $ball_left); //, '/Img/ball.jpeg', $obstacle_top, $obstacle_left, $width, $height
        $ball = $factory->getBall();//new Ball($ball_left, $ball_top);
        $ball->setImg('/Img/ball.jpeg');
        $square = $factory->getSquare();
        $obj = $factory->getObstacle();//new Obstacle($obstacle_top, $obstacle_left); 

        //square size handler
        $square_form = $this->createForm(SquareFormType::class);
        $square_form->handleRequest($request);
        if($square_form->isSubmitted() && $square_form->isValid()){
            dd($square_form->getData());


            $width = $square_form->get('width')->getData();
            $height = $square_form->get('height')->getData();

            $square->setWidth($width);
            $square->setHeight($height);

            $obstacle_top = $obj->getTop();  
            $obstacle_left = $obj->getLeft();  
            $ball_top = $ball->getTop();
            $ball_left = $ball->getLeft();

            if (($obstacle_top >= $height - $obj->getSize()) || ($obstacle_left >= $width - $obj->getSize())) {
                $obstacle_top = rand(0, $height - $obj->getSize()*2);
                $obj->setTop($obstacle_top );
                $obstacle_left = rand(0, (int)($width - $obj->getSize()));  //TODO problem here
                $obj->setLeft($obstacle_left );
            }
            if (($ball_top >= $height - $ball->getSize()) || ($ball_left >= $width - $ball->getSize())) {
                $ball_top = rand(0, (int)( $height - $ball->getSize())/10) * 10;   
                $ball_left = rand(0, (int)( $width - $ball->getSize())/10) * 10;  
                $ball->setLeft($obstacle_left );
                $ball->setTop($obstacle_top );
            }
            
              
            return $this->render('ball/ball.html.twig', [
                'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
                'square_width' => $square->getWidth(),
                'square_height' => $square->getHeight(),  
                'obstacle_top' => $obj->getTop(),  
                'obstacle_left' => $obj->getLeft(),  
                'ball_top' => $ball->getTop(),  
                'ball_left' => $ball->getLeft(),  
                'img' => $ball->getImg(),
                'obj' => $obj->getImg(),
            ]);
        }

       // return new Response('<html><body>Lucky number: '.$number.'</body></html>');
        return $this->render('ball/ball.html.twig', [
            'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
            //'square' => $factory,
            'square_width' => $square->getWidth(),
            'square_height' => $square->getHeight(),  
            'obstacle_top' => $obj->getTop(),  
            'obstacle_left' => $obj->getLeft(),  
            'ball_top' => $ball->getTop(),  
            'ball_left' => $ball->getLeft(),  
            'img' => $ball->getImg(),
            'obj' => $obj->getImg(),
        ]);
    }
}

?>