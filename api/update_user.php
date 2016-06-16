<?php
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();
     
    $response = array("statusCode" => 1000, "statusMsg" => "Success", "result" => NULL);
     
    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['display_name']) && isset($_POST['gender']) && isset($_POST['email'])) {

        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $displayName = $_POST['display_name'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
     
        if ($db->isUserExisted($email)) {
            $user = $db->updateUser($firstName, $lastName, $displayName, $gender, $email);
            if($user) {
                $response["result"]["firstName"] = $user["first_name"];
                $response["result"]["lastName"] = $user["last_name"];
                $response["result"]["displayName"] = $user["display_name"];
                $response["result"]["gender"] = $user["gender"];
                $response["result"]["email"] = $user["email"];
                $response["result"]["createdTime"] = $user["created_at"];
                $response["result"]["updatedTime"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                $response["statusCode"] = 9000;
                $response["statusMsg"] = "Unknown error occurred in update!";
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
        $response["statusMsg"] = "Required parameters (first_name, last_name, display_name, gender, email) is missing!";
        echo json_encode($response);
    }
?>