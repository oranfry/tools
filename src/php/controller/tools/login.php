<?php

use jars\contract\BadTokenException;
use jars\contract\JarsConnector;
use subsimple\Config;

$message = null;

if (defined('AUTH_TOKEN') && AUTH_TOKEN) {
    error_log('Unexpectedly, token is already present');
    die();
}

if ($token = @$_COOKIE['token']) {
    $jars = JarsConnector::connect(CONNECTION_STRING);

    try {
        $jars
            ->token($token)
            ->touch();

        if (!$landingpage = @Config::get()->landingpage) {
            error_response('No landing page defined');
        }

        header('Location: ' . $landingpage);

        die('Redirecting to landing page&hellip;');
    } catch (BadTokenException $e) {
        doover();
    }
}

return [];
