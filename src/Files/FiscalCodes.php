<?php
namespace App\Files;

class FiscalCode{
    // constant tables    
    private const monthCode = array('01' => 'A', '02' => 'B', '03' => 'C', '04' => 'D', '05' => 'E', '06' => 'H', '07' => 'L',
        '08' => 'M', '09' => 'P', '10' => 'R', '11' => 'S', '12' => 'T'); 

    private const oddTables = array(0 => 1, 1 => 0, 2 => 5, 3 => 7, 4 => 9, 5 => 13, 6 => 15, 7 => 17, 8 => 19, 9 => 21, 'A' => 1,
        'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2,
        'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14, 'U' => 16,
        'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23);
    
    
    /* attributes are unnecessary*/
    private $Gender;
    private $Surname;
    private $Name;
    private $City;
    private $Day;
    private $Month;
    private $Year; 
    /* Class functions */
    function __construct($Surname, $Name, $Gender, $Year, $Month, $Day, $From){ //no need of setters for now
        $this->Gender = $Gender;
        $this->Surname = $Surname;
        $this->Name = $Name;
        $this->City = $From;
        $this->Day = $Day;
        $this->Month = $Month;
        $this->Year =$Year; 
    }
    public function getGender() {
        return $this->Gender;
    }
    public function getSurname() {
        return $this->Surname;
    }
    public function getName() {
        return $this->Name;
    }
    public function getCityCode() {
        return $this->City;
    }
    public function getDay() {
        return $this->Day;
    }
    public function getMonth() {
        return $this->Month;
    }
    public function getYear() {
        return $this->Year;
    }

    /* other functions */
    private function control_code($string){
        //separate alfanumeric characters on even position to the ones in odd positions (skip saves for optimization);
        //Done this, the characters are converted into a numeric value following the constant tables:
        $sum = 0;   //and summed between each other
        for($i=0; $i<strlen($string); $i++){
            if($i% 2 === 1){    //even
                if (is_numeric($string[$i])){  //is a number
                    $sum += intval($string[$i]);
                }else{
                    $sum+=(count(range('A', $string[$i]))-1);   //alphabet lentgh
                }
            }else{  //odd
                $sum+=FiscalCode::oddTables[$string[$i]];
            }
        } //Now, values obtained from the odd and even characters strings are summed and the result
        //is divided by 26; the reminder of the division will be converted in the control code, using the table:
        return  trim(range('A','Z', ($sum%26))[1]); //no need to run all array
    }
    
    private function name_code(String $nome, Bool $cognome=false){
        $tmp = '';
        preg_match_all("/[^aeiouAEIOU\s]/", $nome, $consonanti);    
        preg_match_all("/[aeiouAEIOU]/", $nome, $vocali); 
        array_push($vocali[0], 'X', 'X');  //in the case the name have less than 3 etters, the code is finished adding the letter X.

        if((!$cognome) && count($consonanti[0])>=4){
            $tmp .= $consonanti[0][0].$consonanti[0][2].$consonanti[0][3];
        }else{
            for($i = 0; ($i < count($consonanti[0]) && $i<3 ); $i++)
                $tmp .= $consonanti[0][$i];
        }
        $i = 0; //If the consonants are insufficient, the vocals are used
        while(strlen($tmp) < 3){    //if too short fill with vocals
            $tmp .= $vocali[0][$i]; 
            $i++;
        }
        return $tmp;
    }
     // main function
    public function calculate_code(){
        //control errors: all parameters required
        if($this->getSurname() == '' || $this->getName() == ''|| $this->getYear() == '' || $this->getMonth() == '' ||
            $this->getDay() == '' || $this->getCityCode() == '' ){
                return '';//throw new \Exception("Error: missing parameter!");  //TODO fix this
        }//else
        if(preg_match("/[^a-zA-Z\s]/", $this->getName())){
            return '';//throw new \Exception("Error: invalid name");    //TODO fix this
        }
        if(preg_match("/[^a-zA-Z\s]/", $this->getSurname())){
            return '';//throw new \Exception("Error: invalid surname"); //TODO fix this
        }   
        $tmp = '';
        
        $tmp .= $this->name_code($this->getSurname(), true);  //First 3 letters are taken by the surname (generally first, second and third consonant)
        $tmp .= $this->name_code($this->getName());  //Second 3 letters fom the name (generally first, third and fourth consonant)         
        $tmp .= substr($this->getYear(), -2);  // the last 2 numbers of the birth year
        $tmp .= FiscalCode::monthCode[$this->getMonth()];   // the letter corresponding to the month (A = Gennaio, B, C, D, E, H, L, M, P, R, S, T = Dicembre)
        //Gender is passed as 'False' = 'Male', 'True' = 'Female'
        if($this->getGender()  == False) { // the birth day: 
            //if ($this->getDay()<10)  //in case of female sex, 40 is added to that number
            //DONE automatically    $tmp .= '0';    //if the day is included between 1 and 9 a 0 is added at the beginning
            $tmp .= strval($this->getDay());  //TOSTRING     
        } else{ 
            $tmp .= strval($this->getDay() + 40);
        }

        if($this->getCityCode() == ''){
            return '';
        }else{
            $tmp .= $this->getCityCode();
            $tmp = strtoupper($tmp);    //array-dict has only uppercase letters
            $tmp .=  $this->control_code($tmp); // Control character, to verify if the code is calculated correctly.
        }      
        return $tmp;
    }
}
?>