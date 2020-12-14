<?php

$api = new ApiClient(null, APIURL);
$data = json_decode(file_get_contents('php://input'));

return ['data' => $api->login(@$data->username, @$data->password)];