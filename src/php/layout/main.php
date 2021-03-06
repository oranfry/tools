<?php use contextvariableset\Daterange; ?>
<?php use contextvariableset\Repeater; ?>
<?php $repeater = ContextVariableSet::get('repeater'); ?>
<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title><?= BlendsConfig::get(AUTH_TOKEN)->instance_name ?: 'Blends' ?> &bull; <?= $title ?? PAGE ?></title>
</head>
<body class="wsidebar">
    <?php require search_plugins('src/php/partial/tools/nav.php'); ?>
    <div class="wrapper">
        <?php if (BACK): ?><br><div class="only-super1200"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
        <h3><?= $title ?? PAGE ?></h3>
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
        <?php foreach (ContextVariableSet::getAll() as $active) : ?><?php $active->inputs(); ?><?php endforeach ?>
        <input type="hidden" name="_returnurl" value="<?= htmlspecialchars_decode($_SERVER['REQUEST_URI']) ?>">
        <div id="new-vars-here" style="display: none"></div>
    </form>

    <?php if (AUTH_TOKEN): ?>
        <?php $daterange = new Daterange('daterange'); ?>
        <?php $username = Blends::token_username(AUTH_TOKEN); ?>
        <?php $user = Blends::token_user(AUTH_TOKEN); ?>

        <script>
            window.orig_token = '<?= AUTH_TOKEN ?>';
            window.repeater = <?= $repeater && $repeater->period ? "'" . $repeater->render() . "'" : 'null' ?>;
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
