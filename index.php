<?php
clearstatcache();

error_reporting(0);
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, token, Content-Type, Origin, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");
header('Content-type:application/json;charset=utf-8');


if(isset($_REQUEST['route'])) :
    
    require_once 'config.php';
    require_once ROUTER;
    
else :
    
    $res =  ($_REQUEST['error'] == 404) ? ['status' => 404, 'message' => 'route not found'] : ['status' => 500, 'message' => 'internal server error'];

    echo json_encode($res); die;
    
endif;