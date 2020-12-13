<?php
$message = null;

if (AUTH_TOKEN) {
    error_log('Unexpectedly, token is already present');
    die();
}

if (@$_COOKIE['token'] && Blends::verify_token($_COOKIE['token'])) {
    header('Location: ' . landingpage());
    die('Redirecting...');
}

return [];
