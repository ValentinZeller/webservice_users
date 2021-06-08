<?php
include_once(dirname(__FILE__).'/Dbconnect.php');

class DbOperation {

    private $con;

    function __construct() {
        $db = new Dbconnect();
        $this->con = $db->connect();
    }

        //Création utilisateur
    function createUser($username,$pass,$email) {
        //Vérifie si l'utilisateur existe déjà
        if ($this->isUserExist($username,$email)) {
            return 0;
        } else {
            //hashage du mot de passe
            $password = md5($pass);

            //Préparation de la requête
            $req = "INSERT INTO Users(username,password,email) VALUES (?,?,?)";
            $stmt = $this->con->prepare($req);
            $stmt->bind_param("sss",$username,$password,$email);

            //Execute
            if ($stmt->execute()) {
                return 1;
            } else {
                return 2;
            }
        }
    }

    //Vérifier si l'utilisateur existe déjà
    function isUserExist($username,$email) {
        $req = "SELECT * FROM USERS WHERE username = ? OR email = ?";

        //Préparation de la requête
        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$username,$email);

        $stmt->execute();
        $stmt->store_result(); //store_result : stocker le résultat de la resultat

        return $stmt->num_rows > 0; //num_rows : nombre de ligne trouvé
    }

    //Vérifier si un utilisateur correspond aux informations de login
    function userLogin($username,$pass) {
        $req = "SELECT * FROM USERS WHERE username = ? AND password = ?";

        //hashage du mot de passe
        $password = md5($pass);

        //Préparation de la requête
        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$username,$password);

        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;

    }

    //Récupérer un utilisateur par son username
    function getUserByUsername($username) {
        $req = "SELECT * FROM USERS WHERE username = ?";

        //Préparation de la requête
        $stmt = $this->con->prepare($req);
        $stmt->bind_param("s",$username);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc(); //
    }

    //Récupérer un utilisateur par un ou plusieurs paramètres
    function getUsersByParams($params) {


        
        $req = "SELECT * FROM USERS WHERE (UPPER(USERNAME) LIKE UPPER('%".$params['username']."%')) AND (UPPER(EMAIL) LIKE UPPER('%".$params['email']."%')) AND (UPPER(LOCALITE) LIKE UPPER('%".$params['localite']."%')) AND (UPPER(DDN) LIKE UPPER('%".$params['ddn']."%'))";
        
        
        //$req = "SELECT * FROM USERS WHERE (LCASE(username) = ?) OR (LCASE(email) = ?) OR (LCASE(localite) = ?) OR  (ddn = STR_TO_DATE(?,'%Y-%m-%d'))";
        

        $stmt = $this->con->prepare($req);
        //$stmt->bind_param("ssss",$params['username'],$params['email'],$params['localite'],$params['ddn']);
        
        $stmt->execute();

        $result = $stmt->get_result();

        $arrayResult = array();

        while ($row = $result->fetch_assoc()) {
          array_push($arrayResult,$row);
        }

        return $arrayResult;
    }

    function updateUsername($username,$currentuser) {
        if (isUserExist($username,"")) {
            return 0;
        }
        $req = "UPDATE USERS SET username = ? WHERE username = ?";

        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$username,$currentuser);
        $stmt->execute();

        return $this->con->affected_rows > 0;
    }

    function updateEmail($email,$currentuser) {
        if (isUserExist("",$email)) {
            return 0;
        }
        $req = "UPDATE USERS SET email = ? WHERE username = ?";

        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$email,$currentuser);
        $stmt->execute();

        return $this->con->affected_rows > 0;
    }

    function updateLocalite($localite,$currentuser) {
        $req = "UPDATE USERS SET username = ? WHERE username = ?";

        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$localite,$currentuser);
        $stmt->execute();

        return $this->con->affected_rows > 0;
    }
    function updateDdn($ddn,$currentuser) {
        $req = "UPDATE USERS SET username = ? WHERE username = ?";

        $stmt = $this->con->prepare($req);
        $stmt->bind_param("ss",$username,$currentuser);
        $stmt->execute();

        return $this->con->affected_rows > 0;
    }

    function deleteUser($username) {
        $req = "DELETE FROM USERS WHERE username = ?";

        $stmt = $this->con->prepare($req);
        $stmt->bind_param("s",$username);

        $stmt->execute();

        return $this->con->affected_rows > 0;
    }

}

?>