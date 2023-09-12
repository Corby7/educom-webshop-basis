<?php

function showErrorTitle() {
    echo '<title>Page Not Found</title>';
}

function showErrorHeader() {
    echo '<h1>Error 404</h1>';
}

function showErrorContent() {
    echo '
    <div class="content">
        <h1>Page Not Found</h1>
        <p>The requested page could not be found. Please check the URL or return to the homepage.</p>
    </div>';
}


?>