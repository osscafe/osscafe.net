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
			case 'featured_events':
			case 'last_20_events':
			case 'books': $data = $this->$type(); break;
		}
		$json = json_encode($data);
		//file_put_contents($file, $json);
		header('Cache-Control: public,max-age=3600');
		return $json;
	}
	
	public function books(){
		$url = 'http://librize.com/places/3/feed.atom';
		$max = 5;
		$atom = simplexml_load_file($url);
		$books = array();
		$n = 0;
		foreach($atom->entry as $item){
			$attr = $item->link[0]->attributes();
			$img = $item->link[1]->attributes();
			$books[] = array(
				'title' => (string)$item->title,
				'url' => (string)$attr['href'],
				'date' => (string)$item ->published,
				'image' => preg_replace('|\._SL\d+_\.|', '._SL180_.', (string)$img['href']),
			);
			$n++;
			if ($max <= $n)
				break;
		}
		return $books;
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