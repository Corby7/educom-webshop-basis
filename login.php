<?php

function showLoginTitle() {
    echo 'Login';
}

function showLoginHeader() {
    echo 'Login';
}

function showLoginContent() {
    require('validations.php');
    $inputdata = initializeFormData('login');
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateLoginForm($inputdata);
        if ($inputdata['valid']) {
            // extract values from the $inputdata array
            extract($inputdata);

            require('userservice.php');
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