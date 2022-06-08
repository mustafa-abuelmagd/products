<?php
function response($response)
{
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin: *');

//    echo json_encode($response);
    return json_encode($response);

}