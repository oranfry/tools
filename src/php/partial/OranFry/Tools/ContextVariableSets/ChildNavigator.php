<?php

if (!$this->options) {
    return;
}

$bygone_options = [];
$_value = $this->value;
$_options = $this->options;
$origPathDepth = count($_options);
$i = 0;

while ($options = array_shift($_options)) {
    $selected = array_shift($_value);

    foreach (['property', 'id'] as $thing) {
        if (
            count($options->property) > 1
            || reset($options->property) !== ''
        ) {
            $resetSubGroupsParts = [];

            for ($c = $i; $c < $origPathDepth; $c++) {
                if ($c > $i) {
                    $resetSubGroupsParts[] = $this->prefix . '__property_' . $c . '=';
                }

                if ($c > $i || $thing === 'property') {
                    $resetSubGroupsParts[] = $this->prefix . '__id_' . $c . '=';
                }
            }

            $resetSubGroups = implode('&', $resetSubGroupsParts);

            ?><div class="group-navigator-chunk"><?php
                ?><div class="group-navigator-chunk__input"><?php
                    ?><select<?php
                        ?> class="<?php
                            ?>cv-surrogate<?php

                            if ($this->manips || $resetSubGroups) {
                                echo ' cv-manip';
                            }
                        ?>"<?php
                        ?> data-for="<?= $this->prefix ?>__<?= $thing ?>_<?= $i ?>"<?php

                        if ($this->manips || $resetSubGroups) {
                            ?> data-manips="<?= implode('&', array_filter([$this->manips, $resetSubGroups])) ?>"<?php
                        }
                    ?>><?php
                        ?><option value=""></option><?php

                        foreach ($options->$thing as $index => $_group) {
                            if ($_group === '') {
                                continue; // would create dupe of empty option above
                            }

                            ?><option<?php
                                if (!is_numeric($index)) {
                                    ?> value="<?= $_group ?>"<?php
                                }

                                echo $_group == $selected->$thing ? ' selected' : null;
                            ?>><?php
                                echo is_numeric($index) ? $_group : $index;
                            ?></option><?php
                        }
                    ?></select><?php
                ?></div><?php

                ?><div class="group-navigator-chunk__buttons"><?php
                    ?><a style="display: block; line-height: 0" class="cv-manip" data-manips="<?= implode('&', array_filter([$this->manips, $resetSubGroups])) ?>"><?php
                        ?><i class="icon icon--gray icon--repeat icon--small"></i><?php
                    ?></a><?php
                ?></div><?php
            ?></div><?php
        }

        array_push($bygone_options, $selected);
    }

    $i++;
}
