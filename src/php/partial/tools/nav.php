<?php

use OranFry\Subsimple\Config;
use OranFry\Obex\Obex;

$plugin = Obex::find(Config::get()->mounted, 'httpMountPoint', 'is', TOOLS_PLUGIN_MOUNT_POINT);

?><div class="navbar-placeholder" style="height: 2.5em;">&nbsp;</div><?php
?><div class="navbar-container printhide"><?php
    ?><div class="nav-hub appcolor-bg"><?php
        ?><div class="switcher-trigger modal-trigger" data-for="switcher"><?php
            ?><i class="icon icon--gray icon--tiles"></i><?php
        ?></div><?php
        ?><div class="modal-trigger" data-for="switcher"><?php
            echo ' ' . TOOLS_PLUGIN_TITLE;
        ?></div><?php
        ?><div class="menu-trigger"><?php
            ?><i class="icon icon--gray icon--hamburger only-sub1200"></i><?php
        ?></div><?php
    ?></div><?php

    ?><div class="navbar"><?php
        ?><div style="height: 32.8px">&nbsp;</div><?php

        foreach (TOOLS_PLUGIN_CONFIG->contextVariables() as $var) {
            ?><div id="cvs-<?= $var->prefix ?>" style="margin: 1em 0 1em"><?php
                $var->display();
            ?></div><?php
        }

        if (TOOLS_PLUGIN_INCLUDE_PATH) {
            @include TOOLS_PLUGIN_INCLUDE_PATH . '/src/php/partial/nav/tools-plugin.php';
        }

        ss_include('src/php/partial/nav/' . PAGE . '.php', array_merge(compact('plugin'), $viewdata));
    ?></div><?php
?></div><?php
