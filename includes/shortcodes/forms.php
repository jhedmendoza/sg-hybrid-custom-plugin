<?php

add_shortcode('sg_login_form', 'shortcode_sg_login_form');
add_shortcode('sg_registration_form', 'shortcode_sg_registration_form');
add_shortcode('sg_auction_manager_form', 'shortcode_sg_auction_manager_form');

function shortcode_sg_login_form() {
	ob_start();
	hybrid_include('includes/template/login_form.php');
	return ob_get_clean();
}

function shortcode_sg_registration_form() {
	ob_start();
	hybrid_include('includes/template/registration_form.php');
	return ob_get_clean();
}

function shortcode_sg_auction_manager_form() {
	$action = filter_input(INPUT_GET, 'action');

	ob_start();

	if (isset($action) && $action == 'bidders') {
		sg_bidder_list();
	}
	else {
		sg_product_auction_list();
	}

	return ob_get_clean();
}
