<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_GET['email'])) {

        $email = $_GET['email'];

        // get the user by email
        $user = $db->getUserByEmail($email);
     
        if ($user != false) {
            // user is found
            $response["result"]["userName"] = $user["username"];
            $response["result"]["email"] = $user["email"];
            $response["result"]["createdTime"] = $user["created_at"];
            $response["result"]["updatedTime"] = $user["updated_at"];
            echo json_encode($response);
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