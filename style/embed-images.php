<?php
$file = $_SERVER['DOCUMENT_ROOT'] . str_replace('?inline', '', $_SERVER['REQUEST_URI']);
$css = file_get_contents($file);
$css = preg_replace_callback('/url\((.*?)\)/im', function($matches){
	$url = trim($matches[1], '\'\"');
	return file_exists($url)
		? 'url(data:image/png;base64,' . base64_encode(file_get_contents($url)) . ')'
		: '';
}, $css);

header("Content-type: text/css");
echo $css;