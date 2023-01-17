<?php 
if ( !defined('ABSPATH') ) exit;  // Exit if accessed directly 

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

function delete_data($table_name, $where) {
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


	$query = "SELECT * FROM $table";

	$group_query = '';

	if ($group_by)
		$group_query = 'GROUP BY '.$group_by;



	$results = $wpdb->get_results($query . " $group_query ORDER BY date DESC");


	foreach ($results as $key => $result) {

		$shop_manager = get_post_meta($result->product_id, 'shop_manager', true);

		//show only the list of product that belongs to shop manager or if user type is an administrator
		if ($shop_manager == $current_user_id || in_array('administrator', $user_roles)) {
			$data['data'][$key] = $result;
			$data['data'][$key]->total_bidders = count_product_bidders($result->product_id);
		}

	}

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

function get_watchlist_by_user($user_id, $product_id) {
	global $wpdb;
	$table = $wpdb->prefix.'yith_wcact_watchlist';
	$query = "SELECT * FROM $table WHERE user_id = $user_id AND auction_id = $product_id";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_watchlist_by_user_id($user_id) {
	global $wpdb;
	$table = $wpdb->prefix.'yith_wcact_watchlist';
	$query = "SELECT * FROM $table WHERE user_id = $user_id";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_product_status($product_id) {

	global $wpdb;
	$product_auctioned_table = $wpdb->prefix.'wp_yith_wcact_auction';
	$user_has_paid = get_post_meta($product_id, '_yith_auction_paid_order', true);


	$terms = get_the_terms($product_id, 'yith_wcact_auction_status');
	$auction_status = $terms[0]->slug;

	if ($auction_status == 'started' || empty($auction_status) )
		return 'Ongoing Auction';
	else if ( strtolower($user_has_paid) == 'yes' )
		return 'Sold';
	else if ( strtolower($user_has_paid) == 'no' )
		return 'Awaiting Payment';

}

function get_bidder_status($product_id, $status) {

	$terms = get_the_terms($product_id, 'yith_wcact_auction_status');

	$auction_status = $terms[0]->slug;

	if ( $status == 1 && !empty($auction_status) ) {
		if ($auction_status == 'started') {
			return 'count_down_timer';
		}
	}
	else if ($status == 0 && empty($auction_status)) {
			return 'Pending';
	}
	return;
}

function get_all_bidders() {
	global $wpdb;
	$table = $wpdb->prefix.'yith_wcact_auction';
	$query = "SELECT * FROM $table GROUP BY auction_id";
	$result = $wpdb->get_results($query);
	return $result;
}

function get_assigned_products($user_id) {
	global $wpdb;
	
	$table = $wpdb->prefix.'postmeta';
	$query = "SELECT meta_id AS id, post_id AS product_id
 			  FROM $table
 			  WHERE meta_key = 'shop_manager'
 			  AND meta_value = $user_id
 			";

	$results = $wpdb->get_results($query);

	foreach ($results as $key => $result) 
	{
		$bidder_count = count_product_bidders($result->product_id);

		if ($bidder_count > 0)
		{
			unset($result->product_id);
			unset($result->id);
		}
	}

	$data['data'] = $results;
	return $data;
}
