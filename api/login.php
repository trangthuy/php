<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];
     
        // get the user by email and password
        $user = $db->getUserByEmailAndPassword($email, $password);
     
        if ($user != false) {
            // user is found
            $response["result"]["firstName"] = $user["first_name"];
            $response["result"]["lastName"] = $user["last_name"];
            $response["result"]["displayName"] = $user["display_name"];
            $response["result"]["gender"] = $user["gender"];
            $response["result"]["email"] = $user["email"];
            $response["result"]["createdTime"] = $user["created_at"];
            $response["result"]["updatedTime"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user is not found with the credentials
            $response["statusCode"] = 9300;
            $response["statusMsg"] = "Login credentials are wrong. Please try again!";
            echo json_encode($response);
        }
    } else {
        $response["statusCode"] = 9200;
        $response["statusMsg"] = "Required parameters email or password is missing!";
        echo json_encode($response);
    }
?>