<?php
date_default_timezone_set('Asia/Tokyo');
//TODO:mbstringを使えるように、herokuの設定変更。
if ($_SERVER['HTTP_MB_EMULATOR'] == 'on')
	require_once '../php/mb-emulator/mb-emulator.php';

require_once 'library/Slim/Slim.php';
require_once 'library/fb/facebook.php';

$app = new Slim();
$facebook = new Facebook(array(
	'appId'=>$_SERVER['HTTP_FB_APPID'],
	'secret'=>$_SERVER['HTTP_FB_SECRET'],
));

/**
 * 過去20件のイベント情報
 */
$app->get('/fb/event/last_20.json', function () use ($facebook) {
	$uid = '131130253626713';
	$yesterday = time()-60*60*12;
	$fql = <<<____FQL
		SELECT description, eid, name, pic_small, start_time
		FROM event
		WHERE
			eid in (
				SELECT eid 
				FROM event_member 
				WHERE uid = $uid AND 0 < start_time AND start_time < $yesterday)
			AND privacy = 'OPEN'
		ORDER BY start_time DESC
		LIMIT 20
____FQL;
	$result = $facebook->api(array('method'=>'fql.query','query'=>$fql));
	$data = array();
	foreach ($result as $row) $data[] = array(
		'description' => mb_strimwidth($row['description'], 0, 400, '...', 'UTF-8'),
		'eid' => $row['eid'],
		'name' => preg_replace('/^下北沢オープンソースCafe - /', '', $row['name']),
		'pic_small' => $row['pic_small'],
		'date' => date('M j', $row['start_time']),
		'day' => date('D', $row['start_time']),
	);
	echo json_encode($data);
});

/**
 * 週始めから4週間分のイベント情報
 */
$app->get('/fb/event/calendar.json', function () use ($facebook) {
	$uid = '131130253626713';
	$di = getdate();
	$start_date = mktime(0, 0, 0, $di['mon'], $di['mday'] - $di['wday'], $di['year']);
	$end_date = $start_date + 60*60*24*7*4;
	$fql = <<<____FQL
		SELECT description, eid, name, pic_small, start_time
		FROM event
		WHERE
			eid in (
				SELECT eid 
				FROM event_member 
				WHERE uid = $uid AND $start_date < start_time AND start_time < $end_date)
			AND privacy = 'OPEN'
		ORDER BY start_time ASC
____FQL;
	$result = $facebook->api(array('method'=>'fql.query','query'=>$fql));
	$data = array();
	for ($i = 0; $i < 4; $i++){
		$week_data = array();
		for ($j = 0; $j < 7; $j++) $week_data[] = array(
			'date' => date('M j', $start_date + ($i*7 + $j) * (60*60*24)),
			'events' => array(),
		);
		$data[] = array(
			'days' => $week_data,
		);
	}
	foreach ($result as $row){
		$di2 = getdate($row['start_time']-0);
		$d2 = mktime(0, 0, 0, $di2['mon'], $di2['mday'], $di2['year']);
		$d3 = ($d2 - $start_date) / (60*60*24);
		$wn = floor($d3 / 7); $dn = $d3 % 7;
		if (isset($data[$wn]['days'][$dn])) $data[$wn]['days'][$dn]['events'][] = array(
			'eid' => $row['eid'],
			'name' => preg_replace('/(^下北沢オープンソースCafe - | \(OSSの部室\)$| @下北沢$| \(メンター用\)$)/', '', $row['name']),
			'date' => date('M j',$row['start_time']),
			'day' => date('D',$row['start_time']),
			'pic' => $row['pic_small'],
		);
	}
	echo json_encode($data);
});

/**
 * グループのメンバー情報
 */
$app->get('/fb/group/:gid/members.json', function ($gid) use ($facebook) {
	$fql = <<<____FQL
		select uid, username, name, pic, pic_square, profile_url
		from user
		where uid in (select uid from group_member where gid = $gid)
____FQL;
	$result = $facebook->api(array('method'=>'fql.query','query'=>$fql));
	if (!sizeof($result)){
		$result = $facebook->api("/$gid/members");
	}
    echo json_encode($result);
});

$app->run();
