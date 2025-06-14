<?php $highlight = defined('HIGHLIGHT') ? HIGHLIGHT : '#ff0000'; ?>
.appcolor-bg,
.button.button--main,
nav a.current,
td.today,
tr.today td,
.periodchoice.periodchoice--current,
.nav-dropdown a.current,
.drnav.current,
.cv-manip.current,
.navbar .listable a.current {
    background-color: #<?= $highlight ?>;
    color: #333;
}

.navbar .listable a.current {
    color: #333;
}

.button.button--main {
    border: 1px solid #<?= adjustBrightness($highlight, -60) ?>
}

.button.button--main.disabled {
    background-color: #<?= adjustBrightness($highlight, 60) ?>;
    border: 1px solid #<?= $highlight ?>;
}