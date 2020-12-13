<?php use contextvariableset\Hidden; ?>
<?php use contextvariableset\Repeater; ?>
<div class="navbar-placeholder" style="height: 2.5em;">&nbsp;</div>
<div class="instances navbar printhide">
    <?php if (BACK): ?><div class="navset sidebar-backlink-container"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
    <?php @include APP_HOME . '/src/php/partial/nav.php'; ?>
    <form id="tokenform" action="/change-token" method="post" class="only-super1200">
        <div class="navset">
            <div class="nav-title">Token</div>
            <input type="text" name="token" value="<?= AUTH_TOKEN ?>" style="width: 100%; padding: 0.5em">
        </div>
    </form>
    <?php if (AUTH_TOKEN): ?>
        <div class="navset">
            <div class="nav-title">Logout</div>
            <i class="icon icon--gray icon--leave trigger-logout" title="Logout <?= Blends::token_username(AUTH_TOKEN) ?>"></i>
        </div>
    <?php endif ?>
</div>
