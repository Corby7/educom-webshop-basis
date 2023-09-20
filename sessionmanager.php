<?php

function loginUser($name) {
    $_SESSION['name'] = $name;
}

function isUserLoggedIn() {
    return isset($_SESSION['name']) && !empty($_SESSION['name']);
}

function getLoggedInUserName() {}

function logoutUser() {
    // remove all session variables
    session_unset();

    // destroy the session
    session_destroy(); 
}

?>