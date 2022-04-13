<?php
    // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
    header("Content-Type: application/json"); 
    ini_set("session.cookie_httponly", 1);

    require 'database.php';
    session_start();

    // Get variable from js
    $json_str = file_get_contents("php://input");
    $json_obj = json_decode($json_str, true);
    $id = htmlentities($json_obj['id']);
    $token = $json_obj['token'];

    // Check for cross site forgery.
    if (!hash_equals($_SESSION["token"], $token)){
        echo json_encode(array(
            "success" => false,
            "message" => "Request forgery detected."

        ));
        exit; 
    }
    // Check if username is set
    if (isset($_SESSION['username']))
    {
        // Display information for specific event based off its id
        $stmt = $mysqli->prepare("SELECT event_name, month, day, year, time , location from events where event_id = ?");

        if (!$stmt)
        {
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }
        
        $stmt->bind_param('i', $id);     
        $stmt->execute();
        $stmt->bind_result($eventName, $eventMonth, $eventDay, $eventYear, $eventTime, $eventLoc); //bind results to variables
        $stmt->fetch();
        $stmt->close();

        if ($eventTime == 0)
        {
            $eventTime = "0000";
        }

        // Single digit months and days need a 0 in front of it
        if ($eventMonth <= 9)
        {
            $eventMonth = "0".$eventMonth;
        }

        if ($eventDay <= 9)
        {
            $eventDay = "0".$eventDay;
        }

        // In case user for some reason (as a joke) puts a year before 1000 and the input to HTML date needs 4 digits
        if ($eventYear <= 9)
        {
            $eventYear = "000".$eventYear;
        }
        else if ($eventYear <= 99)
        {
            $eventYear = "00".$eventYear;
        }
        else if ($eventYear <= 999)
        {
            $eventYear = "0".$eventYear;
        }

        // Successful retrieval of information
        echo json_encode(array(
            "success" => true,
            "id" => $id,
            "event_name" => $eventName,
            "year" => $eventYear,
            "month" => $eventMonth,
            "day" => $eventDay,
            "time" => $eventTime,
            "loc" => $eventLoc
        ));
        exit;
    }
    else
    {
        // Else fail
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
?>