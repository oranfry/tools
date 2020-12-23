<?php

$api = new ApiClient(AUTH_TOKEN, APIURL);

return ['data' => $api->bulkdelete(BLEND_NAME,  get_query_filters())];
