<?php

require('filerepository.php');

function getSalutation($gender) {
    switch ($gender) {
        case 'male':
            return 'meneer';
        case 'female':
            return 'mevrouw';
        default:
            return;
    }
}

//if email is known already, show error
function handleKnownEmail($inputdata) {
    $inputdata['emailknownErr'] = "E-mailadres is reeds bekend";
    showRegisterForm($inputdata);
}

//if email is unknown, save new userdata, send to login
function handleUnknownEmail($email, $name, $pass) {
    echo "email is not found";
    saveUser($email, $name, $pass);
    //not sure if i can use header?
    header('Location: index.php?page=login');
    exit; //exit to prevent further execution
}

define("RESULT_OK", 0);
define("RESULT_UNKNOWN_USER", -1);
define("RESULT_WRONG_PASSWORD", -2);

function authenticateUser($email, $pass) {
    $user = findUserByEmail($email);
    
    if(empty($user)) {
        return ['result' => RESULT_UNKNOWN_USER]; //no user found with this email
    }

    if ($user['pass'] !== $pass) {
        return ['result' => RESULT_WRONG_PASSWORD]; //password of user does not match
    }

    return ['result' => RESULT_OK, 'user' => $user]; //email matches password
}

function handleAuthentication($result, $inputdata) {
    switch ($result['result']) {
        case RESULT_UNKNOWN_USER;
            $inputdata['emailunknownErr'] = "E-mailadres is onbekend";
            break;
        case RESULT_WRONG_PASSWORD;
            $inputdata['wrongpassErr'] = "Wachtwoord is onjuist";
            break;
        case RESULT_OK;
            $user = $result['user'];
            echo 'Authentication successful: ' . $user['name'] . ';';
            loginUser($user['name']);
            
    }

    showLoginForm($inputdata);
}

function doesEmailExist($email) {
    $user = findUserByEmail($email);
    return !empty($user);
}

function storeUser($email, $name, $pass) {
    //room for future password encryption
    saveUser($email, $name, $pass);
}
?>