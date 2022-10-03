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

function get_user_auction($table_name, $user_id, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.$table_name;
	$query = "SELECT * FROM $table WHERE user_id = $user_id AND product_id = $product_id";
	$result = $wpdb->get_row($query);
	return $result;
}
