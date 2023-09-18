<?php

function showRegisterTitle() {
    echo 'Register';
}

function showRegisterHeader() {
    echo 'Registreer Nu!';
}

function showRegisterContent() {
    require('validations.php');
    $inputdata = initializeFormData('register');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateRegisterForm($inputdata);
        if ($inputdata['valid']) {
            // extract values from the $inputdata array
            extract($inputdata);

            require('userservice.php');
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
