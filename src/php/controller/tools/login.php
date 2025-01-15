<?php

use jars\contract\BadTokenException;
use jars\contract\JarsConnector;
use subsimple\Config;
use subsimple\Exception;

$message = null;

if (defined('AUTH_TOKEN') && AUTH_TOKEN) {
    throw new Exception('Unexpectedly, token is already present');
}

if ($token = @$_COOKIE['token']) {
    $jars = JarsConnector::connect(CONNECTION_STRING);

    try {
        $jars
            ->token($token)
            ->touch();

        if (!$landingpage = @Config::get()->landingpage) {
            throw new Exception('No landing page defined');
        }

        header('Location: ' . $landingpage);

        die('Redirecting to landing page&hellip;');
    } catch (BadTokenException $e) {
        doover();
    }
}

return [];
