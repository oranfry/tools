<?php

use \subsimple\Config;

foreach (Config::get()->mounted ?? null as $plugin) {
    (function (object $plugin) {
        @include APP_HOME . '/' . $plugin->path . '/src/php/partial/nav/tools-plugin.php';
    })($plugin);
}
