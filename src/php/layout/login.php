<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/build/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title>Log In</title>
</head>
<body>
    <div style="text-align: center; padding: 3em;">
        <div class="login-page">
            <h1 style="margin-bottom: 1em;">Tools</h1>
            <form id="loginform">
                <div class="cred-line">
                    <p>Username</p>
                    <input type="text" name="username" id="auth" autocomplete="off" value="<?= @$username ?>">
                </div>
                <div class="cred-line">
                    <p>Password</p>
                    <input type="password" name="password" id="password" autocomplete="current-password">
                </div>
                <div class="cred-line">
                    <input type="submit" value="Sign In">
                </div>
            </form>
        </div>

        <script>document.getElementById('<?= @$username ? 'password' : 'auth' ?>').focus();</script>

        <div style="margin-top: 6em">
            <div class="navset">
                <?php ss_require('src/php/partial/Tools/token-box.php'); ?>
            </div>
        </div>
    </div>

    <?php \ContextVariableSets\ContextVariableSet::form(); ?>
    <script type="text/javascript" src="/build/js/app.<?= latest('js') ?>.js"></script>
</body>
</html>
