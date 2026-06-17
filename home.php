<?php
    //require_once 'db.php'

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if(!empty($data['name']) && !empty($data['pswd'])){
        $name = $data['name'];
        $pswd = $data['pswd'];

        /*$stmt = $mysqli->prepare("SELECT name, pswd FROM Admin")*/
    }

?>