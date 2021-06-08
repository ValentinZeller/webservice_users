<?php

class Dbconnect
{
    //Objet de connexion
    private $con;

    //Constructeur
    function __construct() {}

    function connect() {
        include_once('constantes.php');
        
        $this->con = new mysqli(SERVERNAME,USERNAME,PASSWORD,DBNAME);
        //Tentative de connexion retourne mysqli_connect_errno

        if (mysqli_connect_errno()) {
            //Problème
            //echo "Problème de connexion : ". mysqli_connect_err();
        } else {
            //echo "Connexion réussie <br/>";
        }
       
        return $this->con;
    }
}
?>
