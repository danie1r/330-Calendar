<?php
    // Checks if there exists an event at selected date for currently logged in user
    header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
    ini_set("session.cookie_httponly", 1);
    require 'database.php';
    session_start();

    // Retrieve data
    $json_input = file_get_contents("php://input");
    $json_object = json_decode($json_input, true);
    $month = htmlentities($json_object['month']);
    $day = htmlentities($json_object['day']);
    $year = htmlentities($json_object['year']);

    if (isset($_SESSION['username']))
    {
        $username = $_SESSION['username'];

        // Then user is logged in
        $stmt = $mysqli->prepare("SELECT COUNT(*) FROM events WHERE year = ? and month = ? and day = ? and username = ?");
        if(!$stmt){
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }

        // Bind the parameter
        $stmt->bind_param('iiis', $year, $month, $day, $username);     
        $stmt->execute();

        // Bind the results
        $stmt->bind_result($cnt);
        $stmt->fetch();
        $stmt->close();

        // Check if there was an event on that date
        if($cnt >= 1)
        {
            // There was at least one event on that date
            echo json_encode(array(
                "success" => true
            ));
            exit;
        } 
        else
        {
            // Else exit
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }
    }
    else
    {
        // Else exit
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
?>