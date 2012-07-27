<?php
$mime_types = array('png'=>'image/png', 'jpg'=>'image/jpeg', 'gif'=>'image/gif');
header('Content-type: text/css');
echo preg_replace_callback(
	'/url\((.*?\.(' . implode('|', array_keys($mime_types)) . '))\)/im',
	function ($matches) use ($mime_types) {
		$url = trim($matches[1], '\'\"');
		return file_exists($url)
			? 'url(data:' . $mime_types[strtolower($matches[2])] . ';base64,' . base64_encode(file_get_contents($url)) . ')'
			: $matches[0];
	},
	file_get_contents($_SERVER['DOCUMENT_ROOT'] . str_replace('?inline', '', $_SERVER['REQUEST_URI']))
);
