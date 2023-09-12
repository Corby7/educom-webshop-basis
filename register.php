<?php
$fname = $lname = $email = $pass = $repeatpass = ""; 
$fnameErr = $lnameErr = $emailErr = $passErr = $repeatpassErr = $passcheckErr = "";  
$valid = false;

function showRegisterTitle() {
    echo 'Register';
}

function showRegisterHeader() {
    echo 'Registreer Nu!';
}

function showRegisterContent() {
    global $valid;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        validateRegisterForm();
        if ($valid === true) {
            if (validatePassword()) {
                checkKnownEmail();
                // Passwords are equal, do something when condition is true
            } else {
                // Passwords are not equal, do something when condition is false
                echo "Passwords do not match.";
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

function checkKnownEmail() {
    $usersfile = fopen("users/users.txt", "r") or die("Unable to open file!"); // Add error handling

    while (!feof($usersfile)) {
        echo fgets($usersfile); // Use fgets to read line by line
    }

    fclose($usersfile);
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

    if ($pass === $repeatpass) {
        return true;
    } else {
        $passcheckErr = "Wachtwoorden komen niet overeen";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function showRegisterForm() {
    global $fname, $lname, $email, $pass, $repeatpass; 
    global $fnameErr, $lnameErr, $emailErr, $passErr, $repeatpassErr, $passcheckErr; 

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
                <span class="error">* ' . $emailErr . '</span>
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