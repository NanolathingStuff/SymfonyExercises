<?php

namespace App\Files;    //To be used by other files (like BallController)

//factory pattern and file config
class SquareFactory{
    private $array_assoc;
    private $square;
    private $ball;
    private $obstacle;

    public function __construct($ball_top=NULL, $ball_left=NULL, $ball_img=NULL, 
        $obstacle_top=NULL, $obstacle_left=NULL, $square_width = 600, $square_height = 600) {
        
        $this->square = new Square($square_width, $square_height);
        $this->setBall($ball_top, $ball_left, $ball_img);
        $this->obstacle = new Obstacle(0, 0);  //TODO more than 1?
        //since they are 2 divs, obstacle is by default put below the ball (left if you keep only the images)
        if(!isset($obstacle_top)){
            $obstacle_top = rand(0, $square_height - $this->obstacle->getSize()*2); //-size
        }
        if(!isset($obstacle_left)){
            $obstacle_left = rand(0, $square_width - $this->obstacle->getSize());
        }
        //if resized square too small
        if( ($square_width < ($this->ball->getLeft() + $this->ball->getSize())) || ($square_height < ($this->ball->getTop() + $this->ball->getSize())) ){
            $top = rand(0, (int)( $square_height - $this->ball->getSize())/10) * 10;   // must be multiple of 10
            $left = rand(0, (int)( $square_width - $this->ball->getSize())/10) * 10; 
            $this->ball->setTop($top);
            $this->ball->setLeft($left);
        }

        $this->obstacle->setTop($obstacle_top);
        $this->obstacle->setLeft($obstacle_left); 
        //TODO find a way to parse from YML
        //$this->setCommandsFromXML();
    }

    public function setBall($top, $left, $img){  
        if(!isset($top) || !isset($left) ){
            $ball = new Ball(300, 300);// create ball object first
        }else{
          $ball = new Ball($left, $top);
          $ball->setImg($img);
        }
        $this->ball = $ball;
    }
    public function getBall() {
        return $this->ball;
    }
    public function setObstacle($obstacle) {
        $this->obstacle = $obstacle;
    }
    public function getObstacle() {
        return $this->obstacle;
    }
    public function setSquare($square) {
        $this->square = $square;
    }
    public function getSquare() {
        return $this->square;
    }
    //To add new commands
    public function setCommandsFromXML($file = '../codes/ballCommands.xml'){
        $arr = simplexml_load_file($file) or die("Error: Cannot create object from file");
        $arr = json_decode ( json_encode( $arr ), true );
        $this->array_assoc = $arr;  
    } 
    public function setCommands(){
        $this->array_assoc = array(
            'goUp' => 'goUpCommand',
            'goDown' => 'goDownCommand',
            'goLeft' => 'goLeftCommand',
            'goRight' => 'goRightCommand',
            'set_ball' => 'set_ballCommand',
            'set_bowling_ball' => 'set_bowling_ballCommand',
            'set_silver_ball' => 'set_silver_ballCommand'); 
    }
    public function getCommands() {
        return $this->array_assoc;
    }
    // generic checkHit ball function, works with NULL values
    public function checkCollision(){//if the ball touch the obstacle --> true
      if( ($this->ball->getLeft() < ($this->obstacle->getLeft() + $this->obstacle->getSize()) && 
        ($this->ball->getLeft() > $this->obstacle->getLeft() - $this->ball->getSize())) && 
        ($this->ball->getTop() < $this->obstacle->getTop() + $this->obstacle->getSize() && 
        $this->ball->getTop() > ($this->obstacle->getTop() - $this->ball->getSize()))){
        return true;
      }else return false;
    }
}

//Command (what is execute)
interface Command{
    public function execute();
}
//movement commands
class goUpCommand implements Command{
    private $ball;
    private $bouncing;
    private $max;
                                            //failed splat (...) operator
    public function __construct($ball, $bouncing=FALSE, array $max=[]) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
        $this->max = $max;
    }

    public function execute(){
        if($this->bouncing){
            if(count($this->max) > 1)
                $this->ball->moveDown($this->max[1]); // set $this->max
            else
                $this->ball->moveDown();
        }else{
            $this->ball->moveUp();
        }
    }
}
class goDownCommand implements Command{
    private $ball;
    private $bouncing;
    private $max;

    public function __construct($ball, $bouncing=FALSE, array $max=[]) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
        $this->max = $max;
    }

    public function execute(){
        if($this->bouncing){
            $this->ball->moveUp();
        }else{
            if(count($this->max) > 1)
                $this->ball->moveDown($this->max[1]); // set $this->max
            else
                $this->ball->moveDown();
        }
    }
}
class goLeftCommand implements Command{
    private $ball;
    private $bouncing;
    private $max;

