<?php
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly",1);
    session_start();
    // retrieve contents
    $json_str = file_get_contents('php://input');
    // store data in array
    $json_obj = json_decode($json_str,true);

    // Get variable from js
    $title = htmlentities($json_obj['title']);
    $year = htmlentities(date('Y', strtotime($json_obj['date'])));
    $month = htmlentities(date('n', strtotime($json_obj['date'])));
    $day = htmlentities(date('d', strtotime($json_obj['date'])));
    $time = htmlentities($json_obj['time']);
    $loc = htmlentities($json_obj['location']);
    $group = htmlentities($json_obj['group']); // take in names of all the users the event is shared with.
    $grouparr = explode(",",$group); // extract each user from input and input into an array.
    $token = $json_obj['token'];

    require 'database.php';

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

    // If username is set, insert event to database.
    if(isset($_SESSION['username'])){
        // Check if no location information is given.
        if ($loc == "")
        {
            // Check if the user inputted group of users to share the event with.
            if (sizeof($grouparr) > 0 ){
                foreach ($grouparr as $person){
                    
                    $stmt = $mysqli->prepare("insert into events (username,year,month,day,time,event_name) values (?,?,?,?,?,?)");
                    
                    // if the username does not exist, skip.
                    if (!$stmt){
                        continue;
                    }
                    $stmt->bind_param('siiiis', $person, $year, $month, $day, $time,$title);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $stmt = $mysqli->prepare("insert into events (username,year,month,day,time,event_name) values (?,?,?,?,?,?)");
        
            if (!$stmt){
                echo json_encode(array(
                    "success" => false,
                    "message" => "Event Insertion Failed"
                ));
                exit;
            }
    
            $stmt->bind_param('siiiis', $_SESSION['username'], $year, $month, $day, $time,$title);
        }
        else
        {
            if (sizeof($grouparr) > 0 ){
                foreach ($grouparr as $person){
                    $stmt = $mysqli->prepare("insert into events (username,year,month,day,time,event_name, location) values (?,?,?,?,?,?,?)");
                    
                    if (!$stmt){
                        continue;
                    }
                    $stmt->bind_param('siiiiss', $person, $year, $month, $day, $time,$title,$loc);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            $stmt = $mysqli->prepare("insert into events (username,year,month,day,time,event_name, location) values (?,?,?,?,?,?,?)");
        
            if (!$stmt){
                echo json_encode(array(
                    "success" => false,
                    "message" => "Event Insertion Failed"
                ));
                exit;
            }

            $stmt->bind_param('siiiiss', $_SESSION['username'], $year, $month, $day, $time,$title,$loc);
        }
        $stmt->execute();
        $stmt->close();
      
        echo json_encode(array(
            "success" => true,
            "message" => "Event Added Succesfully"
        ));
        
        
        exit;
    }
?>