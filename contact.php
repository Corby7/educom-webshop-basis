<?php
$gender = $fname = $lname = $email = $phone = $preference = $message = "";
$genderErr = $fnameErr = $lnameErr = $emailErr = $phoneErr = $preferenceErr = $messageErr = "";
$valid = false;

function showContactTitle() {
    echo '<title>Contact-ProtoWebsite</title>';
}

function showContactHeader() {
    echo '<header>
            <h1>Contacteer Mij</h1>
        </header>';
}

function showContactContent() {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        validateContactForm();
        if ($valid === true) {
            //display submitted data if $valid is true
            showContactThanks();
        }
    }

    //display contact form by default
    showContactForm();
}

function validateContactForm() {
    global $gender, $fname, $lname, $email, $phone, $preference, $message;
    global $genderErr, $fnameErr, $lnameErr, $emailErr, $phoneErr, $preferenceErr, $messageErr;

    if (empty($_POST["gender"]))  {
        $genderErr = "Aanhef is vereist";
    } else {
        $gender = test_input($_POST["gender"]);
    }

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

    if (empty($_POST["phone"])) {
        $phoneErr = "Telefoonnummer is vereist";
    } else {
        $phone = test_input($_POST["phone"]);
    }

    if (empty($_POST["preference"])) {
        $preferenceErr = "Voorkeur is vereist";
    } else {   
        $preference = test_input($_POST["preference"]);
    }

    if (empty($_POST["message"])) {
        $messageErr = "Bericht is vereist";
    } else {
        $message = test_input($_POST["message"]);
    }

    //if no errors found set $valid to true
    if (empty($genderErr) && empty($fnameErr) && empty($lnameErr) && empty($emailErr) && empty($phoneErr) && empty($preferenceErr) && empty($messageErr)) {
    $valid = true;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function getSalutation($aanhef) {
    switch ($aanhef) {
        case 'male':
            return 'meneer';
        case 'female':
            return 'mevrouw';
        default:
            return;
    }
}

function showContactThanks() {
    global $gender, $fname, $lname, $email, $phone, $preference, $message;
    
    echo '
    <h2>Beste ' . getSalutation($gender) . ' ' . $fname . ' ' . $lname . ', bedankt voor het invullen van uw gegevens!</h2>
    <h3>Ik zal zo snel mogelijk contact met u opnemen. Ter bevestiging uw informatie:</h3>
    <ul class="submitted_data">
        <li><strong>E-mailadres: </strong>' . $email . '</li>
        <li><strong>Telefoonnummer: </strong>' . $phone . '</li>
        <li><strong>Communicatievoorkeur: </strong>' . $preference . '</li>
        <li><strong>Bericht: </strong>' . $message . '</li>
    </ul>';
}

function showContactForm() {
    global $gender, $fname, $lname, $email, $phone, $preference, $message;
    global $genderErr, $fnameErr, $lnameErr, $emailErr, $phoneErr, $preferenceErr, $messageErr;

    echo '
    <form method="post" action="contact.php">
        <p><span class="error">* Vereist veld</span></p>
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
                <input type="text" id="lname" name="lname" value="' . $lname . '">
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
                <button type="submit">Submit</button>
            </li>';
}

?>