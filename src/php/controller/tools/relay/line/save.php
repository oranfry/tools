<?php

$api = new ApiClient(AUTH_TOKEN, APIURL);
$data = json_decode(file_get_contents('php://input'));

return ['data' => $api->save(LINETYPE_NAME, $data)];