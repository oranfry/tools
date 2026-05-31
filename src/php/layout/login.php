<?php

use ContextVariableSets\ContextVariableSet;

?><!DOCTYPE html><?php
?><html lang="en-NZ"><?php
?><head><?php
    ?><meta name="viewport" content="width=320, initial-scale=1, user-scalable=no"><?php
    ?><link rel="stylesheet" type="text/css" href="/build/css/styles.<?= latest('css') ?>.css"><?php
    ?><meta charset="utf-8"/><?php
    ?><title>Log In</title><?php
?></head><?php
?><body><?php
    ?><div style="text-align: center; padding: 3em;"><?php
        ?><div class="login-page"><?php
            ?><h1 style="margin-bottom: 1em;">Tools</h1><?php
            ?><form id="loginform"><?php
                ?><div class="cred-line"><?php
                    ?><p>Username</p><?php
                    ?><input type="text" name="username" id="auth" autocomplete="off" value="<?= @$username ?>"><?php
                ?></div><?php
                ?><div class="cred-line"><?php
                    ?><p>Password</p><?php
                    ?><input type="password" name="password" id="password" autocomplete="current-password"><?php
                ?></div><?php
                ?><div class="cred-line"><?php
                    ?><input type="submit" value="Sign In"><?php
                ?></div><?php
            ?></form><?php
        ?></div><?php

        ?><script>document.getElementById('<?= @$username ? 'password' : 'auth' ?>').focus();</script><?php

        ?><div style="margin-top: 6em"><?php
            ?><div class="navset"><?php
                ss_require('src/php/partial/Tools/token-box.php');
            ?></div><?php
        ?></div><?php
    ?></div><?php

    ContextVariableSet::form();

    ss_require('src/php/partial/Tools/variables.php');

    ?><script type="text/javascript" src="/build/js/app.<?= latest('js') ?>.js"></script><?php
?></body><?php
?></html><?php
