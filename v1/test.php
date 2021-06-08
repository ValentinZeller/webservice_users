<?php
require_once('includes/Dbconnect.php');

//echo $_SERVER['REQUEST_METHOD'];

//Création utilisateur
function createUser($username,$pass,$email) {
    //Connexion
    $dbconnect = new Dbconnect;
    $con = $dbconnect->connect();
    //Vérifie si l'utilisateur existe déjà
    if (isUserExist($username,$email,$con)) {
        echo "Utilisateur existe déjà";
        return 0;
    } else {
        $password = md5($pass);
        //Préparation de la requête
        $req = "INSERT INTO Users(username,password,email) VALUES (?,?,?)";

        $stmt = $con->prepare($req);
        $stmt->bind_param("sss",$username,$password,$email);
        if ($stmt->execute()) {
            echo "Nouvel utilisateur créé";
            return 1;
        } else {
            echo "Erreur d'insertion";
            return 2;
        }
    }
}

//Vérifier si l'utilisateur existe déjà
function isUserExist($username,$email,$con) {
    $req = "SELECT * FROM USERS WHERE username = ? OR email = ?";

    $stmt = $con->prepare($req);
    $stmt->bind_param("ss",$username,$email);

    $stmt->execute();
    $stmt->store_result(); //store_result : stocker le résultat de la resultat

    return $stmt->num_rows > 0; //num_rows : nombre de ligne trouvé
}

//Vérifier si un utilisateur correspond aux informations de login
function userLogin($username,$pass) {
    //Connexion
    $dbconnect = new Dbconnect;
    $con = $dbconnect->connect();

    $req = "SELECT * FROM USERS WHERE username = ? AND password = ?";

    $password = md5($pass);

    $stmt = $con->prepare($req);
    $stmt->bind_param("ss",$username,$password);

    $stmt->execute();
    $stmt->store_result();

    return $stmt->num_rows > 0;

}

//Récupérer un utilisateur par son username
function getUserByUsername($username) {
    $dbconnect = new Dbconnect;
    $con = $dbconnect->connect();

    $req = "SELECT * FROM USERS WHERE username = ?";

    $stmt = $con->prepare($req);
    $stmt->bind_param("s",$username);

    $stmt->execute();

    return $stmt->get_result()->fetch_assoc(); //
}

//Création d'un utilisateur si les champs sont remplis
if (isset($_GET['username']) AND !empty($_GET['username'])) {
    createUser($_GET['username'],$_GET['password'],$_GET['email']);
}

//Login
if (isset($_GET['login_username']) AND !empty($_GET['login_username'])) {
    if (userLogin($_GET['login_username'],$_GET['login_password'])) {
        echo "Login réussie";
    } else {
        echo "Identifiants incorrects";
    }
}

//Trouver utilisateur
if (isset($_GET['find_username']) AND !empty($_GET['find_username'])) {
    $result = getUserByUsername($_GET['find_username']);
    if ($result) {
        foreach($result as $key => $value) {
            echo $key." : ".$value."<br/>";
        }
    } else {
        echo "Aucun utilisateur trouvé";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Android</title>
    <style>
        body {
        background-color : #B3D4FF;
        font-family : "Roboto";
        }
    </style>
</head>
<body>
    <h1>Gestion utilisateurs</h1>
    <form id="form_create" method='get' action='#'>
        <fieldset>
            <legend>Création utilisateur</legend>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required/>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required/>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required/>
        </div>
        <input type="submit" id="submit" name="submit" value="Créer" />
    </fieldset>
    </form>

    <form id="form_login" method='get' action='#'>
    <fieldset>
    <legend>Login</legend>
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="login_username" required/>
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" id="password" name="login_password" required/>
        </div>
        <input type="submit" id="submit" name="submit" value="Login" />
    </fieldset>
    </form>

    <form id="form_user" method='get' action='#'>
    <fieldset>
    <legend>Trouver un utilisateur</legend>
        <div>
            <label for="user">Username</label>
            <input type="text" id="user" name="find_username" required/>

        <input type="submit" id="submit" name="submit" value="Trouver" />
    </fieldset>
    </form>
</body>
</html>