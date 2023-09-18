<?php

function showContactTitle() {
    echo 'ProtoWebsite';
}

function showContactHeader() {
    echo 'Contacteer Mij';
}

function showContactContent() {
    $inputdata = initializeContactData();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputdata = validateContactForm($inputdata);
        if ($inputdata['valid']) {
            //display submitted inputdata if $valid is true
            showContactThanks($inputdata);
        } else {
            //display contact form if $valid is false
            showContactForm($inputdata);
        }
    } else {
        //display contact form by default if not a POST request
        showContactForm($inputdata);
    }
}

function initializeContactData() {
    return array(
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
}

function validateContactForm($inputdata) {
    // Extract values from the $inputdata array
    extract($inputdata);

    //retrieve and sanitize the fields from $_POST
    $gender = test_input(getPostVar("gender"));
    if (empty($gender)) {
        $genderErr = "Aanhef is vereist";
    }
    
    $fname = test_input(getPostVar("fname"));
    if (empty($fname)) {
        $fnameErr = "Voornaam is vereist";
    }
    
    $lname = test_input(getPostVar("lname"));
    if (empty($lname)) {
        $lnameErr = "Achternaam is vereist";
    }
    
    $email = test_input(getPostVar("email"));
    if (empty($email)) {
        $emailErr = "Email is vereist";
    }
    
    $phone = test_input(getPostVar("phone"));
    if (empty($phone)) {
        $phoneErr = "Telefoonnummer is vereist";
    }
    
    $preference = test_input(getPostVar("preference"));
    if (empty($preference)) {
        $preferenceErr = "Voorkeur is vereist";
    }
    
    $message = test_input(getPostVar("message"));
    if (empty($message)) {
        $messageErr = "Bericht is vereist";
    }
    

    //check if there are any errors and set $valid accordingly
    $valid = empty($genderErr) && empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($messageErr);

    return compact('gender', 'fname', 'lname', 'email', 'phone', 'preference', 'message', 'genderErr', 'fnameErr', 'lnameErr', 'emailErr', 'phoneErr', 'preferenceErr', 'messageErr', 'valid');
}

function test_input($inputdata) {
    $inputdata = trim($inputdata);
    $inputdata = stripslashes($inputdata);
    $inputdata = htmlspecialchars($inputdata);
    return $inputdata;
}

function showContactThanks($inputdata) {
    // Extract values from the $inputdata array
    extract($inputdata);

    echo '
    <h2>Beste ' . getSalutation($gender) . ' ' . $fname . ' ' . $lname . ', bedankt voor het invullen van uw gegevens!</h2>
    <h3>Ik zal zo snel mogelijk contact met u opnemen. Ter bevestiging uw informatie:</h3>
    <ul class="submitted_userdata">
        <li><strong>E-mailadres: </strong>' . $email . '</li>
        <li><strong>Telefoonnummer: </strong>' . $phone . '</li>
        <li><strong>Communicatievoorkeur: </strong>' . $preference . '</li>
        <li><strong>Bericht: </strong>' . $message . '</li>
    </ul>';
}

function getSalutation($gender) {
    switch ($gender) {
        case 'male':
            return 'meneer';
        case 'female':
            return 'mevrouw';
        default:
            return;
    }
}

function showContactForm($inputdata) {
    // Extract values from the $inputdata array
    extract($inputdata);

    echo '
    <form method="post" action="index.php">
        <p><span class="error"><strong>* Vereist veld</strong></span></p>
        <ul class="flex-outer">

            <li>
                <label for="gender">Aanhef:</label>
                <select name="gender" id="gender">
                <option disabled selected value> -- maak een keuze -- </option>
                <option value="male" ' . ($gender == "male" ? "selected" : "") . '>Dhr.</option>
                <option value="female" ' . ($gender == "female" ? "selected" : "") . '>Mvr.</option>
                <option value="unspecified" ' . ($gender == "unspecified" ? "selected" : "") . '>Anders</option>
                </select>
                <span class="error">* ' . $genderErr . '</span>
            </li>

            <li>
                <label for="fname">Voornaam:</label>
                <input type="text" id="fname" name="fname" value="' . $fname . '">
                <span class="error">* ' . $fnameErr . '</span>
            </li>
            
            <li>
                <label for="lname">Achternaam:</label>
                <input type="text" id="lname" name="lname" value="' .$lname . '">
                <span class="error">* ' . $lnameErr . '</span>
            </li>
            
            <li>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="' . $email . '">
                <span class="error">* ' . $emailErr . '</span>
            </li>
            
            <li>
                <label for="phone">Telefoonnummer:</label>
                <input type="tel" id="phone" name="phone" value="' . $phone . '">
                <span class="error">* ' . $phoneErr . '</span>
            </li>
            
            <li>
                <legend>Communicatievoorkeur:</legend>
                <ul class="flex-inner">
                    <li>
                        <input type="radio" id="email" name="preference" value="email" ' . ($preference == "email" ? "checked" : "") . '>
                        <label for="email">Email</label>
                    </li>
                    <li>
                        <input type="radio" id="phone" name="preference" value="phone" ' . ($preference == "phone" ? "checked" : "") . '>
                        <label for="telefoon">Telefoon</label>
                    </li>
                </ul>
                <span class="error">* ' . $preferenceErr . '</span>
            </li>
            
            <li>
                <label for="bericht">Bericht:</label>
                <textarea id="message" name="message" rows="5" cols="33">' . $message . '</textarea>
                <span class="error">* ' . $messageErr . '</span>
            </li>
            
            <li>
                <button type="submit" name="page" value="contact">Verstuur</button>
            </li>
            
        </ul>
    </form>';
}

?>