<?php
$name = $fname = $lname = $email = $pass = $repeatpass = ""; 
$fnameErr = $lnameErr = $emailErr = $passErr = $repeatpassErr = $passcheckErr = $emailknownErr = "";
$valid = false;

$userdatafile_path = 'users/users.txt';
//call readUserDataFile to obtain the user data
$userdata_array = readUserDataFile($userdatafile_path);

function showRegisterTitle() {
    echo 'Register';
}

function showRegisterHeader() {
    echo 'Registreer Nu!';
}

function showRegisterContent() {
    global $valid, $email, $userdata_array, $pass, $repeatpass, $name;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        validateRegisterForm();
        if ($valid === true) {
            //if email is unknown, save new userdata
            if (checkUnknownEmail($email, $userdata_array)) {
                writeUserDataFile($email, $name, $pass);
            //if email is known already, show error
            } else {
                showRegisterForm();
            }
        } else {
            //display register form if $valid is false
            showRegisterForm();
        }
    } else {
        //display register form by default if not a POST request
        showRegisterForm();
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
                'password' => trim($values[2])
            );
        }
    }

    //close the file
    fclose($usersfile);
    return $userdata_array;
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

function checkUnknownEmail($email, $userdata_array) {
    global $emailknownErr;

    foreach ($userdata_array as $userdata) {
        if ($userdata['email'] === $email) {
            $emailknownErr = "E-mailadres is reeds bekend"; 
            echo 'Match found';
            return false; // Match found
        }
    }

    echo 'Match not found';
    return true; // Match not found
}

function validateRegisterForm() {
    global $name, $fname, $lname, $email, $pass, $repeatpass; 
    global $fnameErr, $lnameErr, $emailErr, $passErr, $repeatpassErr, $passcheckErr; 
    global $valid;

    if (empty($_POST["fname"])) {
        $fnameErr = "Voornaam is vereist";
    } else {
        $fname = test_input($_POST["fname"]);
    }
    
    if (empty($_POST["lname"])) {
        $lnameErr = "Achternaam is vereist";
    } else {
        $lname = test_input($_POST["lname"]);
    }

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

    if (empty($_POST["repeatpass"])) {
        $repeatpassErr = "Wachtwoord herhalen is vereist";
    } else {
        $repeatpass = test_input($_POST["repeatpass"]);
    }

    if (empty($passErr) && empty($repeatpassErr)) {
        validatePassword($pass, $repeatpass);
    }

    //if no errors found set $valid to true
    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($passErr) && empty($repeatpassErr) && empty($passcheckErr)) {
        $name = $fname . ' ' . $lname;
        $valid = true;
    }
}

function validatePassword($pass, $repeatpass) {
    global $passcheckErr; 

    if ($pass !== $repeatpass) {
        $passcheckErr = "Wachtwoorden komen niet overeen";
        return false;
    }

    return true;
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showRegisterForm() {
    global $fname, $lname, $email, $pass, $repeatpass; 
    global $fnameErr, $lnameErr, $emailErr, $passErr, $repeatpassErr, $passcheckErr, $emailknownErr; 

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
            </li>';
            
}

?>