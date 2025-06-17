<?php

class Db{
    private $hostname = "localhost";
    private $user = "root";
    private $pswd = "";
    private $dbname = "atmabiswas";
    private $pdo;

    public function connect(){
    $this->pdo=null;
        
        try{
            $this->pdo = new PDO("mysql:host=$this->hostname;dbname=$this->dbname",$this->user,$this->pswd);

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
            
        }catch(PDOException $e){
            print_r($e);

        }
    return $this->pdo;
    }
}


?>