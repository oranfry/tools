<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/build/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title><?= @$title ?? PAGE ?></title>
</head>
<body class="wsidebar">
    <?php require search_plugins('src/php/partial/tools/nav.php'); ?>
    <div class="wrapper">
        <?php if (BACK): ?><br><div class="only-super1200"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
        <?php if ($preflash_view = search_plugins('src/php/partial/preflash/' . (defined('VIEW') ? VIEW : PAGE) . '.php')): ?><?php require $preflash_view; ?><?php endif ?>
        <div class="flash"><?php foreach (is_array(@$flash) ? $flash : [] as $message): ?>
            <div class="flash__message <?= @$message->type ? "flash__message--{$message->type}" : null ?>"><?= $message->message ?></div>
        <?php endforeach ?></div>
        <?php require search_plugins('src/php/partial/content/' . (defined('VIEW') ? VIEW : PAGE) . '.php'); ?>
    </div>

    <script type="text/javascript" src="/build/js/app.<?= latest('js') ?>.js"></script>
    <?php @include APP_HOME . '/src/php/partial/js/' . PAGE . '.php'; ?>
</body>
</html>
