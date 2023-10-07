<?php

if (!$this->options):
    return;
endif;

$bygone_options = [];
$_value = $this->value;
$_options = $this->options;
$i = 0;

?><div style="margin-bottom: 2em;"><?php

while ($options = array_shift($_options)):
    $selected = array_shift($_value);

    if (count($options) > 1 || reset($options) !== ''):
        ?><select class="cv-surrogate" data-for="<?= $this->prefix ?>__<?= $i ?>"><?php
            ?><option></option><?php

            foreach ($options as $_group):
                if ($_group === '') {
                    continue; // would create dupe of empty option above
                }

                $current = $_group == $selected ? 'selected' : null;

                ?><option <?= $current ?>><?= $_group ?></option><?php
            endforeach;
        ?></select><?php
    endif;

    array_push($bygone_options, $selected);

    $i++;
endwhile;

?></div>
