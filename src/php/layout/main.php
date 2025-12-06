<?php

use ContextVariableSets\ContextVariableSet;
use subsimple\Config;

?><!DOCTYPE html><?php
?><html lang="en-NZ"><?php

?><head><?php
    ?><meta name="viewport" content="width=320, initial-scale=1, user-scalable=no"><?php
    ?><link rel="stylesheet" type="text/css" href="/build/css/styles.<?= latest('css') ?>.css"><?php
    ?><meta charset="utf-8"/><?php
    ?><title><?= @$title ?? PAGE ?></title><?php
?></head><?php

?><body class="wsidebar"><?php
    ?><div id="switcher" class="modal"><?php
        $shownPlugins = array_filter(Config::get()->httpMounted ?? [], fn ($plugin) => !($plugin->options['hidden'] ?? false));

        if (count($shownPlugins) > 1) {
            foreach ($shownPlugins as $plugin) {
                ?><a<?php
                    ?> href="<?= $plugin->httpMountPoint ?>"<?php
                    ?>><?php
                    echo $plugin->title;
                ?></a><?php
                ?><br><?php
            }
        }
        ?><br><?php

        ss_require('src/php/partial/Tools/token-box.php');
        ?><br><?php
        ss_require('src/php/partial/Tools/logout-icon.php');
    ?></div><?php

    ss_require('src/php/partial/Tools/nav.php', $viewdata);

    ?><div class="wrapper"><?php
        if (BACK) {
            ?><br><div class="only-super1200"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php
        }

        ss_include('src/php/partial/preflash/' . (defined('VIEW') ? VIEW : PAGE) . '.php', $viewdata);

        ?><div class="flash"><?php

        foreach (is_array(@$flash) ? $flash : [] as $message) {
            ?><div class="flash__message <?= @$message->type ? "flash__message--{$message->type}" : null ?>"><?= $message->message ?></div><?php
        }

        ?></div><?php

        ss_require('src/php/partial/content/' . (defined('VIEW') ? VIEW : PAGE) . '.php', $viewdata);
    ?></div><?php

    ContextVariableSet::form();

    ?><script type="text/javascript" src="/build/js/app.<?= latest('js') ?>.js"></script><?php

    ss_include('src/php/partial/js/' . PAGE . '.php', $viewdata);
?></body><?php
?></html><?php
