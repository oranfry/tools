<?php

with_plugins(function($pdir, $name) use ($latests) {
    $dir = "{$pdir}/src/icon";

    if (!is_dir($dir)) {
        return;
    }

    $handle = opendir($dir);

    while ($file = readdir($handle)) {
        if (!preg_match('/(.*)\.png$/', $file, $groups)) {
            continue;
        }

        $icon = $groups[1];

        echo ".icon--{$icon} { background-image: url(/build" . TOOLS_BASE_URL . "/img/icon/{$icon}.{$latests['icon']['hash']}.png); }\n";
    }

    closedir($handle);

    echo "input[type=\"checkbox\"]:checked { background-image: url(/build" . TOOLS_BASE_URL . "/img/icon/tick.{$latests['icon']['hash']}.png); }\n";
});
