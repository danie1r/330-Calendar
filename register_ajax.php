<?php
    header("Content-Type: application/json");
    ini_set("session.cookie_httponly", 1);
    // Retrieve data
    $json_input = file_get_contents("php://input");
    $json_object = json_decode($json_input, true);

    require 'database.php';

    $username = htmlentities($json_object['reg_user']);
    $password = htmlentities($json_object['reg_pass']);

    // Check if database entry exists in 'users'
    $stmt = $mysqli->prepare("SELECT COUNT(*) from users where username = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed: $mysqli->error"
        ));
        exit;
    }

    // Bind parameters
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Bind results
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    // Check is username and password is valid form.
    if(!preg_match('/^[\w_\-\ ]+$/ ', $username))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid username inputted."
        ));
        exit;
    }
    else if (!preg_match('/^[\w_\-\ ]+$/ ', $password))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid password inputted."
        ));
        exit;
    }
    else
    {
        if (strlen($username) > 0 && strlen($password) > 0)
        {         
            // If count == 1, then username inputted is duplicate
            if ($cnt == 1)
            {
                echo json_encode(array(
                    "success" => false,
                    "message" => "Username already exists."
                ));
                exit;
            }
            else
            {
                // Username is valid (i.e. insert into database)
                $stmt_write = $mysqli->prepare("insert into users (username, user_pass) values (?, ?)");
                if (!$stmt_write)
                {
                    echo json_encode(array(
                        "success" => false,
                        "message" => "Query Prep Failed: $mysqli->error"
                    ));
                    exit;
                }

                $stmt_write->bind_param('ss', $username, password_hash($password, PASSWORD_DEFAULT));
                $stmt_write->execute();
                $stmt_write->close();

                echo json_encode(array(
                    "success" => true,
                ));
                exit;
            }
        }
    }
    
?>