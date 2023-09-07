<?php
session_start();

$submittedData = $_SESSION['submitted_data'];

function getSalutation($aanhef) {
    switch ($aanhef) {
        case 'male':
            return 'Dhr.';
        case 'female':
            return 'Mvr.';
        default:
            return 'Anders';
    }
}

$aanhef = $submittedData['Aanhef'];
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <title>Contact-ProtoWebsite</title>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Bedankt!</h1>
            </header>

            <nav>
                <ul>
                    <li><a href="index.html">HOME</a></li>|
                    <li><a href="about.html">ABOUT</a></li>|
                    <li><a href="contact.html">CONTACT</a></li>
                </ul> 
            </nav>

            <div class="content">
                <h2>Bedankt voor het invullen van uw gegevens!</h2>
                <h3>Ik zal zo snel mogelijk contact met u opnemen. Ter bevestiging uw informatie:</h3>

                <ul>
                    <li><strong>Aanhef: </strong><?php echo getSalutation($aanhef); ?></li>
                    <li><strong>Voornaam: </strong><?php echo $submittedData['Voornaam']?></li>
                    <li><strong>Achternaam: </strong><?php echo $submittedData['Achternaam']?></li>
                    <li><strong>E-mailadres: </strong><?php echo $submittedData['E-mailadres']?></li>
                    <li><strong>Telefoonnummer: </strong><?php echo $submittedData['Telefoonnummer']?></li>
                    <li><strong>Communicatievoorkeur: </strong><?php echo $submittedData['Communicatievoorkeur']?></li>
                    <li><strong>Bericht: </strong><?php echo $submittedData['Bericht']?></li>
                </ul>
            </div>
            <footer>
                <p>&copy; 2023 Jules Corbijn Bulsink</p>
            </footer>
        </div>
    </body>
</html>