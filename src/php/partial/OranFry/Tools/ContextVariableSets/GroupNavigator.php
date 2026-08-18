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

    if (count($options) > 1 || reset($options) !== '') {
        $resetSubGroups = null;

        for ($c = $i + 1; $c < $origPathDepth; $c++) {
            if ($c > $i + 1) {
                $resetSubGroups .= '&';
            }

            $resetSubGroups .= $this->prefix . '__' . $c . '=';
        }

        ?><div class="group-navigator-chunk"><?php
            ?><div class="group-navigator-chunk__input"><?php
                ?><select<?php
                    ?> class="<?php
                        ?>cv-surrogate<?php

                        if ($this->manips || $resetSubGroups) {
                            echo ' cv-manip';
                        }
                    ?>"<?php

                    ?> data-for="<?= $this->prefix ?>__<?= $i ?>"<?php

                    if ($this->manips || $resetSubGroups) {
                        ?> data-manips="<?= implode('&', array_filter([$this->manips, $resetSubGroups])) ?>"<?php
                    }
                ?>><?php
                    ?><option value=""></option><?php

                    foreach ($options as $_group) {
                        if ($_group === '') {
                            continue; // would create dupe of empty option above
                        }

                        ?><option<?php
                            ?> value="<?= $_group ?>"<?php

                            if ($_group == $selected) {
                                ?> selected<?php
                            }
                        ?>><?php
                            echo $_group;
                        ?></option><?php
                    }
                ?></select><?php
            ?></div><?php

            ?><div class="group-navigator-chunk__buttons"><?php
                if ($this->manips || $resetSubGroups) {
                    ?><a style="display: block; line-height: 0" class="cv-manip" data-manips="<?= implode('&', array_filter([$this->manips, $resetSubGroups])) ?>"><?php
                        ?><i class="icon icon--gray icon--repeat icon--small"></i><?php
                    ?></a><?php
                }
            ?></div><?php
        ?></div><?php
    }

    array_push($bygone_options, $selected);

    $i++;
}
