<?php

add_shortcode('sg_login_form', 'shortcode_sg_login_form');
add_shortcode('sg_registration_form', 'shortcode_sg_registration_form');

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