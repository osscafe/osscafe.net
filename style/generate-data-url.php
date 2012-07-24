<?php
$original = '_images.css';
$dest = 'images.css';

$css = file_get_contents($original);
$css = preg_replace_callback('/url\((.*?)\)/im', function($matches){
	$url = trim($matches[1], '\'\"');
	return file_exists($url)
		? 'url(data:image/png;base64,' . base64_encode(file_get_contents($url)) . ')'
		: '';
}, $css);
file_put_contents($dest, $css);