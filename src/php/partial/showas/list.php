<table class="easy-table">
    <thead>
        <tr>
            <?php if (0 && @$editable) : ?>
                <th class="select-column printhide"><i class="icon icon--gray icon--smalldot-o selectall"></i></td></th>
            <?php endif ?>
            <?php foreach ($fields as $field): ?>
                <th class="<?= $field->type == 'number' ? 'right' : '' ?>"><?= $field->name ?></th>
            <?php endforeach ?>
            <?php if (@$editable) : ?><th></th><?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($records as $i => $record): ?>
            <tr
                <?= @$parent ? "data-parent=\"{$parent}\"" : '' ?>
                data-id="<?= @$record->id ?>"
                data-type="<?= @$record->type ?>"
            >
                <?php if (0 && @$editable) : ?>
                    <td class="select-column printhide"><input type="checkbox"></td>
                <?php endif ?>
                <?php foreach ($fields as $field): ?>
                    <?php $value = @$field->value ? computed_field_value($record, $field->value) : @$record->{$field->name}; ?>
                    <td data-value="<?= $value ?>" style="<?= $field->type == "number" ? 'text-align: right;' : '' ?>" class="<?= @$field->sacrifice ? 'sacrifice' : '' ?>"><?php
                        if (@$field->customlink) {
                            ?><a class="incog" href="<?= is_string($field->customlink) ? computed_field_value($record, $field->customlink) : $record->{"{$field->name}_link"} ?>" <?= @$field->download ? 'download' : '' ?>><?php
                        }

                        if ($field->type == 'icon') {
                            ?><i class="icon icon--gray icon--<?= @$field->translate->{$value} ?? $value ?>"></i><?php
                        } elseif ($field->type == 'color') {
                            ?><span style="display: inline-block; height: 1em; width: 1em; background-color: #<?= $value ?>;">&nbsp;</span><?php
                        } elseif ($field->type == 'file' && @$record->{"{$field->name}_path"}) {
                           ?><a href="/api/download/<?= $record->{"{$field->name}_path"} ?>" download><i class="icon icon--gray icon--<?= @$field->translate[$field->icon] ?? $field->icon ?>"></i></a><?php
                        } else {
                            echo is_callable(@$field->prefix) ? ($field->prefix)($record) : @$field->prefix;
                            echo $field->type == 'fake' ? $field->value : $value;
                            echo is_callable(@$field->suffix) ? ($field->suffix)($record) : @$field->suffix;
                        }

                        if (@$field->customlink) {
                            ?></a><?php
                        }
                    ?></td>
                <?php endforeach ?>
                <?php if (@$editable) : ?>
                    <td class="printhide" style="text-align: right; vertical-align: middle">
                        <a href="<?= editlink($record->id, $record->type) ?>"><i class="icon icon--gray icon--edit"></i></a>
                        <?php if (@$parent): ?><i class="trigger-unlink-line icon icon--gray icon--unlink"></i><?php endif ?>
                        <i class="trigger-delete-line icon icon--gray icon--times"></i>
                    </td>
                <?php endif ?>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
