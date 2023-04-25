<?php

use jars\client\HttpClient;
use subsimple\Config;

$message = null;

if (defined('AUTH_TOKEN') && AUTH_TOKEN) {
    error_log('Unexpectedly, token is already present');
    die();
}

if ($token = @$_COOKIE['token']) {
    $jars = HttpClient::of(APIURL);
    $jars->token($token);

    if ($jars->touch()) {
        if (!$landingpage = @Config::get()->landingpage) {
            error_response('No landing page defined');
        }

        header('Location: ' . $landingpage);

        die('Redirecting to ' . $landingpage . '...');
    }
}

return [];
