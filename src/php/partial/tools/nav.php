<div class="navbar-placeholder" style="height: 2.5em;">&nbsp;</div>
<div class="instances navbar printhide">
    <?php if (BACK): ?><div class="only-sub1200 navset sidebar-backlink-container"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
    <?php @include search_plugins('src/php/partial/nav.php'); ?>
    <?php @include search_plugins('src/php/partial/nav/' . PAGE . '.php'); ?>

    <div class="navset">
        <div class="inline-rel">
            <div class="inline-modal">
                <form id="tokenform" action="/change-token" method="post" class="only-super1200">
                    <input type="text" name="token" value="<?= AUTH_TOKEN ?>" style="width: 100%; padding: 0.5em">
                </form>
            </div>
            <i class="icon icon--gray icon--ticket inline-modal-trigger"></i>
        </div>
        <?php if (AUTH_TOKEN): ?>
            <i class="icon icon--gray icon--leave trigger-logout" title="Logout"></i>
        <?php endif ?>
    </div>
</div>
