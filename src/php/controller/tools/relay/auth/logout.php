<?php

$api = new ApiClient($token, APIURL);

return ['data' => $api->logout()];