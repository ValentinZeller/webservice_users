<?php
/*

get User

Affiche l'utilisateur recherché
*/
include_once(dirname(__FILE__).'\includes\DbOperation.php');
$db = new DbOperation();

//Requête HTTP GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username']) && isset($_GET['password'])) {
        $result = $db->userLogin($_GET['username'],$_GET['password']);
        if ($result) {
            $user = $db->getUserByUsername($_GET['username']);
            $response = array(
                'status' => true,
                'error' => false,
                'message' => 'Connexion reussie',
                'username' => $user['username'],
                'email' =>  $user['email']
            );
        } else {
            $response = array(
                'status'=>false,
                'error'=>true,
                'message'=>'Mauvaise authentification'
            );
        }
    } else {
        $response = array(
            'status'=> false,
            'error'=>true,
            'message' => 'Donnees requises manquantes'
        );
    }
    
} else {
    $response = array(
        'status'=> false,
        'error'=>true,
        'message' => 'Requete invalide'
    );
}

echo json_encode($response);

?>

