<div class="inline-rel">
    <div class="inline-modal">
        <form id="tokenform" action="/change-token" method="post" class="only-super1200">
            <input type="text" name="token" value="<?= AUTH_TOKEN ?>" autofill="one-time-code" placeholder="token" style="min-width: 10em; width: 100%; padding: 0.5em">
        </form>
    </div>
    <a href="#" class="inline-modal-trigger"><i class="icon icon--gray icon--ticket"></i></a>
</div>