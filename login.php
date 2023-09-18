<?php

function showLoginTitle() {
    echo 'Login';
}

function showLoginHeader() {
    echo 'Login';
}

function showLoginContent() {
    $userdatafile_path = 'users/users.txt';
    //call readUserDataFile to obtain the userdata
    $userdata_array = readUserDataFile($userdatafile_path);

    require('validations.php');
    $inputdata = initializeFormData('login');
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateLoginForm($inputdata);
        if ($inputdata['valid']) {
            // extract values from the $inputdata array
            extract($inputdata);

            $result = authenticateUser($email, $pass, $userdata_array);
            handleAuthentication($result, $inputdata);
        } else {
            //display contact form if $valid is false
            showLoginForm($inputdata);
        }
    } else {
        //display contact form by default if not a POST request
        showLoginForm($inputdata);
    }
}

function readUserDataFile($userdatafile_path) {
    global $userdata_array;

    //initialize an empty array to store the data
    $userdata_array = array();

    //open the file for reading or give error when unable to
    $usersfile = fopen($userdatafile_path, 'r') or die("Unable to open file!");

    while(!feof($usersfile)) {
        $line = fgets($usersfile);
        $values = explode('|', $line);

        if (count($values) === 3) {
            $userdata_array[] = array(
                'email' => trim($values[0]),
                'name' => trim($values[1]),
                'pass' => trim($values[2])
            );
        }
    }

    //close the file
    fclose($usersfile);
    return $userdata_array;
}

define("RESULT_OK", 0);
define("RESULT_UNKNOWN_USER", -1);
define("RESULT_WRONG_PASSWORD", -2);

function authenticateUser($email, $pass, $userdata_array) {
    $user = findUserByEmail($email, $userdata_array);
    
    if(empty($user)) {
        return ['result' => RESULT_UNKNOWN_USER]; //no user found with this email
    }

    if ($user['pass'] !== $pass) {
        return ['result' => RESULT_WRONG_PASSWORD]; //password of user does not match
    }

    return ['result' => RESULT_OK, 'user' => $user]; //email matches password
}

function findUserByEmail($email, $userdata_array) {
    foreach ($userdata_array as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null; //return null if no user with the given email is found
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
            echo 'Authentication succesfull';
            return; //exit early, no need to call showLoginForm()
    }

    showLoginForm($inputdata);
}

function showLoginForm($inputdata) {
    //extract values from the $userdata array
    extract($inputdata);

    echo '
    <form method="post" action="index.php">
        <p><span class="error"><strong>* Vereist veld</strong></span></p>
        <ul class="flex-outer">

            <li>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="' . $email . '">
                <span class="error">* ' . $emailErr . $emailunknownErr . '</span>
            </li>

            <li>
                <label for="pass">Wachtwoord:</label>
                <input type="password" id="pass" name="pass" value="' . $pass . '">
                <span class="error">* ' . $passErr . $wrongpassErr . '</span>
            </li>

            <li>
            <button type="submit" name="page" value="login">Verstuur</button>
            </li>

        </ul>
    </form>';
}

?>