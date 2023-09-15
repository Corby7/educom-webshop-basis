<?php

//MAIN APP
$page = getRequestedPage();
showResponsePage($page);

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

//check array on key, if set; return key otherwise; return default empty
// function getArrayValue($array, $key, $default='') {
//     return isset($array[$key]) ? $array[$key] : $default;
// }

function getArrayValues($array, $default = '5') {
    $result = array();
    foreach ($array as $key => $value) {
        $result[$key] = isset($array[$key]) ? $array[$key] : $default;
    }
    return $result;
}

function showResponsePage($page) {
    beginDocument();
    showHeadSection($page);
    showBodySection($page);
    endDocument();
}

function beginDocument() {
    echo '
    <!DOCTYPE html>
    <html>';
}

function showHeadSection($page) {
    echo '    <head>' . PHP_EOL;
    echo '<link rel="stylesheet" href="CSS/style.css">';
    showTitle($page);
    echo '    </head>' . PHP_EOL;
}

function showTitle($page) {
    echo '<title>';
        switch ($page) {
            case 'home':
                require('home.php');
                showHomeTitle();
                break;
            case 'about':
                require('about.php');
                showAboutTitle();
                break;
            case 'contact':
                require('contact.php');
                showContactTitle();
                break;
            case 'register':
                require('register.php');
                showRegisterTitle();
                break;
            case 'login':
                require('login.php');
                showLoginTitle();
                break;    
            default:
                require('error.php');
                showErrorTitle();
                break;
        }
    echo '-ProtoWebsite</title>';
}

function showBodySection($page) { 
    echo '<body>' . PHP_EOL;
    echo '  <div class="container">' . PHP_EOL; 
    showHeader($page); 
    showMenu(); 
    showContent($page); 
    showFooter(); 
    echo '  </div>' . PHP_EOL;         
    echo '</body>' . PHP_EOL;  
} 

function endDocument() {
    echo '</html>';
}

function showHeader($page) {
    echo '<header>' . PHP_EOL;
    echo '  <h1>';
    switch ($page) {
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
    showMenuItem("register", "REGISTER"); 
    echo '|';
    showMenuItem("login", "LOGIN"); 
    echo '
        </ul>  
    </nav>'; 
} 

function showMenuItem($link, $text) {
    echo '<li><a href="index.php?page=' . $link . '">' . $text . '</a></li>';
}

function showContent($page) {
    echo '<div class="content">' . PHP_EOL;
    switch ($page) {
        case 'home':
            showHomeContent();
            break;
        case 'about':
            showAboutContent();
            break;
        case 'contact':
            showContactContent();
            break;
        case 'register':
            showRegisterContent();
            break;
        case 'login':
            showLoginContent();
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