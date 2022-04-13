<?php
    header("Content-Type: application/json"); 
    ini_set("session.cookie_httponly", 1);

    require 'database.php';
    session_start();

    $json_str = file_get_contents("php://input");
    $json_obj = json_decode($json_str, true);

    // Get variable from js.
    $id = htmlentities($json_obj['id']);
    $title = htmlentities($json_obj['title']);
    $year = htmlentities(date('Y', strtotime($json_obj['date'])));
    $month = htmlentities(date('n', strtotime($json_obj['date'])));
    $day = htmlentities(date('d', strtotime($json_obj['date'])));
    $time = htmlentities($json_obj['time']);
    $loc = htmlentities($json_obj['location']);
    $token = $json_obj['token'];

    // Check for cross-site forgery
    if (!hash_equals($_SESSION["token"], $token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected."

        ));
        exit; 
    }
    // Check if time is between 0000 - 2459
    if (!preg_match('/^([0-2][0-3][0-5][0-9]|[0-1][0-9][0-5][0-9])$/', $time))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid time inputted."
        ));
        exit;
    }

    // Check if user inputted an event title
    if (strlen($title) <= 0)
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid title inputted."
        ));
        exit;
    }

    // Check if user inputted a day, month, and year
    if (strlen(strtotime($json_obj['date']) <= 0))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid date inputted."
        ));
        exit;
    }

    // Check if username is set
    if (isset($_SESSION['username']))
    {
        // Display information for specific event based off its id
        $stmt = $mysqli->prepare("UPDATE events set event_name = ?, month = ?, day = ?, year = ?, time = ?, location = ? where event_id = ?");

        if (!$stmt)
        {
            echo json_encode(array(
                "success" => false,
                "message" => "Connection Failed"
            ));
            exit;
        }
        
        $stmt->bind_param('siiiisi', $title, $month, $day, $year, $time, $loc, $id);     
        $stmt->execute();
        $stmt->close();

        // Successful retrieval of information
        echo json_encode(array(
            "success" => true
        ));
        exit;
    }
    else
    {
        // Else fail
        echo json_encode(array(
            "success" => false,
            "message" => "No user logged in."
        ));
        exit;
    }
?>