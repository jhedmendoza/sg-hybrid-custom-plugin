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

 function update_data($table_name, $status, $product_id, $user_id) {
		global $wpdb;

		$data = ['status' => $status];
		$where = ['product_id' => $product_id, 'user_id' => $user_id];

		$update = $wpdb->update($wpdb->prefix.$table_name, $data, $where);

		if ($update > 0) {
				return true;
		}
		else if ($update === false) {
			return false;
		}
}

function delete_data($table_name, $where, $format) {
	global $wpdb;
  return $wpdb->delete($wpdb->prefix.$table_name, $where);
}

function get_all_data($table_name, $group_by='') {

	global $wpdb;
	global $product;

	$table = $wpdb->prefix.$table_name;

	$current_user_id = get_current_user_id();
	$user_meta = get_userdata($current_user_id);
	$user_roles = $user_meta->roles;

	$items_per_page = 10;

	$total = $wpdb->get_var("SELECT COUNT(1) FROM $table");

	$page = isset($_GET['cpage'] ) ? abs( (int) $_GET['cpage'] ) : 1;
	$offset = ( $page * $items_per_page ) - $items_per_page;

	$query = "SELECT * FROM $table";

	$group_query = '';

	if ($group_by)
		$group_query = 'GROUP BY '.$group_by;

	$results = $wpdb->get_results($query . " $group_query ORDER BY date DESC LIMIT ${offset}, ${items_per_page}");


	foreach ($results as $key => $result) {

			$shop_manager = get_post_meta($result->product_id, 'shop_manager', true);

			//show only the list of product that belongs to shop manager or if user type is an administrator
			if ($shop_manager == $current_user_id || in_array('administrator', $user_roles)) {
				$data['data'][$key] = $result;
				$data['data'][$key]->total_bidders = count_product_bidders($result->product_id);
			}

	}

	$data['pagination']['total'] = $total;
	$data['pagination']['page']  = $page;
	$data['pagination']['items_per_page'] = $items_per_page;

	return $data;
}

function count_product_bidders($product_id) {
	global $wpdb;
	$table = $wpdb->prefix.'sg_hybrid_user_bid';
	$total = $wpdb->get_var("SELECT COUNT(*) AS total_bidders FROM $table WHERE product_id = $product_id GROUP BY product_id");
	return $total;
}

function get_user_auction($table_name, $user_id, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.$table_name;
	$query = "SELECT * FROM $table WHERE user_id = $user_id AND product_id = $product_id";
	$result = $wpdb->get_row($query);
	return $result;
}

function get_yith_bidders($table_name, $user_id, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.$table_name;
	$query = "SELECT * FROM $table WHERE user_id = $user_id AND auction_id = $product_id";
	$result = $wpdb->get_row($query);
	return $result;
}

function get_product_bidders($table_name, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.$table_name;
	$query = "SELECT * FROM $table WHERE product_id = $product_id";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_rejected_product_bidders($product_id) {
	global $wpdb;
	$table = $wpdb->prefix.'sg_hybrid_user_bid';
	$query = "SELECT * FROM $table WHERE product_id = $product_id AND status = 0";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_watchlist($product_id) {
	global $wpdb;
	$table = $wpdb->prefix.'yith_wcact_watchlist';
	$query = "SELECT * FROM $table WHERE auction_id = $product_id";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_product_status($product_id) {

	global $wpdb;
	$product_auctioned_table = $wpdb->prefix.'wp_yith_wcact_auction';
	$user_has_paid = get_post_meta($product_id, '_yith_auction_paid_order', true);

	if ( empty($user_has_paid) )
		return 'Ongoing Auction';

	if ( strtolower($user_has_paid) == 'yes' )
		return 'Sold';

	if ( strtolower($user_has_paid) == 'no' )
		return 'Awaiting Payment';

}

function get_bidder_status($product_id, $status) {

	$terms =  get_the_terms($product_id, 'yith_wcact_auction_status');

	$auction_status = $terms[0]->slug;

	if ( $status == 1 && !empty($auction_status) ) {
		switch ($auction_status) {
			case 'started':
				return '15 mins counting down';
			break;
		}
	}
	else if ($status == 0 && empty($auction_status)) {
			return 'Pending';
	}
	else {
		return '';
	}

}

function get_all_bidders() {
	global $wpdb;
	$table = $wpdb->prefix.'yith_wcact_auction';
	$query = "SELECT * FROM $table GROUP BY auction_id";
	$result = $wpdb->get_results($query);
	return $result;
}
