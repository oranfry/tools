<?php $api = new ApiClient(AUTH_TOKEN, APIURL); ?>
<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title><?= @$title ?? PAGE ?></title>
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

    <script type="text/javascript" src="/js/app.<?= latest('js') ?>.js"></script>
    <?php @include APP_HOME . '/src/php/partial/js/' . PAGE . '.php'; ?>
</body>
</html>
