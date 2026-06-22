<?php 
    require_once 'db.php';
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // Statistiques globales
    $globalStmt = $mysqli->prepare("SELECT 
        MIN(tauxJournalier) as minLoyer, 
        MAX(tauxJournalier) as maxLoyer,
        COUNT(*) as totalClients,
        AVG(tauxJournalier) as moyenneLoyer
        FROM Client");
    
    $stats = [
        'minLoyer' => 0,
        'maxLoyer' => 0,
        'totalClients' => 0,
        'moyenneLoyer' => 0,
        'parVoiture' => []
    ];

    if($globalStmt->execute()) {
        $globalResult = $globalStmt->get_result();// conversion donnee mysql en tableau php
        $globalRow = $globalResult->fetch_assoc();// traitement a la ligne du base de donnee 
        $stats['minLoyer'] = $globalRow['minLoyer'];
        $stats['maxLoyer'] = $globalRow['maxLoyer'];
        $stats['totalClients'] = $globalRow['totalClients'];
        $stats['moyenneLoyer'] = round($globalRow['moyenneLoyer'], 2);
    }

    // Nombre de locations par voiture
    $carStmt = $mysqli->prepare("SELECT car, COUNT(*) as count FROM Client GROUP BY car");
    
    if($carStmt->execute()) {
        $carResult = $carStmt->get_result();
        while($row = $carResult->fetch_assoc()) {
            $stats['parVoiture'][] = [
                'car' => $row['car'],
                'count' => $row['count']
            ];
        }
    }
    
    echo json_encode(['status' => 'success', 'data' => $stats]);
    $mysqli->close();
?>

