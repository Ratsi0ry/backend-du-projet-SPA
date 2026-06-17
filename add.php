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
    
    // DEBUG : voir ce qui arrive
    error_log("Data reçue : " . print_r($data, true));  
    
    if(!empty($data['name']) && !empty($data['fstName']) && !empty($data['tel']) && !empty($data['car']) && !empty($data['days']) && !empty($data['rate'])) {
        $name = $data['name'];
        $fstName = $data['fstName'];
        $tel = $data['tel'];
        $car = $data['car'];
        $days = $data['days'];
        $rate = $data['rate'];
    

        $stmt = $mysqli->prepare("INSERT INTO Client(name,fstName,tel,car,days,rate) VALUES (?,?,?,?,?,?)"); 

    if($stmt) {
            //convention variables
            $stmt->bind_param("sssiii", $name, $fstName, $tel, $car, $days, $rate);

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