<?php 
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        exit(0); 
    }

    require_once 'db.php';

    //GET: rcuperation de tous les clients
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    
        $stmt = $mysqli->prepare("SELECT id, name, fstName, tel, car, dayBegin, dayEnd, tauxJournalier FROM Client");

        if($stmt->execute()) {
            $result = $stmt->get_result();
            $clients = [];

            while($row = $result->fetch_assoc()) {
                // Calcul du nombre de jrs
                $start = new DateTime($row['dayBegin']); //DateTime rend la date mysql utilisable a php en tant que super object
                $end = new DateTime($row['dayEnd']);
                $interval = $start->diff($end); //diff : ecart entre les dates
                $nbJours = $interval->days + 1; 

                $clients[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'fstName' => $row['fstName'],
                    'tel' => $row['tel'],
                    'car' => $row['car'],
                    'dayBegin' => $row['dayBegin'],
                    'dayEnd' => $row['dayEnd'],
                    'nbJours' => $nbJours,
                    'tauxJournalier' => $row['tauxJournalier']
                ];
            }
            
            echo json_encode(['status' => 'success', 'data' => $clients]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erreur: ' . $mysqli->error]);
        }
    }

    // POST: Modifier un client
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'update') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if(!empty($data['id']) && !empty($data['name']) && !empty($data['fstName']) && !empty($data['tel']) && !empty($data['car'])) {
            $stmt = $mysqli->prepare("UPDATE Client SET name=?, fstName=?, tel=?, car=? WHERE id=?");
            $stmt->bind_param("ssssi", $data['name'], $data['fstName'], $data['tel'], $data['car'], $data['id']);

            if($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Client modifié!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Données invalides']);
        }
    }

    // DELETE: Supprimer un client
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'delete') {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if(!empty($data['id'])) {
            $stmt = $mysqli->prepare("DELETE FROM Client WHERE id=?");
            $stmt->bind_param("i", $data['id']);

            if($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Client supprimé!']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID invalide']);
        }
    }

    $mysqli->close();
?>