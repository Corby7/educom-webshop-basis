<?php
//make session start here

$userdatafile_path = 'users/users.txt';
//call readUserDataFile to obtain the userdata
$userdata_array = readUserDataFile($userdatafile_path);

function showLoginTitle() {
    echo 'Login';
}

function showLoginHeader() {
    echo 'Login';
}

function showLoginContent() {
    $inputdata = initializeContactData();
    

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateLoginForm($inputdata);
        if ($inputdata['valid']) {
            // extract values from the $inputdata array
            extract($inputdata);
            $result = authenticateUser($email, $pass);
            processAuthentication($result, $inputdata);
        } else {
            //display contact form if $valid is false
            showLoginForm($inputdata);
        }
    } else {
        //display contact form by default if not a POST request
        showLoginForm($inputdata);
    }
}

function initializeContactData() {
    return array(
        'email' => '',
        'emailErr' => '',
        'emailunknownErr' => '',
        'pass' => '',
        'passErr' => '',
        'wrongpassErr' => '',
        'valid' => ''
    );
}

function validateLoginForm($inputdata) {
    // extract values from the $inputdata array
    extract($inputdata);

    if (empty($_POST["email"])) {
        $emailErr = "Email is vereist";
    } else {
        $email = test_input($_POST["email"]);
    }

    if (empty($_POST["pass"])) {
        $passErr = "Wachtwoord is vereist";
    } else {
        $pass = test_input($_POST["pass"]);
    }

    $valid = empty($emailErr) && empty($passErr);

    return compact ('email', 'pass', 'emailErr', 'passErr', 'emailunknownErr', 'wrongpassErr', 'valid');
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

function processAuthentication($result, $inputdata) {
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

function findUserByEmail($email) {
    global $userdata_array;

    foreach ($userdata_array as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null; //return null if no user with the given email is found
}

function test_input($inputdata) {
    $inputdata = trim($inputdata);
    $inputdata = stripslashes($inputdata);
    $inputdata = htmlspecialchars($inputdata);
    return $inputdata;
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

        </ul>';
}

?>