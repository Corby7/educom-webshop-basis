<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/style.css">
        <title>Contact-ProtoWebsite</title>
    </head>
    <body>

    <?php
    //define variables and set to empty values
    $gender = $fname = $lname = $email = $phone = $preference = $message = "";
    $genderErr = $fnameErr = $lnameErr = $emailErr = $phoneErr = $preferenceErr = $messageErr = "";
    $valid = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["gender"]))  {
            $genderErr = "Aanhef is vereist";
        } else {
        //don't test input as it's just a selection? 
        $gender = $_POST["gender"];
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
            //don't test input as it's just a selection?    
            $preference = $_POST["preference"];
        }

        if (empty($_POST["message"])) {
            $messageErr = "Bericht is vereist";
        } else {
            $message = test_input($_POST["message"]);
        }

        //if no errors set variable valid to true
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
    ?>

        <div class="container">
            <header>
                <h1>Contacteer Mij</h1>
            </header>

            <nav>
                <ul>
                    <li><a href="index.html">HOME</a></li>|
                    <li><a href="about.html">ABOUT</a></li>|
                    <li><a href="contact.php">CONTACT</a></li>
                </ul> 
            </nav>

            <div class="content">
                <?php if ($valid === true) : ?>
                    <!-- Display submitted data -->
                    <h2>Beste<?php echo " " . getSalutation($gender) . " " . $fname . " " . $lname; ?>, bedankt voor het invullen van uw gegevens!</h2>
                    <h3>Ik zal zo snel mogelijk contact met u opnemen. Ter bevestiging uw informatie:</h3>
                    <ul class="submitted_data">
                        <li><strong>E-mailadres: </strong><?php echo $email?></li>
                        <li><strong>Telefoonnummer: </strong><?php echo $phone?></li>
                        <li><strong>Communicatievoorkeur: </strong><?php echo $preference?></li>
                        <li><strong>Bericht: </strong><?php echo $message?></li>
                    </ul>
                <?php else : ?>
                    <!-- Display the form -->
                    <form method="post" action="contact.php">
                        <p><span class="error">* Vereist veld</span></p>                   
                        <ul class="flex-outer">
                            <li>
                                <label for="gender">Aanhef:</label>
                                <select name="gender" id="gender">
                                <option disabled selected value> -- maak een keuze -- </option>
                                <option value="male" <?php if (isset($gender) && $gender == "male") echo "selected"; ?>>Dhr.</option>
                                <option value="female" <?php if (isset($gender) && $gender == "female") echo "selected"; ?>>Mvr.</option>
                                <option value="unspecified" <?php if (!isset($gender) || $gender == "unspecified") echo "selected"; ?>>Anders</option>
                                </select>
                                <span class="error">* <?php echo $genderErr;?></span>
                            </li>
                            <li>
                                <label for="fname">Voornaam:</label>
                                <input type="text" id="fname" name="fname" value="<?php echo $fname;?>">
                                <span class="error">* <?php echo $fnameErr;?></span>  
                            </li>
                            <li>
                                <label for="lname">Achternaam:</label>
                                <input type="text" id="lname" name="lname" value="<?php echo $lname;?>">
                                <span class="error">* <?php echo $lnameErr;?></span>  
                            </li>
                            <li>
                                <label for="email">E-mailadres:</label>
                                <input type="email" id="email" name="email" value="<?php echo $email;?>">
                                <span class="error">* <?php echo $emailErr;?></span> 
                            </li>
                            <li>
                                <label for="phone">Telefoonnummer:</label>
                                <input type="tel" id="phone" name="phone" value="<?php echo $phone;?>">
                                <span class="error">* <?php echo $phoneErr;?></span>
                            </li>
                            <li>
                                <legend>Communicatievoorkeur:</legend>
                                <ul class="flex-inner">
                                    <li>
                                        <input type="radio" id="email" name="preference" 
                                        <?php if (isset($preference) && $preference=="email") echo "checked";?>
                                        value="email">
                                        <label for="email">Email</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="phone" name="preference" 
                                        <?php if (isset($preference) && $preference=="phone") echo "checked";?>
                                        value="phone">
                                        <label for="telefoon">Telefoon</label> 
                                    </li>
                                    <span class="error">* <?php echo $preferenceErr;?></span>
                                </ul>
                            </li>
                            <li>
                                <label for="bericht">Bericht:</label>
                                <textarea id="message" name="message" rows="5" cols="33"><?php echo $message;?></textarea>
                                <span class="error">* <?php echo $messageErr;?></span>
                            </li>
                            <li>
                                <button type="submit">Submit</button> 
                            </li>
                        </ul>
                    </form>
                <?php endif; ?>   
            </div>
            <footer>
                <p>&copy; 2023 Jules Corbijn Bulsink</p>
            </footer>
        </div>
    </body>
</html>