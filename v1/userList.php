<?php
/*

get User

Affiche l'utilisateur recherché
*/
include_once(dirname(__FILE__).'\includes\DbOperation.php');
$db = new DbOperation();

//Requête HTTP GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username']) AND !empty($_GET['username'])) {
        $result = $db->getUserByUsername($_GET['username']);
        if ($result) {
            $response = array(
                'status' => true,
                'error' => false,
                'message' => 'Utilisateur trouvé',
                'username' => $result['username'],
                'email' =>  $result['email']
            );
        } else {
            $response = array(
                'status'=>false,
                'error'=>true,
                'message'=>'Aucun utilisateur trouvé'
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

