<!DOCTYPE html>
<html lang="en-NZ">
<head>
    <meta name="viewport" content="width=320, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="/build/css/styles.<?= latest('css') ?>.css">
    <meta charset="utf-8"/>
    <title><?= @$title ?? PAGE ?></title>
</head>
<body class="wsidebar">
    <?php ss_require('src/php/partial/tools/nav.php', $viewdata); ?>
    <div class="wrapper">
        <?php if (BACK): ?><br><div class="only-super1200"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
        <?php ss_include('src/php/partial/preflash/' . (defined('VIEW') ? VIEW : PAGE) . '.php', $viewdata); ?>
        <div class="flash"><?php foreach (is_array(@$flash) ? $flash : [] as $message): ?>
            <div class="flash__message <?= @$message->type ? "flash__message--{$message->type}" : null ?>"><?= $message->message ?></div>
        <?php endforeach ?></div>
        <?php ss_require('src/php/partial/content/' . (defined('VIEW') ? VIEW : PAGE) . '.php', $viewdata); ?>
    </div>

    <form id="instanceform">
        <div style="display: none;">
            <?php foreach (ContextVariableSet::getAll() as $active) : ?><?php $active->inputs(); ?><?php endforeach ?>
            <div id="new-vars-here"></div>
        </div>
    </form>

    <script type="text/javascript" src="/build/js/app.<?= latest('js') ?>.js"></script>
    <?php ss_include('src/php/partial/js/' . PAGE . '.php', $viewdata); ?>
</body>
</html>
