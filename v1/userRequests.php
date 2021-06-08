<?php

include_once(dirname(__FILE__).'\includes\DbOperation.php');
$db = new DbOperation();

//Requête HTTP GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (isset($_GET['username']) || isset($_GET['email']) || isset($_GET['localite']) || isset($_GET['ddn'])) {
        $params;

        if (isset($_GET['username']) && !empty($_GET['username'])) {
            $params['username'] = strtolower($_GET['username']);
        } else {
            $params['username'] = "%";
        }
        if (isset($_GET['email'])&& !empty($_GET['email'])) {
            $params['email'] = strtolower($_GET['email']);
        } else {
            $params['email'] = "%";
        }
        if (isset($_GET['localite'])&& !empty($_GET['localite'])) {
            $params['localite'] = strtolower($_GET['localite']);
        } else {
            $params['localite'] = "%";
        }
        if (isset($_GET['ddn'])&& !empty($_GET['ddn'])) {
            $params['ddn'] = strtolower($_GET['ddn']);
        } else {
            $params['ddn'] = "%";
        }

        $result = $db->getUsersByParams($params);

        if ($result) {
            $response = array(
                'status' => true,
                'error' => false,
                'message' => 'Utilisateur trouve',
                'users' => $result
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