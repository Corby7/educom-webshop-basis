<?php

function initializeFormData($formType) {
    $data = array();

    switch ($formType) {
        case 'contact':
            $data = array(
                'gender' => '',
                'fname' => '',
                'lname' => '',
                'email' => '',
                'phone' => '',
                'preference' => '',
                'message' => '',
                'genderErr' => '',
                'fnameErr' => '',
                'lnameErr' => '',
                'emailErr' => '',
                'phoneErr' => '',
                'preferenceErr' => '',
                'messageErr' => '',
                'valid' => ''
            );
            break;
        
        case 'register':
            $data = array(
                'name' => '',
                'fname' => '',
                'lname' => '',
                'email' => '',
                'pass' => '',
                'repeatpass' => '',
                'fnameErr' => '',
                'lnameErr' => '',
                'emailErr' => '',
                'passErr' => '',
                'repeatpassErr' => '',
                'passcheckErr' => '',
                'emailknownErr' => '',
                'valid' => ''
            );
            break;
        
        case 'login':
            $data = array(
                'email' => '',
                'emailErr' => '',
                'emailunknownErr' => '',
                'pass' => '',
                'passErr' => '',
                'wrongpassErr' => '',
                'valid' => ''
            );
            break;
        
        default:
            // Handle unknown form types or set default values
            break;
    }

    return $data;
}


function validateContactForm($inputdata) {
    // Extract values from the $inputdata array
    extract($inputdata);

    //retrieve and sanitize the fields from $_POST
    $gender = testInput(getPostVar("gender"));
    if (empty($gender)) {
        $genderErr = "Aanhef is vereist";
    }
    
    $fname = testInput(getPostVar("fname"));
    if (empty($fname)) {
        $fnameErr = "Voornaam is vereist";
    }
    
    $lname = testInput(getPostVar("lname"));
    if (empty($lname)) {
        $lnameErr = "Achternaam is vereist";
    }
    
    $email = testInput(getPostVar("email"));
    if (empty($email)) {
        $emailErr = "Email is vereist";
    }
    
    $phone = testInput(getPostVar("phone"));
    if (empty($phone)) {
        $phoneErr = "Telefoonnummer is vereist";
    }
    
    $preference = testInput(getPostVar("preference"));
    if (empty($preference)) {
        $preferenceErr = "Voorkeur is vereist";
    }
    
    $message = testInput(getPostVar("message"));
    if (empty($message)) {
        $messageErr = "Bericht is vereist";
    }
    

    //check if there are any errors and set $valid accordingly
    $valid = empty($genderErr) && empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($messageErr);

    return compact('gender', 'fname', 'lname', 'email', 'phone', 'preference', 'message', 'genderErr', 'fnameErr', 'lnameErr', 'emailErr', 'phoneErr', 'preferenceErr', 'messageErr', 'valid');
}

function validateRegisterForm($inputdata) {
    // extract values from the $inputdata array
    extract($inputdata);

    //retrieve and sanitize the fields from $_POST
    $fname = testInput(getPostVar("fname"));
    if (empty($fname)) {
        $fnameErr = "Voornaam is vereist";
    }

    $lname = testInput(getPostVar("lname"));
    if (empty($lname)) {
        $lnameErr = "Achternaam is vereist";
    }

    $email = testInput(getPostVar("email"));
    if (empty($email)) {
        $emailErr = "Email is vereist";
    }

    if (!empty($email) && doesEmailExist($email)) {
        $emailknownErr = "E-mailadres is reeds bekend";
    }

    $pass = testInput(getPostVar("pass"));
    if (empty($pass)) {
        $passErr = "Wachtwoord is vereist";
    }

    $repeatpass = testInput(getPostVar("repeatpass"));
    if (empty($repeatpass)) {
        $repeatpassErr = "Wachtwoord herhalen is vereist";
    }

    if (empty($passErr) && empty($repeatpassErr)) {
        $passcheckErr = validatePassword($pass, $repeatpass);
    }

    //if no errors found, set username and set valid to true
    if (empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($passErr) && empty($repeatpassErr) && empty($passcheckErr) && empty($emailknownErr)) {
        $name = $fname . ' ' . $lname;
        $valid = true;
    }

    return compact ('name', 'fname', 'lname', 'email', 'pass', 'repeatpass', 'fnameErr', 'lnameErr', 'emailErr', 'passErr', 'repeatpassErr', 'passcheckErr', 'emailknownErr', 'valid');
}

function validateLoginForm($inputdata) {
    // extract values from the $inputdata array
    extract($inputdata);

    //retrieve and sanitize the fields from $_POST
    $email = testInput(getPostVar("email"));
    if (empty($email)) {
        $emailErr = "Email is vereist";
    }

    $pass = testInput(getPostVar("pass"));
    if (empty($pass)) {
        $passErr = "Wachtwoord is vereist";
    }

    $valid = empty($emailErr) && empty($passErr);

    return compact ('email', 'pass', 'emailErr', 'passErr', 'emailunknownErr', 'wrongpassErr', 'valid');
}

function testInput($inputdata) {
    $inputdata = trim($inputdata);
    $inputdata = stripslashes($inputdata);
    $inputdata = htmlspecialchars($inputdata);
    return $inputdata;
}

function validatePassword($pass, $repeatpass) {
    if ($pass !== $repeatpass) {
        return "Wachtwoorden komen niet overeen";
    }
    return '';
}

?>