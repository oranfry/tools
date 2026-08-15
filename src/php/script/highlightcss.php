<?php

$highlight = defined('HIGHLIGHT') ? HIGHLIGHT : '#ff0000';

echo implode(',', [
    '.appcolor-bg',
    '.button.button--main',
    'nav a.current',
    'td.today',
    'tr.today td',
    '.periodchoice.periodchoice--current',
    '.nav-dropdown a.current',
    '.drnav.current',
    '.cv-manip.current',
    '.navbar .listable a.current',
]);

?>{<?php
    ?>background-color: #<?= $highlight ?>;<?php
    ?>color: #333;<?php
?>}<?php

?>.navbar .listable a.current {<?php
    ?>color: #333;<?php
?>}<?php

?>.button.button--main {<?php
    ?>border: 1px solid #<?= adjustBrightness($highlight, -60) ?>;<?php
?>}<?php

?>.button.button--main.disabled {<?php
    ?>background-color: #<?= adjustBrightness($highlight, 60) ?>;<?php
    ?>border: 1px solid #<?= $highlight ?>;<?php
?>}<?php

