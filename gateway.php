<?php
//define('APP_CACHE_DIR', __DIR__ . '/../cache');
require_once 'php/fb/facebook.php';
require_once 'php/FacebookGateway.php';
if ($_SERVER['HTTP_MB_EMULATOR'] == 'on')
	require_once 'php/mb-emulator/mb-emulator.php';
date_default_timezone_set('Asia/Tokyo');

//外部から来る変数
$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
//$refresh = isset($_REQUEST['refresh']) && $_REQUEST['refresh'] == 'true';
//$refresh = true;//for debug

$gateway = new FacebookGateway($_SERVER['HTTP_FB_APPID'], $_SERVER['HTTP_FB_SECRET'], $_SERVER['HTTP_FB_PAGEID']);
echo $gateway->get_json($type);//, $refresh);