<?php

include_once(dirname(__FILE__).'\includes\DbOperation.php');
$db = new DbOperation();

//Requête HTTP Post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Création d'un utilisateur si les champs sont remplis
    if (isset($_POST['username'])) {
        $result = $db->deleteUser($_POST['username']);

        if ($result) {
            $response = array(
                'status' => true,
                'error' => false,
                'message' => 'Utilisateur supprimé'
            );

        } else {
            $response = array(
                'status' => false,
                'error' => true,
                'message' => 'Utilisateur non supprimé'
            );
        }
    } else {
        $response = array(
            'status' => true,
            'error' => false,
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