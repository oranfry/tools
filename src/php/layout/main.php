<?php use contextvariableset\Daterange; ?>
<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title><?= BlendsConfig::get(AUTH_TOKEN)->instance_name ?: 'Blends' ?></title>
    <style>
        .appcolor-bg,
        .button.button--main,
        nav a.current,
        td.today,
        tr.today td,
        .periodchoice.periodchoice--current,
        .nav-dropdown a.current,
        .drnav.current,
        .cv-manip.current {
            background-color: #<?= HIGHLIGHT ?>;
        }

        .button.button--main {
            border: 1px solid #<?= adjustBrightness(HIGHLIGHT, -60) ?>
        }

        <?php
            list($h) = hexToHsl(HIGHLIGHT);
            list(, $s, $l) = hexToHsl(REFCOL);

            $hex = hslToHex([$h, $s, $l]);
        ?>

        .calendar-month .col .icon { filter: hue-rotate(<?= round($h * 360) ?>deg) brightness(0.8); }
        .calendar-month .col { color: #<?= adjustBrightness($hex, -100) ?>; }
    </style>
</head>
<body class="wsidebar">
    <?php require search_plugins('src/php/partial/tools/nav.php'); ?>
    <div class="wrapper">
        <?php if (@$GLOBALS['title']): ?>
            <h3><?= $GLOBALS['title'] ?></h3>
        <?php endif ?>
        <?php if (count(@$warnings ?: [])): ?>
            <br>
            <?php foreach ($warnings as $warning): ?>
                <div class="warning">Warning: <?= $warning ?></div>
            <?php endforeach ?>
            <br>
        <?php endif ?>
        <?php require search_plugins('src/php/partial/content/' . (defined('VIEW') ? VIEW : PAGE) . '.php'); ?>
    </div>

    <form id="instanceform">
        <?php
            foreach (ContextVariableSet::getAll() as $active) {
                $active->inputs();
            }
        ?>
        <input type="hidden" name="_returnurl" value="<?= htmlspecialchars_decode($_SERVER['REQUEST_URI']) ?>">
        <div id="new-vars-here" style="display: none"></div>
    </form>

    <?php if (AUTH_TOKEN): ?>
        <?php $daterange = new Daterange('daterange'); ?>
        <?php $username = Blends::token_username(AUTH_TOKEN); ?>
        <?php $user = Blends::token_user(AUTH_TOKEN); ?>

        <script>
            window.orig_token = '<?= AUTH_TOKEN ?>';
            window.repeater = <?= $repeater->period ? "'" . $repeater->render() . "'" : 'null' ?>;
            window.range_from = <?= $daterange->from ? "'" . $daterange->from . "'" : 'null' ?>;
            window.range_to = <?= $daterange->to ? "'" . $daterange->to . "'" : 'null' ?>;
            window.username = '<?= $username; ?>';
            window.user = <?= $user ? "'{$user}'" : 'null'; ?>;
            <?php foreach (PAGE_PARAMS as $key => $value): ?>
                window.<?= "{$key} = '{$value}'"; ?>;
            <?php endforeach ?>
            <?php if (defined('BLEND_NAME')): ?>
                window.current_filter = '<?= implode('&', array_map(function($f){
                    return $f->field . $f->cmp . (is_array($f->value) ? implode(',' ,$f->value) : $f->value);
                }, get_current_filters(Blend::load(AUTH_TOKEN, BLEND_NAME)->fields))); ?>';
            <?php endif ?>
            <?php if (BACK): ?><?= "var back = '" . BACK . "'"; ?>;<?php endif ?>
        </script>
    <?php endif ?>

    <script type="text/javascript" src="/js/app.<?= latest('js') ?>.js"></script>
    <?php @include APP_HOME . '/src/php/partial/js/' . PAGE . '.php'; ?>
</body>
</html>
