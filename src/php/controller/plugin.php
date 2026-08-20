<?php

global $jars;

$data = compact('jars');

if (defined('TOOLS_PLUGIN_CONFIG')) {
    $result = TOOLS_PLUGIN_CONFIG->boot($jars);

    if ($result !== null) {
        $data += $result;
    }
}

return $data;
