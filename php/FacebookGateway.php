<?php
class FacebookGateway {
	private $connection;
	private $pageId;
	
	function __construct($appId, $secret, $pageId){
		$this->connection = new Facebook(array('appId'=>$appId, 'secret'=>$secret));
		$this->pageId = $pageId;
	}
	
	public function get_json($type){//, $refresh){
		/*
		$hash = md5($type);
		$dir = APP_CACHE_DIR . '/fbgateway';
		if (!file_exists($dir)) mkdir($dir, 0777, true);
		$file = "$dir/$hash.json";
		if (!$refresh && file_exists($file) && filemtime($file) > mktime()-60*60*6)
			return file_get_contents($file);
		*/
		
		$data = array();
		switch ($type){
			case 'coming_events':
			case 'coming_events_with_rspv':
			case 'featured_events':
			case 'calendar':
			case 'last_20_events':
			case 'books': $data = $this->$type(); break;
		}
		$json = json_encode($data);
		//file_put_contents($file, $json);
		header('Cache-Control: public,max-age=3600');
		return $json;
	}
	
	public function coming_events(){
		$yesterday = time()-60*60*24;
		$fql = <<<________FQL
			SELECT creator, description, eid, end_time, location, name, pic, pic_big, pic_small, start_time
			FROM event
			WHERE
				eid in (
					SELECT eid 
					FROM event_member 
					WHERE uid = 131130253626713 AND $yesterday < start_time)
				AND privacy = 'OPEN'
			ORDER BY start_time ASC
			LIMIT 1, 5
________FQL;
		$result = $this->connection->api(array('method'=>'fql.query','query'=>$fql));
		$data = array();
		foreach ($result as $row)
			$data[] = FacebookGateway::process_event($row);
		return $data;
	}
	
	public function coming_events_with_rspv(){
		$yesterday = time()-60*60*24;
		$fql = array();
		$fql['events'] = <<<________FQL
			SELECT creator, description, eid, end_time, location, name, pic, pic_big, pic_small, start_time
			FROM event
			WHERE
				eid in (
					SELECT eid 
					FROM event_member 
					WHERE uid = 131130253626713 AND $yesterday < start_time)
				AND privacy = 'OPEN'
			ORDER BY start_time
			LIMIT 1, 5
________FQL;
		$fql['map'] = <<<________FQL
			SELECT eid, uid
			FROM event_member
			WHERE
				rsvp_status IN ("attending", "unsure") AND
				eid in (SELECT eid FROM #events)
________FQL;
		$fql['attendees'] = <<<________FQL
			SELECT uid, pic_square, profile_url
			FROM user
			WHERE uid in (SELECT uid FROM #map)
________FQL;
		$results = $this->connection->api(array('method'=>'fql.multiquery','queries'=>$fql));
		$map = array();
		$events = array();
		$attendees = array();
		foreach ($results as $result)
			switch ($result['name']) {
				case 'map': $map = $result['fql_result_set']; break;
				case 'attendees': foreach ($result['fql_result_set'] as $row) $attendees[$row['uid']] = $row; break;
				case 'events': foreach ($result['fql_result_set'] as $row) $events[$row['eid']] = FacebookGateway::process_event($row); break;
			}
		foreach ($map as $m){
			$uid = $m['uid']; $eid = $m['eid'];
			if (!isset($events[$eid]['attendees'])) $events[$eid]['attendees'] = array();
			$events[$eid]['attendees'][] = $attendees[$uid];
		}
		$data = array();
		foreach ($events as $event)
			$data[] = $event;
		return $data;
	}
	
	public function calendar(){
		$di = getdate();
		$start_date = mktime(0, 0, 0, $di['mon'], $di['mday'] - $di['wday'], $di['year']);
		$end_date = $start_date + 60*60*24*7*4;
		$fql = <<<________FQL
			SELECT creator, description, eid, end_time, location, name, pic, pic_big, pic_small, start_time
			FROM event
			WHERE
				eid in (
					SELECT eid 
					FROM event_member 
					WHERE uid = 131130253626713 AND $start_date < start_time AND start_time < $end_date)
				AND privacy = 'OPEN'
			ORDER BY start_time ASC
________FQL;
		$result = $this->connection->api(array('method'=>'fql.query','query'=>$fql));
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
				'pic' => $row['pic'],
			);
		}
		return $data;
	}
	
	public function last_20_events(){
		$yesterday = time()-60*60*24;
		$fql = <<<________FQL
			SELECT creator, description, eid, end_time, location, name, pic, pic_big, pic_small, start_time
			FROM event
			WHERE
				eid in (
					SELECT eid 
					FROM event_member 
					WHERE uid = 131130253626713 AND 0 < start_time AND start_time < $yesterday)
				AND privacy = 'OPEN'
			ORDER BY start_time DESC
			LIMIT 20
________FQL;
		$result = $this->connection->api(array('method'=>'fql.query','query'=>$fql));
		$data = array();
		foreach ($result as $row)
			$data[] = FacebookGateway::process_event($row);
		return $data;
	}
	
	public function featured_events(){
		$yesterday = time()-60*60*24;
		$fql = <<<________FQL
			SELECT creator, description, eid, end_time, location, name, pic, pic_big, pic_small, start_time
			FROM event
			WHERE
				eid in (
					SELECT eid 
					FROM event_member 
					WHERE uid = 131130253626713 AND $yesterday < start_time)
				AND privacy = 'OPEN'
			ORDER BY start_time ASC
			LIMIT 1
________FQL;
		$result = $this->connection->api(array('method'=>'fql.query','query'=>$fql));
		$data = array();
		foreach ($result as $row)
			$data[] = FacebookGateway::process_event($row);
		return $data;
	}
	
	private static function process_event($row){
		$row['name'] = preg_replace('/^下北沢オープンソースCafe - /', '', $row['name']);
		$row['date'] = date('M j',$row['start_time']);
		$row['day'] = date('D',$row['start_time']);
		$row['description'] = mb_strimwidth($row['description'], 0, 400, '...', 'UTF-8');
		//$row['thumbnail'] = $row['picture'];
		//$row['picture'] = preg_replace('/_q\.(jpg)$/', '_n.$1', $row['picture']);
		return $row;
	}
}

function convertXmlObjToArr( $obj, &$arr ){
    $children = $obj->children();
    $executed = false;
    foreach ($children as $index => $node){
        if( array_key_exists( $index, (array) $arr ) ){
            if(array_key_exists( 0, $arr[$index] ) ){
                $i = count($arr[$index]);
                convertXmlObjToArr($node, $arr[$index][$i]);
            } else {
                $tmp = $arr[$index];
                $arr[$index] = array();
                $arr[$index][0] = $tmp;
                $i = count($arr[$index]);
                convertXmlObjToArr($node, $arr[$index][$i]);
            }
        } else {
            $arr[$index] = array();
            convertXmlObjToArr($node, $arr[$index]);
        } 
 
        $attributes = $node->attributes();
        if ( count($attributes) > 0 ) {
            $arr[$index]['@attributes'] = array();
            foreach ($attributes as $attr_name => $attr_value){
                $attr_index = strtolower(trim((string)$attr_name));
                $arr[$index]['@attributes'][$attr_index] = trim((string)$attr_value);
            }
        }
 
        $executed = true;
    }
    if(!$executed&&$children->getName()==""){
        $arr = (String)$obj;
    } 
 
    return;
}