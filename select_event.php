<?php
    header("Content-Type: application/json"); 
    ini_set("session.cookie_httponly", 1);

    require 'database.php';
    session_start();

    $json_str = file_get_contents("php://input");
    $json_obj = json_decode($json_str, true);
   
    // Get variables from js.
    $day = htmlentities((int) $json_obj['day']);
    $month = htmlentities((int) $json_obj['month']);
    $year = htmlentities((int) $json_obj['year']);
    $usr = htmlentities($_SESSION['username']);

    // Check if username is valid
    if(!preg_match('/^[\w_\-\ ]+$/', $usr))
    {
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    
    // Check if username is set
    if (isset($_SESSION['username']))
    {
        // Get all event names from specific date
        $stmt = $mysqli->prepare("SELECT event_name, event_id, month, day, year, time FROM events WHERE username = ? AND day = ? AND month = ? AND year = ?");

        if (!$stmt)
        {
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }
        $stmt->bind_param('siii', $usr, $day, $month, $year);     
        $stmt->execute();
        $stmt->bind_result($eventName, $eventID, $eventM, $eventD, $eventY, $time);

        // Store all events and times in this array
        $events = array();

        while($stmt->fetch())
        {
            if ($time == 0)
            {
                $time = "0000";
            }

            $time = substr($time, 0, 2).":".substr($time, 2);
            array_push($events, [$eventName, $eventID, $eventM, $eventD, $eventY, $time]);
        }

        $stmt->close();

        if (!empty($events))
        {
            // If our array is not empty, then we return success true
            echo json_encode(array(
                "success" => true,
                "events" => $events
            ));
            exit;
        }
        else
        {
            // Else don't send anything
            echo json_encode(array(
                "success" => false
            ));
            exit;
        }
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