<?php

function showContactTitle() {
    echo 'ProtoWebsite';
}

function showContactHeader() {
    echo 'Contacteer Mij';
}

function showContactContent() {
    $userdata = initializeContactData();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $userdata = validateContactForm($userdata);
        if ($userdata['valid']) {
            //display submitted userdata if $valid is true
            showContactThanks($userdata);
        } else {
            //display contact form if $valid is false
            showContactForm($userdata);
        }
    } else {
        //display contact form by default if not a POST request
        showContactForm($userdata);
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

function validateContactForm($data) {
    if (empty($_POST["gender"]))  {
        $data['genderErr'] = "Aanhef is vereist";
    } else {
        $data['gender'] = test_input($_POST["gender"]);
    }

    if (empty($_POST["fname"])) {
        $data['fnameErr'] = "Voornaam is vereist";
    } else {
        $data['fname'] = test_input($_POST["fname"]);
    }
    
    if (empty($_POST["lname"])) {
        $data['lnameErr'] = "Achternaam is vereist";
    } else {
        $data['lname'] = test_input($_POST["lname"]);
    }

    if (empty($_POST["email"])) {
        $data['emailErr'] = "Email is vereist";
    } else {
        $data['email'] = test_input($_POST["email"]);
    }

    if (empty($_POST["phone"])) {
        $data['phoneErr'] = "Telefoonnummer is vereist";
    } else {
        $data['phone'] = test_input($_POST["phone"]);
    }

    if (empty($_POST["preference"])) {
        $data['preferenceErr'] = "Voorkeur is vereist";
    } else {   
        $data['preference'] = test_input($_POST["preference"]);
    }

    if (empty($_POST["message"])) {
        $data['messageErr'] = "Bericht is vereist";
    } else {
        $data['message'] = test_input($_POST["message"]);
    }

    //check if there are any errors and set $valid accordingly
    $data['valid'] = empty($data['genderErr']) && empty($data['fnameErr']) && empty($data['lnameErr']) && empty($data['emailErr']) && empty($data['phoneErr']) && empty($data['preferenceErr']) && empty($data['messageErr']);

    return $data;
}

function test_input($userdata) {
    $userdata = trim($userdata);
    $userdata = stripslashes($userdata);
    $userdata = htmlspecialchars($userdata);
    return $userdata;
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

function showContactThanks($userdata) {
    echo '
    <h2>Beste ' . getSalutation($userdata['gender']) . ' ' . $userdata['fname'] . ' ' . $userdata['lname'] . ', bedankt voor het invullen van uw gegevens!</h2>
    <h3>Ik zal zo snel mogelijk contact met u opnemen. Ter bevestiging uw informatie:</h3>
    <ul class="submitted_userdata">
        <li><strong>E-mailadres: </strong>' . $userdata['email'] . '</li>
        <li><strong>Telefoonnummer: </strong>' . $userdata['phone'] . '</li>
        <li><strong>Communicatievoorkeur: </strong>' . $userdata['preference'] . '</li>
        <li><strong>Bericht: </strong>' . $userdata['message'] . '</li>
    </ul>';
}

function showContactForm($userdata) {
    echo '
    <form method="post" action="index.php">
        <p><span class="error"><strong>* Vereist veld</strong></span></p>
        <ul class="flex-outer">

            <li>
                <label for="gender">Aanhef:</label>
                <select name="gender" id="gender">
                <option disabled selected value> -- maak een keuze -- </option>
                <option value="male" ' . ($userdata['gender'] == "male" ? "selected" : "") . '>Dhr.</option>
                <option value="female" ' . ($userdata['gender'] == "female" ? "selected" : "") . '>Mvr.</option>
                <option value="unspecified" ' . ($userdata['gender'] == "unspecified" ? "selected" : "") . '>Anders</option>
                </select>
                <span class="error">* ' . $userdata['genderErr'] . '</span>
            </li>

            <li>
                <label for="fname">Voornaam:</label>
                <input type="text" id="fname" name="fname" value="' . $userdata['fname'] . '">
                <span class="error">* ' . $userdata['fnameErr'] . '</span>
            </li>
            
            <li>
                <label for="lname">Achternaam:</label>
                <input type="text" id="lname" name="lname" value="' .$userdata['lname'] . '">
                <span class="error">* ' . $userdata['lnameErr'] . '</span>
            </li>
            
            <li>
                <label for="email">E-mailadres:</label>
                <input type="email" id="email" name="email" value="' . $userdata['email'] . '">
                <span class="error">* ' . $userdata['emailErr'] . '</span>
            </li>
            
            <li>
                <label for="phone">Telefoonnummer:</label>
                <input type="tel" id="phone" name="phone" value="' . $userdata['phone'] . '">
                <span class="error">* ' . $userdata['phoneErr'] . '</span>
            </li>
            
            <li>
                <legend>Communicatievoorkeur:</legend>
                <ul class="flex-inner">
                    <li>
                        <input type="radio" id="email" name="preference" value="email" ' . ($userdata['preference'] == "email" ? "checked" : "") . '>
                        <label for="email">Email</label>
                    </li>
                    <li>
                        <input type="radio" id="phone" name="preference" value="phone" ' . ($userdata['preference'] == "phone" ? "checked" : "") . '>
                        <label for="telefoon">Telefoon</label>
                    </li>
                </ul>
                <span class="error">* ' . $userdata['preferenceErr'] . '</span>
            </li>
            
            <li>
                <label for="bericht">Bericht:</label>
                <textarea id="message" name="message" rows="5" cols="33">' . $userdata['message'] . '</textarea>
                <span class="error">* ' . $userdata['messageErr'] . '</span>
            </li>
            
            <li>
                <button type="submit" name="page" value="contact">Verstuur</button>
            </li>
            
        </ul>';
}

?>