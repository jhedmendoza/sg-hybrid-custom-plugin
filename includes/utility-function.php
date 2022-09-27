<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

// Globals.
global $__instances;

// Initialize placeholders.
$__instances = array();

function hybrid_new_instances( $class = '' ) {
	global $__instances;
	return $__instances[ $class ] = new $class();
}

function hybrid_get_path( $filename = '' ) {
	return HYBRID_PATH . ltrim($filename, '/');
}

function hybrid_include( $filename = '', $attributes='') {
	$file_path = hybrid_get_path($filename);
	if( file_exists($file_path) ) {
		include_once($file_path);
	}
}

function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}

function printr($data) {
	echo '<pre>';
		print_r($data);
	echo '</pre>';
	exit;
}
