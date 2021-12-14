<?php

$api = ApiClient::http(AUTH_TOKEN, APIURL);

return ['data' => $api->get(LINETYPE_NAME, LINE_ID)];
