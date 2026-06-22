<?php 

     if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
        exit(0); 
    }

    require_once 'db.php';

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    //debug
    error_log("Data reçue : " . print_r($data, true));  
    
    if(!empty($data['name']) && !empty($data['fstName']) && !empty($data['tel']) && !empty($data['car']) && !empty($data['dayBegin']) && !empty($data['dayEnd']) && !empty($data['tauxJournalier'])) {
        $name = $data['name'];
        $fstName = $data['fstName'];
        $tel = $data['tel'];
        $car = $data['car'];
        $dayBegin = $data['dayBegin'];
        $dayEnd = $data['dayEnd'];
        $tauxJournalier = $data['tauxJournalier'];
    

        $stmt = $mysqli->prepare("INSERT INTO Client(name,fstName,tel,car,dayBegin,dayEnd,tauxJournalier) VALUES (?,?,?,?,?,?,?)"); 

    if($stmt) {
            //convention variables
            $stmt->bind_param("ssisssi", $name, $fstName, $tel, $car, $dayBegin, $dayEnd, $tauxJournalier);

            if($stmt->execute()){
                echo json_encode(["status" => "success", "message" => "Information enregistrée!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Erreur lors de l'exécution: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Erreur de préparation: " . $mysqli->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Veulliez remplir tous le champ "]);
    }

    $mysqli->close();

?>