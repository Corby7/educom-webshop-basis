<?php

session_start(); 

require('sessionmanager.php');
require('validations.php');
require('userservice.php');
require('filerepository.php');

require('home.php');
require('about.php');
require('contact.php');
require('register.php');
require('login.php');
require('error.php');

//MAIN APP
$page = getRequestedPage();
$inputdata = processRequest($page);
showResponsePage($inputdata);

//FUNCTIONS
function getRequestedPage() {
    $requested_type = $_SERVER['REQUEST_METHOD'];
    if ($requested_type == 'POST') {
        $requested_page = getPostVar('page', 'home');
    } else {
        $requested_page = getUrlVar('page', 'home');
    }

    return $requested_page;
}

function getPostVar($key, $default = '') {
    $value = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
    return isset($value) ? $value : $default;
}

function getUrlVar($key, $default = '') {
    $value = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
    return isset($value) ? $value : $default;
}

//working on this
function processRequest($page) {
    $inputdata = initializeFormData($page);
    //if ($_SERVER["REQUEST_METHOD"] == "POST") {

        switch($page) {
            case 'contact':
                $inputdata = validateContactForm($inputdata);
                if($inputdata['valid']) {
                    $page = "thanks";
                }
                break;

            case 'register':
                $inputdata = validateRegisterForm($inputdata);
                if ($inputdata['valid']) {
                    // extract values from the $inputdata array
                    extract($inputdata);

                    saveUser($email, $name, $pass);
                    $page = "login";
                }
                break;

            case 'login':
                $inputdata = validateLoginForm($inputdata);
                if ($inputdata['valid']) {
                    // Extract values from the $inputdata array
                    extract($inputdata);

                    $result = authenticateUser($email, $pass);

                    // Check authentication result
                    if ($result['result'] === RESULT_OK) {
                        // Authentication is successful
                        // You can perform actions here or set a session variable to indicate login
                        // For example, you can set a session variable like $_SESSION['user'] = $result['user'];
                        // Then, you can redirect the user to the home page
                        loginUser($result['user']);
                        $page = "home";
                    } elseif ($result['result'] === RESULT_UNKNOWN_USER) {
                        // Handle unknown user error
                        $inputdata['emailunknownErr'] = "E-mailadres is onbekend";
                    } elseif ($result['result'] === RESULT_WRONG_PASSWORD) {
                        // Handle wrong password error
                        $inputdata['wrongpassErr'] = "Wachtwoord is onjuist";
                    }
                }
                break;
            
            case 'logout':
                logoutUser();
                $page = "home";
                break;
        }
        $inputdata['page'] = $page;
        return $inputdata;
    // } else {
    //     //display form by default if not a POST request
    //     $inputdata['page'] = $page;
    //     return $inputdata;
    // }
}

function showResponsePage($inputdata) {
    beginDocument();
    showHeadSection($inputdata);
    showBodySection($inputdata);
    endDocument();
}

function beginDocument() {
    echo '
    <!DOCTYPE html>
    <html>';
}

function showHeadSection($inputdata) {
    echo '    <head>' . PHP_EOL;
    echo '<link rel="stylesheet" href="CSS/style.css">';
    showTitle($inputdata);
    echo '    </head>' . PHP_EOL;
}

function showTitle($inputdata) {
    echo '<title>';
        switch ($inputdata['page']) {
            case 'home':
                showHomeTitle();
                break;
            case 'about':
                showAboutTitle();
                break;
            case 'contact':
                showContactTitle();
                break;
            case 'register':
                showRegisterTitle();
                break;
            case 'login':
                showLoginTitle();
                break;    
            default:
                showErrorTitle();
                break;
        }
    echo '-ProtoWebsite</title>';
}

function showBodySection($inputdata) { 
    echo '<body>' . PHP_EOL;
    echo '  <div class="container">' . PHP_EOL; 
    showHeader($inputdata); 
    showMenu(); 
    showContent($inputdata); 
    showFooter(); 
    echo '  </div>' . PHP_EOL;         
    echo '</body>' . PHP_EOL;  
} 

function endDocument() {
    echo '</html>';
}

function showHeader($inputdata) {
    echo '<header>' . PHP_EOL;
    echo '  <h1>';
    echo getLoggedInUserName() . " ";
    switch ($inputdata['page']) {
        case 'home':
            showHomeHeader();
            break;
        case 'about':
            showAboutHeader();
            break;
        case 'contact':
            showContactHeader();
            break;
        case 'register':
            showRegisterHeader();
            break;
        case 'login':
            showLoginHeader();
            break;     
        default:
            showErrorHeader();
            break;
    }
    echo '  </h1>';
    echo '</header>' . PHP_EOL;
}

function showMenu() { 
    echo ' 
    <nav> 
        <ul>';
    showMenuItem("home", "HOME"); 
    echo '|';
    showMenuItem("about", "ABOUT");
    echo '|'; 
    showMenuItem("contact", "CONTACT");
    echo '|'; 
    
    if(isUserLoggedIn()) {
        showMenuItem("logout", "LOGOUT " . getLoggedInUserName());
    } else {
        showMenuItem("register", "REGISTER"); 
        echo '|';
        showMenuItem("login", "LOGIN");
    } 
    echo '
        </ul>  
    </nav>'; 
} 

function showMenuItem($link, $text) {
        echo '<li><a href="index.php?page=' . $link . '">' . $text . '</a></li>';
}

function showContent($inputdata) {
    echo '<div class="content">' . PHP_EOL;
    switch ($inputdata['page']) {
        case 'home':
            showHomeContent();
            break;
        case 'about':
            showAboutContent();
            break;
        case 'contact':
            showContactForm($inputdata);
            break; 
        case 'thanks':
            showContactThanks($inputdata);
            break;
        case 'register':
            showRegisterForm($inputdata);
            break;
        case 'login':
            showLoginForm($inputdata);
            break;
        default:
            showErrorContent();
            break;
    }
    echo '</div>' . PHP_EOL;
}

function showFooter() {
    echo '
    <footer>
        <p>&copy; 2023 Jules Corbijn Bulsink</p>
    </footer>';
}

?>  