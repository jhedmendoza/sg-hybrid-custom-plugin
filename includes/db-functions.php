<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

function insert_data($table_name, $data) {
		global $wpdb;

		$id = $wpdb->insert($wpdb->prefix.$table_name, $data);

		if ($id)
			return $wpdb->insert_id;
		else
			return 0;
}

function get_all_data($table_name) {

	global $wpdb;

	$table = $wpdb->prefix.$table_name;

	$items_per_page = 10;
	$total = $wpdb->get_var("SELECT COUNT(1) FROM $table");
	$page = isset($_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;

	$query = "SELECT * FROM $table";
	$results = $wpdb->get_results($query. " ORDER BY date DESC LIMIT ${offset}, ${items_per_page}");

	foreach ($results as $key => $result) {
			$data['data'][$key] = $result;
	}

	$data['pagination']['total'] = $total;
	$data['pagination']['page']  = $page;
	$data['pagination']['items_per_page'] = $items_per_page;

	return $data;
}

function get_user_auction($table_name, $user_id, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.$table_name;
	$query = "SELECT * FROM $table WHERE user_id = $user_id AND product_id = $product_id";
	$result = $wpdb->get_row($query);
	return $result;
}
