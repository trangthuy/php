<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_POST['email'])) {

        $email = $_POST['email'];
     
        if ($db->isUserExisted($email)) {
            $checkDeleted = $db->deleteUserByEmail($email);
            if($checkDeleted) {
                echo json_encode($response);
            } else {
                $response["statusCode"] = 9000;
                $response["statusMsg"] = "Unknown error occurred in delete!";
                echo json_encode($response);
            }
        } else {
            // user is not found with the credentials
            $response["statusCode"] = 9400;
            $response["statusMsg"] = "Email is not exist!";
            echo json_encode($response);
        }
    } else {
        $response["statusCode"] = 9200;
        $response["statusMsg"] = "Required parameters email is missing!";
        echo json_encode($response);
    }
?>