<?php
    header("Content-Type: application/json");

    ini_set("session.cookie_httponly", 1);

    session_start();

    $json_str = file_get_contents("php://input");
    $json_obj = json_decode($json_str, true);
   
    // Get variables from js
    $day = htmlentities((int) $json_obj['day']);
    $month = htmlentities((int) $json_obj['month']);
    $year = htmlentities((int) $json_obj['year']);
    $usr = htmlentities($_SESSION['username']);

    // Check if username is valid.
    if(!preg_match('/^[\w_\-\ ]+$/', $usr))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid username"
        ));
        exit;
    }
    require 'database.php';

    // Check if user is logged in.
    if (isset($_SESSION['username']))
    {
        $stmt = $mysqli->prepare("SELECT event_name, time, location FROM events WHERE username = ? AND day = ? AND month = ? AND year = ?");

        if (!$stmt)
        {
            echo json_encode(array(
                "success" => false,
                "message" => "Query Prep Failed: $mysqli -> error"
            ));
            exit;
        }
        $stmt->bind_param('siii', $usr, $day, $month, $year);     
        $stmt->execute();
        $stmt->bind_result($eventName, $time, $location);

        // Store all events and times in this array
        $events= array();
        $alt = array("No events planned this day");
        while($stmt->fetch())
        {
            if ($time == 0)
            {
                $time = "0000";
            }

            if ($location == "")
            {
                $location = "N/A";
            }
            array_push($events, "Event name: ".$eventName.", Time: ".substr($time, 0, 2).":".substr($time, 2).", Location: ".$location);
        }

        $stmt->close();

        // If event extraction was successful, output events.
        if (!empty($events))
        {
            echo json_encode(array(
                "success" => true,
                "log_in" => true,
                "events" => $events
            ));
            exit;
        }
        else
        {
            echo json_encode(array(
                "success" => true,
                "log_in" => true,
                "events" => $alt 
            ));
            exit;
        }
    }
    else
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Not signed in"
        ));
        exit;
    }
?>