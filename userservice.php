<?php

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

// function handleAuthentication($result, $inputdata) {
//     switch ($result['result']) {
//         case RESULT_UNKNOWN_USER;
//             $inputdata['emailunknownErr'] = "E-mailadres is onbekend";
//             break;
//         case RESULT_WRONG_PASSWORD;
//             $inputdata['wrongpassErr'] = "Wachtwoord is onjuist";
//             break;
//         case RESULT_OK;
//             $user = $result['user'];
//             echo 'Authentication successful: ' . $user['name'] . ';';
//             loginUser($user['name']);
//     }
// }

function doesEmailExist($email) {
    $user = findUserByEmail($email);
    return !empty($user);
}

function storeUser($email, $name, $pass) {
    //room for future password encryption
    saveUser($email, $name, $pass);
}
?>