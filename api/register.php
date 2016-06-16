<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
    
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_POST['username'])&& isset($_POST['email']) && isset($_POST['password'])) {
     
        $userName = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
     
        // check if user is already existed with the same email
        if ($db->isUserExisted($email)) {
            // user already existed
            $response["statusCode"] = 9100;
            $response["statusMsg"] = "User already existed with email: " . $email;
            echo json_encode($response);
        } else {
            // create a new user
            $user = $db->storeUser($userName, $email, $password);
            if ($user) {
                // user stored successfully
                $response["result"]["userName"] = $user["username"];
                $response["result"]["email"] = $user["email"];
                $response["result"]["createdTime"] = $user["created_at"];
                $response["result"]["updatedTime"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["statusCode"] = 9000;
                $response["statusMsg"] = "Unknown error occurred in registration!";
                echo json_encode($response);
            }
        }
    } else {
        $response["statusCode"] = 9200;
        $response["statusMsg"] = "Required parameters (username, email or password) is missing!";
        echo json_encode($response);
    }
?>