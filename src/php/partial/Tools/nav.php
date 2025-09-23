<?php

use \subsimple\Config;
use obex\Obex;

$plugin = Obex::find(Config::get()->mounted, 'point', 'is', TOOLS_PLUGIN_MOUNT_POINT);

?><div class="navbar-placeholder" style="height: 2.5em;">&nbsp;</div><?php
?><div class="navbar printhide"><?php
    if (BACK) {
        ?><div class="only-sub1200 navset sidebar-backlink-container"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php
    }

    ?><div class="navset"><?php
        ?><span class="switcher-trigger modal-trigger" data-for="switcher"><?php
            ?><i class="icon icon--gray icon--tiles"></i><?php
            ?><span class="only-super1200"><?php
                echo ' ' . TOOLS_PLUGIN_TITLE;
            ?></span><?php
        ?></span><?php
    ?></div><?php

    foreach (TOOLS_PLUGIN_CONFIG->contextVariables() as $var) {
        $var->display();
    }

    if (TOOLS_PLUGIN_INCLUDE_PATH) {
        @include TOOLS_PLUGIN_INCLUDE_PATH . '/src/php/partial/nav/tools-plugin.php';
    }

    ss_include('src/php/partial/nav/' . PAGE . '.php', array_merge(compact('plugin'), $viewdata));
?></div><?php
