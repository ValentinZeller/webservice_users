<?php

include_once(dirname(__FILE__).'\includes\DbOperation.php');
$db = new DbOperation();

//Requête HTTP Post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //Création d'un utilisateur si les champs sont remplis
    if ( isset($_POST['currentuser'])  AND(isset($_POST['username']) OR isset($_POST['email']) OR isset($_POST['localite']) OR isset($_POST['ddn']))) {

        $response['message'] = "";
        $resultUsername = false;
        $resultEmail = false;
        $resultLocalite = false;
        $resultDdn = false;
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $resultEmail = $db->updateEmail($_POST['email'],$_POST['currentuser']);
            if ($resultEmail) {
                $response['message'] .= "Email mis à jour; ";
            } else {
                $response['message'] .= "Email pas mise à jour";
            }
            
        }

        if (isset($_POST['localite']) && !empty($_POST['localite'])) {
            $resultLocalite = $db->updateLocalite($_POST['localite'],$_POST['currentuser']);
            
            if ($resultLocalite) {
                $response['message'] .= "Localite mise à jour; ";
            } else {
                $response['message'] .= "Localite pas mis à jour; ";
            }
            
        }

        if (isset($_POST['ddn']) && !empty($_POST['ddn'])) {
            $resultDdn = $db->updateDdn($_POST['ddn'],$_POST['currentuser']);
            if ($resultDdn) {
                $response['message'] .= "Date de naissance mise à jour; ";
            } else {
                $response['message'] .= "Date de naissance pas mis à jour; ";
            }
            
        }

        if (isset($_POST['username']) && !empty($_POST['username'])) {
            $resultUsername = $db->updateUsername($_POST['username'],$_POST['currentuser']);
            if ($resultUsername) {
                $response['message'] .= "Username mis à jour; ";
            } else {
                $response['message'] .= "Username pas mis à jour; ";
            }
            
        }

        if ($response['message'].equals('')) {
            $response['messsage'] = "Certaines donnees n'ont pas été mises à jour";
        }

        $response['error'] = !($resultUsername && $resultEmail && $resultLocalite && $resultDdn);
        $response['status'] = $resultUsername || $resultEmail || $resultLocalite || $resultDdn;

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