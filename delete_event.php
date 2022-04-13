<?php
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);

    require 'database.php';
    session_start();

    $json_str = file_get_contents("php://input");
    $json_obj = json_decode($json_str, true);
   
    // Get variable from js
    $id = $json_obj['id'];
    $token = $json_obj['token'];

    // Check for cross-site forgery.
    if (!hash_equals($_SESSION["token"], $token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected."

        ));
        exit; 
    }
    if (isset($_SESSION['username']))
    {
        // Attempt to delete 
        $stmt = $mysqli->prepare("delete from events where event_id = ?");
        if (!$stmt){
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }
    
        $stmt->bind_param('i', $id);
        $stmt->execute();

        // Successful deletion
        echo json_encode(array(
            "success" => true
        ));
        exit;
    }
    else
    {
        // User not logged in
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
?>