<div class="inline-rel">
    <div class="inline-modal">
        <form id="tokenform" action="/change-token" method="post">
            <input type="password" name="token" value="<?= AUTH_TOKEN ?>" placeholder="token" autocomplete="current-password" style="min-width: 10em; width: 100%; padding: 0.5em">
        </form>
    </div>
    <a href="#" class="inline-modal-trigger"><i class="icon icon--gray icon--ticket"></i></a>
</div>