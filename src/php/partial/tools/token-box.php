<?php

?><div class="inline-rel"><?php
    ?><div class="inline-modal"><?php
        ?><form id="tokenform" action="/change-token" method="post"><?php
            ?><input type="password" name="token" value="<?= AUTH_TOKEN ?>" placeholder="token" autocomplete="current-password" style="min-width: 10em; width: 100%; padding: 0.5em"><?php
        ?></form><?php
    ?></div><?php
    ?><a href="#" class="inline-modal-trigger"><i class="icon icon--gray icon--ticket"></i> Token</a><?php
?></div><?php
