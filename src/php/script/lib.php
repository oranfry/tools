<?php

use jars\client\HttpClient;
use jars\contract\BadTokenException;
use subsimple\Config;

const REF_SATURATION = 0.4;
const REF_LIGHTNESS = 0.73;

define('BACK', @$_GET['back'] ? base64_decode($_GET['back']) : null);

function adjustBrightness($hex, $steps)
{
    $steps = max(-255, min(255, $steps));
    preg_match('/^(#)/', $hex, $groups);
    $hash = @$groups[1] ?: '';
    $hex = str_replace('#', '', $hex);

    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex, 0, 1), 2).str_repeat(substr($hex, 1, 1), 2).str_repeat(substr($hex, 2, 1), 2);
    }

    $color_parts = str_split($hex, 2);
    $return = $hash;

    foreach ($color_parts as $color) {
        $color = hexdec($color);
        $color = max(0, min(255, $color + $steps));
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }

    return $return;
}

function get_flat_list($name)
{
    $lists = Blend::load(AUTH_TOKEN, 'lists')->search(AUTH_TOKEN, [(object)['field' => 'name', 'cmp' => '=', 'value' => $name]]);

    if (@$lists->error || count($lists) != 1) {
        return;
    }

    $list = reset($lists);
    Linetype::load(AUTH_TOKEN, 'list')->load_children(AUTH_TOKEN, $list);

    return array_map(function($i) {
        return $i->item;
    }, $list->listitems);
}

function get_query_filters()
{
    $filters = [];

    foreach (explode('&', $_SERVER['QUERY_STRING']) as $v) {
        $r = preg_split('/(\*=|>=|<=|~|=|<|>)/', urldecode($v), -1, PREG_SPLIT_DELIM_CAPTURE);

        if (count($r) == 3) {
            $values = explode(',', $r[2]);
            $filters[] = (object) [
                'field' => $r[0],
                'cmp' => $r[1],
                'value' => count($values) > 1 ? $values : reset($values),
            ];
        }
    }

    return $filters;
}

function hexToHsl($hex)
{
    $hex = array($hex[0].$hex[1], $hex[2].$hex[3], $hex[4].$hex[5]);
    $rgb = array_map(function ($part) {
        return hexdec($part) / 255;
    }, $hex);

    $max = max($rgb);
    $min = min($rgb);

    $l = ($max + $min) / 2;

    if ($max == $min) {
        $h = $s = 0;
    } else {
        $diff = $max - $min;
        $s = $l > 0.5 ? $diff / (2 - $max - $min) : $diff / ($max + $min);

        switch ($max) {
            case $rgb[0]:
                $h = ($rgb[1] - $rgb[2]) / $diff + ($rgb[1] < $rgb[2] ? 6 : 0);
                break;
            case $rgb[1]:
                $h = ($rgb[2] - $rgb[0]) / $diff + 2;
                break;
            case $rgb[2]:
                $h = ($rgb[0] - $rgb[1]) / $diff + 4;
                break;
        }

        $h /= 6;
    }

    return array($h, $s, $l);
}

function hslToHex($hsl)
{
    list($h, $s, $l) = $hsl;

    if ($s == 0) {
        $r = $g = $b = 1;
    } else {
        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;

        $r = hue2rgb($p, $q, $h + 1 / 3);
        $g = hue2rgb($p, $q, $h);
        $b = hue2rgb($p, $q, $h - 1 / 3);
    }

    return rgb2hex($r) . rgb2hex($g) . rgb2hex($b);
}

function hue2rgb($p, $q, $t)
{
    if ($t < 0) {
        $t += 1;
    }

    if ($t > 1) {
        $t -= 1;
    }

    if ($t < 1 / 6) {
        return $p + ($q - $p) * 6 * $t;
    }

    if ($t < 1 / 2) {
        return $q;
    }

    if ($t < 2 / 3) {
        return $p + ($q - $p) * (2 / 3 - $t) * 6;
    }

    return $p;
}

function postroute_tools()
{
    global $jars;

    $jars = HttpClient::of(APIURL);

    if (!defined('AUTH_TOKEN')) {
        switch (AUTHSCHEME) {
            case 'header':
                define('AUTH_TOKEN', @getallheaders()['X-Auth']);

                break;
            case 'cookie':
                define('AUTH_TOKEN', @$_COOKIE['token']);

                break;
            case 'pre':
                break;
            case 'none':
                define('AUTH_TOKEN', null);

                break;
            case 'onetime':
                define('AUTH_TOKEN', HttpClient::of(APIURL)->login(USERNAME, PASSWORD));

                break;
            case 'deny':
                error_response('Access Denied', 403);

            default:
                error_log('Invalid AUTHSCHEME: ' . AUTHSCHEME);
                error_response('Internal Server Error', 500);
        }
    }

    if (in_array(AUTHSCHEME, ['header', 'cookie', 'pre', 'onetime'])) {
        if (!AUTH_TOKEN) {
            doover();
        }

        try {
            $jars
                ->token(AUTH_TOKEN)
                ->touch();
        } catch (BadTokenException $e) {
            doover();
        }
    }
}

function rgb2hex($rgb)
{
    return str_pad(dechex((int) ($rgb * 255)), 2, '0', STR_PAD_LEFT);
}

function set_highlight($hue)
{
    define('HIGHLIGHT', hslToHex([$hue, REF_SATURATION, REF_LIGHTNESS]));
}

function doover()
{
    setcookie('token', '', time() - 3600);
    header('Location: /');

    echo "Please restart the login process\n";

    die();
}

function init_tools()
{
    if ($hue = @Config::get()->highlight_hue) {
        set_highlight($hue);
    }
}
