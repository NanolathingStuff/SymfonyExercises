<?php

class CodeContainer{
    // constant tables    
    private const monthCode = array('1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'H', '7' => 'L',
        '8' => 'M', '9' => 'P', '10' => 'R', '11' => 'S', '12' => 'T'); 

    private const oddTables = array(0 => 1, 1 => 0, 2 => 5, 3 => 7, 4 => 9, 5 => 13, 6 => 15, 7 => 17, 8 => 19, 9 => 21, 'A' => 1,
        'B' => 0, 'C' => 5, 'D' => 7, 'E' => 9, 'F' => 13, 'G' => 15, 'H' => 17, 'I' => 19, 'J' => 21, 'K' => 2,
        'L' => 4, 'M' => 18, 'N' => 20, 'O' => 11, 'P' => 3, 'Q' => 6, 'R' => 8, 'S' => 12, 'T' => 14, 'U' => 16,
        'V' => 10, 'W' => 22, 'X' => 25, 'Y' => 24, 'Z' => 23);
    
    
    /* attributes are unnecessary*/
    private $Gender;
    private $Surname;
    private $Name;
    private $City;
    private $Province;
    private $Day;
    private $Month;
    private $Year; 
    /* Class functions */
    function __construct($Surname, $Name, $Gender, $Year, $Month, $Day, $From, $Province){ //no need of setters for now
        $this->Gender = $Gender;
        $this->Surname = $Surname;
        $this->Name = $Name;
        $this->City = $From;
        $this->Province = $Province;
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
    public function getCity() {
        return $this->City;
    }
    public function getProvince() {
        return $this->Province;
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
                $sum+=CodeContainer::oddTables[$string[$i]];
            }
        } //Now, values obtained from the odd and even characters strings are summed and the result
        //is divided by 26; the reminder of the division will be converted in the control code, using the table:
        return  trim(range('A','Z', ($sum%26))[1]); //no need to run all array
    }
    
    private function codice_comune($comune, $sigla){   //from https://dait.interno.gov.it/territorio-e-autonomie-locali/sut/elenco_codici_comuni.php 
        $filename = "../codes/listacomuni.csv"; //downloaded http://lab.comuni-italiani.it/download/comuni.html
        if(file_exists($filename)){
            $file = file($filename);
            foreach($file as $row){
                $data = explode(',', $row);
                if(strtolower($comune) == strtolower($data[0]) && strtolower($sigla) == strtolower($data[1]))
                    return trim($data[2]); //file is modified as: Comune; Provincia; Codice
            }
        }else{
            return 0;   //error file not found
        }return 1;  //error town not found
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
        if($_POST['surname'] == '' || $_POST['name'] == ''|| $_POST['year'] == 0 || $_POST['month'] == 0 ||
            $_POST['day'] == 0 || $_POST['born_place'] == '' ||$_POST['province'] == ''){
                throw new Exception("Error: missing parameter!");
        }//else
        if(preg_match("/[^a-zA-Z\s]/", $this->getName())){
            throw new Exception("Error: invalid name");
        }
        if(preg_match("/[^a-zA-Z\s]/", $this->getSurname())){
            throw new Exception("Error: invalid surname");
        }
        $tmp = '';
        
        $tmp .= $this->name_code($this->getSurname(), true);  //First 3 letters are taken by the surname (generally first, second and third consonant)
        $tmp .= $this->name_code($this->getName());  //Second 3 letters fom the name (generally first, third and fourth consonant)         
        $tmp .= substr($this->getYear(), -2);  // the last 2 numbers of the birth year
        $tmp .= CodeContainer::monthCode[$this->getMonth()];   // the letter corresponding to the month (A = Gennaio, B, C, D, E, H, L, M, P, R, S, T = Dicembre)
        if($this->getGender()  == "Male") { // the birth day: 
            if ($this->getDay()<10)  //in case of female sex, 40 is added to that number
                $tmp .= '0';    //if the day is included between 1 and 9 a 0 is added at the beginning
            $tmp .= strval($this->getDay());  //TOSTRING     
        } else{ 
            $tmp .= strval($this->getDay() + 40);
        }
        $townCode = $this->codice_comune($this->getCity(), $this->getProvince());
        $tmp .= $townCode;  //Codice Belfiore of the town (4 characters)
        if ($townCode === 0){   //error
            throw new Exception("Error: File not found");
        }elseif ($townCode === 1){   //error
            throw new Exception("Error: Codice Comune not found");
        }else{
            $tmp = strtoupper($tmp);    //array-dict has only uppercase letters
            $tmp .=  $this->control_code($tmp); // Control character, to verify if the code is calculated correctly.
        }      
        return $tmp;
    }
}
?>