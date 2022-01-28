<?php

$api = ApiClient::http(AUTH_TOKEN, APIURL);
$data = json_decode(file_get_contents('php://input'));

return ['data' => $api->bulkupdate(BLEND_NAME, $data, get_query_filters())];
