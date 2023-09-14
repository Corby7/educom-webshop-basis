<?php

function showErrorTitle() {
    echo 'Page Not Found';
}

function showErrorHeader() {
    echo 'Error 404';
}

function showErrorContent() {
    echo '
        <h1>Page Not Found</h1>
        <p>The requested page could not be found. Please check the URL or return to the homepage.</p>';
}


?>