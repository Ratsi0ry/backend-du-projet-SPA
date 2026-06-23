<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

   if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        exit(0); 
    }

    require_once 'db.php';

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(!empty($data['username']) && !empty($data['pswd'])){
        $username = $data['username'];
        $pswd = $data['pswd'];

        $stmt = $mysqli->prepare("SELECT * FROM Admin WHERE username = ? LIMIT 1");
    
        if($stmt) {
            $stmt->bind_param("s", $username);

            $stmt->execute();

            //recuperation de la resultat su requete
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();

            if($admin){
                if($pswd==$admin['pswd']){
                    echo json_encode([
                        "success" => "true",
                        "message" => "mot de passe verifier"
                    ]);
                }else{
                    echo json_encode([
                        "success" => "false",
                        "message" => "mot de passe incorrect"
                    ]);
                }
            } else {
                 echo json_encode([
                        "sucess" => "false",
                        "message" => "nom d'utilisateur false"
                    ]);
            }
        }

    } else {
        echo json_encode([
            "successs" => false,
            "message" => "Erreur de prepartion SQL"
        ]);
    }

?>