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
    background-color: #<?= HIGHLIGHT ?>;
}

.navbar .listable a.current {
    color: #333;
}

.button.button--main {
    border: 1px solid #<?= adjustBrightness(HIGHLIGHT, -60) ?>
}
