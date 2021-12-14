<?php

$api = ApiClient::http(AUTH_TOKEN, APIURL);

return ['data' => $api->logout()];
