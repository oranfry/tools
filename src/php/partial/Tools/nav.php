<div class="navbar-placeholder" style="height: 2.5em;">&nbsp;</div>
<div class="navbar printhide">
    <?php if (BACK): ?><div class="only-sub1200 navset sidebar-backlink-container"><a class="sidebar-backlink" href="<?= BACK ?>">Back</a></div><?php endif ?>
    <?php ss_include('src/php/partial/nav.php', $viewdata); ?>
    <?php ss_include('src/php/partial/nav/' . PAGE . '.php', $viewdata); ?>

    <div class="navset">
        <div class="inline-rel">
            <div class="inline-modal">
                <form id="tokenform" action="/change-token" method="post" class="only-super1200">
                    <input type="text" name="token" value="<?= AUTH_TOKEN ?>" style="width: 100%; padding: 0.5em">
                </form>
            </div>
            <a href="#" class="inline-modal-trigger"><i class="icon icon--gray icon--ticket"></i></a>
        </div>
        <a href="#" class="trigger-logout <?php if (!AUTH_TOKEN): ?>disabled<?php endif ?>"><i class="icon icon--gray icon--leave" title="Logout"></i></a>
    </div>
</div>
