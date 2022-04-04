<?php
// src/Controller/BallCodeController.php
namespace App\Controller;

use App\Files\SquareFactory;   //custom class
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\SquareFormType;
use App\Form\CommandForm;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class BallController extends AbstractController{

    //#[Route('/ball/ball', name:"ball")]   //http://127.0.0.1:8000/ball/ball 
    public function create(Request $request): Response{

        $cache = new FilesystemAdapter();   //initialize cache manager
        $factory = new SquareFactory(); //to avoid error, will be overwritten
        //retrieve saved item
        if ($cache->hasItem('factory')){
            $cachedObject = $cache->getItem('factory');
            $factory = $cachedObject->get();    //get value
            //dd('cached', $cachedObject, $factory);
        }else{
            //randomize starting ball
            $ball_top = rand(0, (int)( 600 - 50)/10) * 10;   // must be multiple of 10
            $ball_left = rand(0, (int)( 600 - 50)/10) * 10; //  $this->ball->getSize()

            $factory = new SquareFactory($ball_top, $ball_left); //, '/Img/ball.jpeg', $obstacle_top, $obstacle_left, $width, $height
            $ball = $factory->getBall();//new Ball($ball_left, $ball_top);
            $ball->setImg('/Img/ball.jpeg');
            // store values
            $savedObj = $cache->getItem('factory');
            if (!$savedObj->isHit()){
                $savedObj->set($factory);
                $cache->save($savedObj);
            }
            //dd('uncached', $factory);
        }

        $ball = $factory->getBall();
        $square = $factory->getSquare();
        $obj = $factory->getObstacle();
        
        //square size handler
        $square_form = $this->createForm(SquareFormType::class);
        $square_form->handleRequest($request);
        //press button handler
        $button_form = $this->createForm(CommandForm::class);
        $button_form->handleRequest($request);

        //submission code
        if($square_form->isSubmitted() && $square_form->isValid()){
            //$content = $request->getContent();
            //dd( $demoString->get(), $content, $square_form->getData());

            //get saved factory
            $cachedObject = $cache->getItem('factory');
            $factory = $cachedObject->get();    //get value
            // delete all items
            $cache->clear();
            // store new values
            $savedObj = $cache->getItem('factory');
            if (!$savedObj->isHit()){
                $savedObj->set($factory);
                $cache->save($savedObj);
            }

            $width = $square_form->get('width')->getData();
            $height = $square_form->get('height')->getData();

            $square->setWidth($width);
            $square->setHeight($height);

            $obstacle_top = $obj->getTop();  
            $obstacle_left = $obj->getLeft();  
            $ball_top = $ball->getTop();
            $ball_left = $ball->getLeft();

            //TODO problem here
            if (($obstacle_top >= $height - $obj->getSize()) || ($obstacle_left >= $width - $obj->getSize())) {
                $obstacle_top = rand(0, $height - $obj->getSize()*2);
                $obj->setTop($obstacle_top );
                $obstacle_left = rand(0, (int)($width - $obj->getSize()));  
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
                'button_form' => $button_form->createView(),    
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

        if($button_form->isSubmitted() && $button_form->isValid()){
            //dd($button_form->getData(), $request->getContent());

            return $this->render('ball/ball.html.twig', [
                'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
                'button_form' => $button_form->createView(),    
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
        //default render
        return $this->render('ball/ball.html.twig', [
            'square_form' => $square_form->createView(),    //necessary function to create the form in the twig
            'button_form' => $button_form->createView(),    
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