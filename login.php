<?php
//make session start here

$userdatafile_path = 'users/users.txt';
//call readUserDataFile to obtain the user data
$userdata_array = readUserDataFile($userdatafile_path);

function showLoginTitle() {
    echo 'Login';
}

function showLoginHeader() {
    echo 'Login';
}

function showLoginContent() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = validateLoginForm();
        if ($data['valid']) {
            $result = authenticateUser($data['email'], $data['pass']);
            processAuthentication($result);
            // //display submitted data if $valid is true
            // if (checkLogin($data['email'], $data['pass'], $userdata_array)) {
            //     // session_start();
            //     // $_SESSION['email'] = $email;
            //     // echo $_SESSION['email'];
            //     echo 'JUIST!';
            // } else {
            //     showLoginForm($data);
            // }
        } else {
            //display contact form if $valid is false
            showLoginForm($data);
        }
    } else {
        //display contact form by default if not a POST request
        showLoginForm(array());
    }
}

function validateLoginForm() {
    $email = $pass = '';
    $emailErr = $passErr = '';
    $valid = false;

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

    if (empty($emailErr) && empty($passErr)) {
        $valid = true;
    }

    return array('email' => $email, 'emailErr' => $emailErr, 'pass' => $pass, 'passErr' => $passErr, 'valid' => $valid);
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

function processAuthentication($result) {


    switch ($result['result']) {
        case RESULT_UNKNOWN_USER;
            $emailunknownErr = "E-mailadres is onbekend";
            return ['emailunknownErr' => $emailunknownErr];
            echo 'Unknown user';
            break;
        case RESULT_WRONG_PASSWORD;
            $wrongpassErr = "Wachtwoord is onjuist";
            echo 'Wrong password';
            break;
        case RESULT_OK;
            echo 'Authentication succesfull';
            break;
    }
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

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showLoginForm($data) {
    echo '
    <form method="post" action="index.php">
        <p><span class="error"><strong>* Vereist veld</strong></span></p>
        <ul class="flex-outer">

            <li>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="' . getArrayValue($data,'email') . '">
                <span class="error">* ' . getArrayValue($data,'emailErr') . getArrayValue($data,'emailunknownErr') . '</span>
            </li>

            <li>
                <label for="pass">Wachtwoord:</label>
                <input type="password" id="pass" name="pass" value="' . getArrayValue($data,'pass') . '">
                <span class="error">* ' . getArrayValue($data,'passErr') . getArrayValue($data,'passwrongErr') . '</span>
            </li>

            <li>
            <button type="submit" name="page" value="login">Verstuur</button>
            </li>

        </ul>';
}

?>