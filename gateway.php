<?php
define('APP_CACHE_DIR', __DIR__ . '/../cache');
require_once 'php/fb/facebook.php';
require_once 'php/FacebookGateway.php';

//外部から来る変数
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
$refresh = isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == 'true';
//$refresh = true;//for debug

$gateway = new FacebookGateway($_SERVER['FB_APPID'], $_SERVER['FB_SECRET'], $_SERVER['FB_PAGEID']);
echo $gateway->get_json($type, $refresh);