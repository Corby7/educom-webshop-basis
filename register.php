<?php

function showRegisterTitle() {
    echo 'Register';
}

function showRegisterHeader() {
    echo 'Registreer Nu!';
}

function showRegisterContent() {
    $userdatafile_path = 'users/users.txt';
    //call readUserDataFile to obtain the user data
    $userdata_array = readUserDataFile($userdatafile_path);

    require('validations.php');
    $inputdata = initializeFormData('register');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateRegisterForm($inputdata);
        if ($inputdata['valid']) {
            // extract values from the $inputdata array
            extract($inputdata);

            //check if email is known, i.e. not null
            $emailKnown = findUserByEmail($email, $userdata_array) !== null;

            if ($emailKnown) {
                handleKnownEmail($inputdata);
            } else {
                handleUnknownEmail($email, $name, $pass);
            }
        } else {
            //display register form if $valid is false
            showRegisterForm($inputdata);
        }
    } else {
        //display register form by default if not a POST request
        showRegisterForm($inputdata);
    }
}

function readUserDataFile($userdatafile_path) {
    $userdata_array = array();
    $usersfile = fopen($userdatafile_path, 'r') or die("Unable to open file!");

    while (!feof($usersfile)) {
        $line = fgets($usersfile);
        $values = explode('|', $line);

        if (count($values) === 3) {
            $userdata_array[] = array(
                'email' => trim($values[0]),
                'name' => trim($values[1]),
                'password' => trim($values[2])
            );
        }
    }

    //close the file
    fclose($usersfile);
    return $userdata_array;
}

function validatePassword($pass, $repeatpass) {
    if ($pass !== $repeatpass) {
        return "Wachtwoorden komen niet overeen";
    }
    return '';
}

function findUserByEmail($email, $userdata_array) {
    foreach ($userdata_array as $user) {
        if ($user['email'] === $email) {
            return $user;
        }
    }
    return null; //return null if no user with the given email is found
}

//if email is known already, show error
function handleKnownEmail($inputdata) {
    $inputdata['emailknownErr'] = "E-mailadres is reeds bekend";
    showRegisterForm($inputdata);
}

//if email is unknown, save new userdata, send to login
function handleUnknownEmail($email, $name, $pass) {
    echo "email is not found";
    writeUserDataFile($email, $name, $pass);
    header('Location: index.php?page=login');
    exit; //exit to prevent further execution
}

function writeUserDataFile($email, $name, $pass) {
    // Specify the path to the .txt file
    $userdatafile_path = 'users/users.txt';

    //open userdata file, append new userdata in newline and close file
    $usersfile = fopen($userdatafile_path, 'a') or die("Unable to open file!");
    $newUserDatatxt = $email . '|' . $name . '|' . $pass . "\n";
    fwrite($usersfile, $newUserDatatxt);
    fclose($usersfile);
}

function showRegisterForm($inputdata) {
    //extract values from the $userdata array
    extract($inputdata);

    echo '
    <form method="post" action="index.php">
        <p><span class="error"><strong>* Vereist veld</strong></span></p>
        <ul class="flex-outer">

            <li>
                <label for="fname">Voornaam:</label>
                <input type="text" id="fname" name="fname" value="' . $fname . '">
                <span class="error">* ' . $fnameErr . '</span>
            </li>

            <li>
                <label for="lname">Achternaam:</label>
                <input type="text" id="lname" name="lname" value="' . $lname . '">
                <span class="error">* ' . $lnameErr . '</span>
            </li>

            <li>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="' . $email . '">
                <span class="error">* ' . $emailErr . $emailknownErr . '</span>
            </li>

            <li>
                <label for="pass">Wachtwoord:</label>
                <input type="password" id="pass" name="pass" value="' . $pass . '">
                <span class="error">* ' . $passErr . '</span>
            </li>

            <li>
                <label for="repeatpass">Herhaal wachtwoord:</label>
                <input type="password" id="repeatpass" name="repeatpass" value="' . $repeatpass . '">
                <span class="error">* ' . $repeatpassErr . $passcheckErr . '</span>
            </li>

            <li>
                <button type="submit" name="page" value="register">Verstuur</button>
            </li>

        </ul>
    </form>';
}

?>
