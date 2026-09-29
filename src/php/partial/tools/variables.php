<?php

echo '<script>';
    ?>const TOOLS_TOKEN_EXPIRY_DAYS = <?= defined('TOOLS_TOKEN_EXPIRY_DAYS') ? TOOLS_TOKEN_EXPIRY_DAYS : 'null'; ?>;<?php
    ?>const TOOLS_BASE_URL = '<?= TOOLS_BASE_URL ?>';<?php
echo '</script>';
