<?php
    header("Content-Type: application/json");

    // Retrieve data
    $json_input = file_get_contents("php://input");
    $json_object = json_decode($json_input, true);
    $username = htmlentities($json_object['username']);

    require 'database.php';
    ini_set("session.cookie_httponly", 1);

    // Check if username input is valid.
    if(!preg_match('/^[\w_\-\ ]+$/', $username))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid username"
        ));
        exit;
    }

    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*), user_pass  FROM users WHERE username = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false,
            "message" => "Query Prep Failed"
        ));
        exit;
    }
    
    // Bind the parameter
    $stmt->bind_param('s', $username);     
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($cnt, $pwd_hash);
    $stmt->fetch();

    $pwd_guess = $json_object['password'];

    // Check if password input is valid.
    if(!preg_match('/^[\w_\-\ ]+$/', $pwd_guess))
    {
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid password"
        ));
        exit;
    }
    // Compare the submitted password to the actual password hash
    if($cnt == 1 && password_verify($pwd_guess, $pwd_hash))
    {
        session_start();
        // Login succeeded!
        $_SESSION['username'] = $username;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        echo json_encode(array(
            "success" => true,
            "username" => $username,
            "token" => $_SESSION['token'] //output current session's token.
        ));
        exit;
    } 
    else
    {
        // Login failed; redirect back to the login screen
        echo json_encode(array(
            "success" => false,
            "message" => "Invalid username or password given."
        ));
        session_destroy();
        exit;
    }
?>