<?php
$message = null;

if (defined('AUTH_TOKEN') && AUTH_TOKEN) {
    error_log('Unexpectedly, token is already present');
    die();
}

if (@$_COOKIE['token']) {
    $api = ApiClient::http($_COOKIE['token'], APIURL);

    if ($api->touch()) {
        if (!$landingpage = @Config::get()->landingpage) {
            error_response('No landing page defined');
        }

        header('Location: ' . $landingpage);

        die('Redirecting to ' . $landingpage . '...');
    }
}

return [];
