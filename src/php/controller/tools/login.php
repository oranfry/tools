<?php
$message = null;

if (AUTH_TOKEN) {
    error_log('Unexpectedly, token is already present');
    die();
}

if (@$_COOKIE['token'] && Blends::verify_token($_COOKIE['token'])) {
    if (!@Config::get()->landingpage) {
        error_response('No landing page defined');
    }

    header('Location: ' . Config::get()->landingpage);
    die('Redirecting...');
}

return [];
