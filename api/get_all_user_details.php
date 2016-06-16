<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
        
    $users = $db->getUsers();
    if (!empty($users)) {
        $response["result"] = $users;
        echo json_encode($response);
    } else {
       $response["statusCode"] = 9500;
       $response["statusMsg"] = "No user found!";
       echo json_encode($response);
    }
?>