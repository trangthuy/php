<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_POST['delete'])) {

        $delTrue = $_POST['delete'];
     
        if (($delTrue == 1)) {
            $checkDeleted = $db->deleteUsers();
            if($checkDeleted) {
                echo json_encode($response);
            } else {
                $response["statusCode"] = 9600;
                $response["statusMsg"] = "Unknown error occurred in delete!";
                echo json_encode($response);
            }
        } else {
                $response["statusCode"] = 9400;
                $response["statusMsg"] = "Delete all user fail!";
                echo json_encode($response);
            }
    } else {
        $response["statusCode"] = 9200;
        $response["statusMsg"] = "Required parameters (delete) is missing!";
        echo json_encode($response);
    }
?>