<?php
$fname = $lname = $email = $pass = $repeatpass = ""; 
$fnameErr = $lnameErr = $emailErr = $passErr = $repeatpassErr = $passcheckErr = $emailknownErr = "";  
$valid = false;

//call readUserDataFile to obtain the user data
$userdata_array = readUserDataFile("users/users.txt");

function showRegisterTitle() {
    echo 'Register';
}

function showRegisterHeader() {
    echo 'Registreer Nu!';
}

function showRegisterContent() {
    global $valid, $email, $userdata_array;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        validateRegisterForm();
        if ($valid === true) {
            //passwords are equal, do something when condition is true
            if (validatePassword()) {
                //if email is unknown, do something
                if (checkUnknownEmail($email, $userdata_array)) {

                //email is known already, show error
                } else {
                    showRegisterForm();
                }
            
            //passwords are not equal, show error
            } else {
                echo "Passwords do not match.";
                readUserDataFile("users/users.txt");
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

function readUserDataFile() {
    global $userdata_array;
    // Specify the path to the .txt file
    $file_path = 'users/users.txt';

    //initialize an empty array to store the data
    $userdata_array = array();

    //open the file for reading or give error when unable to
    $usersfile = fopen($file_path, 'r') or die("Unable to open file!");

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
    global $fname, $lname, $email, $pass, $repeatpass; 
    global $fnameErr, $lnameErr, $emailErr, $passErr, $repeatpassErr; 
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

    //if no errors found set $valid to true
    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($passErr) && empty($repeatpassErr)) {
        $valid = true;
    }
}

function validatePassword() {
    global $pass, $repeatpass;
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