    public function __construct($ball, $bouncing=FALSE, array $max=[]) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
        $this->max = $max;
    }

    public function execute(){
        if($this->bouncing){
            if(count($this->max) > 0)
                $this->ball->moveRight($this->max[0]); // set $this->max
            else
                $this->ball->moveRight();
        }else{
            $this->ball->moveLeft();
        }
    }
}
class goRightCommand implements Command{
    private $ball;
    private $bouncing;
    private $max;

    public function __construct($ball, $bouncing=FALSE, array $max=[]) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
        $this->max = $max;
    }

    public function execute(){
        if($this->bouncing){
            $this->ball->moveLeft();
        }else{
            if(count($this->max) > 0)
                $this->ball->moveRight($this->max[0]); // set $this->max
            else
                $this->ball->moveRight();
        }
    }
}
//img commands
class set_ballCommand implements Command{
    private $ball;
    private $bouncing;
    public function __construct($ball, $bouncing=FALSE) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
    }

    public function execute(){
        $this->ball->setImg("../img/ball.jpeg");
    }
}
class set_bowling_ballCommand implements Command{
    private $ball;
    private $bouncing;
    public function __construct($ball, $bouncing=FALSE) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
    }

    public function execute(){
        $this->ball->setImg("../img/bowling_ball.jpeg");
    }
}
class set_silver_ballCommand implements Command{
    private $ball;
    private $bouncing;
    public function __construct($ball, $bouncing=FALSE) {
        $this->ball = $ball;
        $this->bouncing = $bouncing;
    }

    public function execute(){
        $this->ball->setImg("../img/silver_ball.jpeg");
    }
}

//Receiver (receive command: base class)
class Ball{

    private $image = "../img/ball.jpeg";
    private $left;
    private $top;
    private $step = 10;
    private $size = 50;
    //functions
    public function __construct($left=300, $top=300) {
        $this->left = $left;
        $this->top = $top;
    }

    public function setLeft($left) {
        $this->left = $left;
    }
    public function setTop($top) {
        $this->top = $top;
    }
    public function getLeft() {
        return $this->left;
    }
    public function getTop() {
        return $this->top;
    }
    public function setImg($path) {
        $this->image = $path;
    }
    public function getImg() {
        return $this->image;
    }
    public function setStep($step) {
        $this->step = $step;
    }
    public function getStep() {
        return $this->step;
    }
    public function setSize($size) {
        $this->size = $size;
    }
    public function getSize() {
        return $this->size;
    }

    /*movement functions*/
    public function moveUp(){
        if($this->top>0) //border check
            $this->top -= $this->step;
    }
    public function moveLeft(){
        if($this->left>0) //border check
            $this->left -= $this->step;
    }
    public function moveRight($max=550){
        if($this->left<$max) //get border size
            $this->left += $this->step;
    }
    public function moveDown($max=500){
        if($this->top<$max) //get border size
            $this->top += $this->step;
    }
    public function checkHit(Obstacle $obj){//if the ball touch the obstacle --> count = 5
        if( ($this->getLeft()<($obj->getLeft()+$obj->getSize()) && ($this->getLeft() > $obj->getLeft()-$this->getSize())) &&
            ($this->getTop()<$obj->getTop()+$obj->getSize() && $this->getTop() > ($obj->getTop()-$this->getSize()))){
        $obj->setCount(5);
        }
    }
}
//Invoker (call Command)
class CommandInvoker{
    private $command;

    public function __construct(Command $command){
        $this->command = $command;
    }
    public function setCommand(Command $command){
        $this->command = $command;
    }
    public function handle(){
        return $this->command->execute();
    }
}

class Obstacle{  
    private $image = "/Img/pole.jpeg";
    private $left;
    private $top;
    private $count;
    private $size = 40;
    //functions
    function __construct($top, $left, $count=0) {
        $this->left = $left;
        $this->top = $top;
        $this->count = $count;
    }
    public function setLeft($left) {
        $this->left = $left;
    }
    public function setTop($top) {
        $this->top = $top;
    }
    public function getLeft() {
        return $this->left;
    }
    public function getTop() {
        return $this->top;
    }
    public function setCount($count) {
        $this->count = $count;
    }
    public function getCount() {
        return $this->count;
    }
    public function getImg() {
        return $this->image;
    }
    public function setSize($size) {
        $this->size = $size;
    }
    public function getSize() {
        return $this->size;
    }
}
class Square{   //TODO to change and  get border size dynamically
    private $border;
    private $width;
    private $height;

    public function __construct($width = 600, $height = 550, $border = 14) {
        $this->height = $height;
        $this->width = $width;
        $this->border = $border;
    }

    public function setBorder($border) {
        $this->border = $border;
    }
    public function getBorder() {
        return $this->border;
    }
    public function setWidth($width) {
        $this->width = $width;
    }
    public function getWidth() {
        return $this->width;
    }
    public function setHeight($height) {
        $this->height = $height;
    }
    public function getHeight() {
        return $this->height;
    }
}


?>