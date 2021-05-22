<span class="file-field-controls" data-hasvalue="<?= (bool) $value ?>">
    <span class="file-field-controls__actions" <?= !$value && !@$bulk ? 'style="display:none"' : '' ?>>
        <?php if (!@$bulk): ?>
            <div style="margin-bottom: 0.5em"><a href="/api/download/<?= $value ?>" download><i class="icon icon--gray icon--<?= @$field->translate[$field->icon] ?? $field->icon ?>"></i></a></div>
        <?php endif ?>
        <span class="button file-field-controls__change"><?= @$bulk ? 'choose' : 'change' ?></span>
        <span class="button file-field-controls__delete">delete</span>
        <?php if (@$field->generable): ?>
            <span class="button file-field-controls__generate">generate</span>
        <?php endif ?>
    </span>
    <span class="file-field-controls__input" <?= $value || @$bulk ? 'style="display:none"' : '' ?>>
        <input class="field value" type="file" name="<?= $field->name ?>" style="width: 16em">
        <?php if (@$field->generable): ?>
            <span class="button file-field-controls__generate" <?= @$bulk ? 'style="display: none"' : '' ?>>generate</span>
        <?php endif ?>
        <span class="button file-field-controls__cancel" <?= !$value && !@$bulk ? 'style="display:none"' : '' ?>>cancel</span>
    </span>
    <span class="file-field-controls__willdelete" style="display:none">
        Will delete on save
        <span class="button file-field-controls__cancel">cancel</span>
    </span>
    <span class="file-field-controls__willgenerate" style="display:none">
        Will generate on save
        <span class="button file-field-controls__cancel">cancel</span>
    </span>
</span>
