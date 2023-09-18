<?php

function loginUser($name) {
    $_SESSION["name"] = $name;
    echo 'Authentication successful. Welcome, ' . $_SESSION["name"];
}

function isUserLoggedIn() {
    return isset($_SESSION['name']) && !empty($_SESSION['name']);
}

function getLoggedInUserName() {

}

function logoutUser() {
   // remove all session variables
    session_unset();

    // destroy the session
    session_destroy(); 

    header("Location: index.php");
    exit;
}

?>