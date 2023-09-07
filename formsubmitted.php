<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="CSS/style.css">
        <title>Contact-ProtoWebsite</title>
    </head>
    <body>
        <div class="container">
            <header>
                <h1>Contacteer Mij</h1>
            </header>

            <nav>
                <ul>
                    <li><a href="index.html">HOME</a></li>|
                    <li><a href="about.html">ABOUT</a></li>|
                    <li><a href="contact.html">CONTACT</a></li>
                </ul> 
            </nav>

            <div class="content">
                <h1>Bedankt voor het invullen van uw gegevens:</h1>
                <ul>
                    <li>Aanhef: <?php echo $gender?></li>
                    <li>Voornaam: <?php echo $fname?></li>
                    <li>Achternaam: <?php echo $lname?></li>
                    <li>E-mailadres: <?php echo $email?></li>
                    <li>Telefoonnummer: <?php echo $phone?></li>
                    <li>Communicatievoorkeur: <?php echo $preference?></li>
                    <li>Bericht: <?php echo $message?></li>
                </ul>
            </div>
            <footer>
                <p>&copy; 2023 Jules Corbijn Bulsink</p>
            </footer>
        </div>
    </body>
</html>