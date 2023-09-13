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
            default:
                require('error.php');
                showErrorTitle();
                break;
        }
    echo '-ProtoWebsite</title>';
}

function showBodySection($page) {
    echo '    <body>' . PHP_EOL;
        echo '<div class="container">';
            echo '<header>';
                echo '<h1>';
                    showHeader($page);
                echo '</h1>';
            echo '</header>';
            showMenu();
            echo '<div class="content">';
                showContent($page);
            echo '</div>'; 
            showFooter();
        echo '</div>';
    echo '    </body>' . PHP_EOL; 
}

function endDocument() {
    echo '</html>';
}

function showHeader($page) {
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
        default:
            showErrorHeader();
            break;
    }
}

function showMenu() {
    echo '
    <nav>
        <ul>
            <li><a href="index.php?page=home">HOME</a></li>|
            <li><a href="index.php?page=about">ABOUT</a></li>|
            <li><a href="index.php?page=contact">CONTACT</a></li>|
            <li><a href="index.php?page=register">REGISTER</a></li>
        </ul> 
    </nav>';
}


function showContent($page) {
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
        default:
            showErrorContent();
            break;
    }
}


function showFooter() {
    echo '
    <footer>
        <p>&copy; 2023 Jules Corbijn Bulsink</p>
    </footer>';
}

?>  