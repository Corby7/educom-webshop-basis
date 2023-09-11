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
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}


function showResponsePage($page) {
    beginDocument();
    showHeadSection();
    showBodySection($page);
    endDocument();
}

function beginDocument() {
    echo '<!DOCTYPE html>
    <html>';
}

function showHeadSection() {
    echo '<head>
    <link rel="stylesheet" href="CSS/style.css">
    <title>Home-ProtoWebsite</title>
    </head>';
}

function showBodySection($page) {
    echo '    <body>' . PHP_EOL;
    echo '<div class="container">';
    showHeader($page);
    showMenu();
    //showContent($page); 
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
            require('home.php');
            showHomeHeader();
            break;
        case 'about'
            require('about.php');
            showAboutHeader();
            break;
        case 'contact'
        require('contact.php');
        showContactHeader();
        break;
    }
}

function showMenu() {
    echo '<nav>
            <ul>
                <li><a href="index.php?page=home">HOME</a></li>|
                <li><a href="index.php?page=about">ABOUT</a></li>|
                <li><a href="index.php?page=contact">CONTACT</a></li>
            </ul> 
        </nav>';
}

function showFooter() {
    echo '<footer>
            <p>&copy; 2023 Jules Corbijn Bulsink</p>
        </footer>';
}

?>  