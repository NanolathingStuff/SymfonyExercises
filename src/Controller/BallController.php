<?php
// src/Controller/BallCodeController.php
namespace App\Controller;

use App\Files\SquareFactory;   //custom class
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; 
use Symfony\Component\HttpFoundation\Request;
use App\Form\SquareFormType;
use App\Form\CommandForm;
use App\Files\CommandInvoker;
use App\Files\goUpCommand;
use App\Files\goDownCommand;
use App\Files\goLeftCommand;
use App\Files\goRightCommand;
use App\Files\set_ballCommand;
use App\Files\set_bowling_ballCommand;
use App\Files\set_silver_ballCommand;


class BallController extends AbstractController{

    //#[Route('/ball/ball', name:"ball")]   //http://127.0.0.1:8000/ball/ball 
    public function create(Request $request): Response{

        //randomize starting ball
        $ball_top = rand(0, (int)( 600 - 50)/10) * 10;   // must be multiple of 10
        $ball_left = rand(0, (int)( 600 - 50)/10) * 10; //  $this->ball->getSize()

        $factory = new SquareFactory($ball_top, $ball_left); //, '/Img/ball.jpeg', $obstacle_top, $obstacle_left, $width, $height
        $ball = $factory->getBall();//new Ball($ball_left, $ball_top);
        $ball->setImg('/Img/ball.jpeg');

        $ball = $factory->getBall();
        $square = $factory->getSquare();
        $obj = $factory->getObstacle();
        
        //square size handler
        $square_form = $this->createForm(SquareFormType::class);
        $square_form->handleRequest($request);
        //press button handler
        $button_form = $this->createForm(CommandForm::class);
        $button_form->handleRequest($request);

        //get command
        if(isset($_POST) && count($_POST) > 1){ //symfony form return array(1) { ["square_form"]=> array(4)
            //var_dump($_POST);   printf(count($_POST));

            $ball->setLeft($_POST['left']);
            $ball->setTop($_POST['top']);
            $ball->setImg($_POST['img']);
            $obj->setLeft($_POST['obstacle_left']);
            $obj->setTop($_POST['obstacle_top']);
            $obj->setCount($_POST['obstacle_count']);
            $square->setWidth($_POST['width']);
            $square->setHeight($_POST['height']);

            //check if hit obstacle
            if ($factory->checkCollision()){ 
                $count = 5;
            }else{
                if($_POST['obstacle_count'] > 0){
                    $count = $_POST['obstacle_count']-1; 
                }else{
                    $count = 0;
                }
            }
            $obj->setCount($count);
  
            /*control section*///TODO
            if (isset($_POST["command"])){  //movement
                //try{
                $commands = $this->generateCommands($ball, ($obj->getCount()>0), [$square->getWidth()-$ball->getSize(), $square->getHeight()-$ball->getSize()]);
                $command = $commands[$_POST["command"]];//$factory->getCommands()[$_POST["command"]];
                /*if($_POST["command"] == "goUp")  $ball->moveUp();
                if($_POST["command"] == "goDown")  $ball->moveDown();
                if($_POST["command"] == "goLeft")  $ball->moveLeft();
                if($_POST["command"] == "goRight")  $ball->moveRight();*/

                $invoker = new CommandInvoker($command); //change this part if you implement new commands
                $invoker->handle(); /*
                }catch(Exception $exception) {
                    echo "Unknown Command error: " . $exception->getMessage() . '<br>';
                }*/
            }
        }
        //submission code
        if($square_form->isSubmitted() && $square_form->isValid()){
            //$content = $request->getContent();
            //dd( $demoString->get(), $content, $square_form->getData());

            //TODO get saved factory
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
                'square_width' => $square->getWidth(),
                'square_height' => $square->getHeight(),  
                'obstacle_top' => $obj->getTop(),  
                'obstacle_left' => $obj->getLeft(),  
                'ball_top' => $ball->getTop(),  
                'ball_left' => $ball->getLeft(),  
                'img' => $ball->getImg(),
                'obj' => $obj->getImg(),
                'count'=> $obj->getCount(),
            ]);
        }

        //default render
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
            'count'=> $obj->getCount(),
        ]);
    }

    //TODO fix this in a sensate manner
    public function generateCommands($ball, $bouncing=FALSE, array $max=[]){
        return array(
            'goUp' => new goUpCommand($ball,  $bouncing, $max),
            'goDown' => new goDownCommand($ball,  $bouncing, $max),
            'goLeft' => new goLeftCommand($ball,  $bouncing, $max),
            'goRight' => new goRightCommand($ball,  $bouncing, $max),
            'set_ball' => new set_ballCommand($ball,  $bouncing, $max),
            'set_bowling_ball' => new set_bowling_ballCommand($ball,  $bouncing, $max),
            'set_silver_ball' => new set_silver_ballCommand($ball,  $bouncing, $max), 
        );
    }
}

?>