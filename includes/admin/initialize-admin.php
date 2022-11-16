<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'initialize_sg_auction_requests_menu' );

function initialize_sg_auction_requests_menu() {

  $title = 'Auction Manager';
  $slug  = 'auctions';
  $icon  = 'dashicons-open-folder';
  $position = 50;

  add_menu_page( $title, $title, 'manage_options', $slug, '', $icon, $position );

  $auction_request_menu = add_submenu_page( $slug, 'Product Auction', 'Product Auction', 'manage_options', 'sg-auction-products', 'sg_product_auction_list');
  $auction_bidders_menu = add_submenu_page( 'bidder-list', 'Bidder List', 'Bidder List', 'manage_options', 'sg-bidder-list', 'sg_bidder_list');

  remove_submenu_page($slug, $slug);

  add_action('load-'.$auction_request_menu, 'load_admin_css_js');
  add_action('load-'.$auction_bidders_menu, 'load_admin_css_js');

}

function load_admin_css_js() {
  add_action( 'admin_enqueue_scripts', 'enqueue_admin_css_js');
}

function enqueue_admin_css_js() {

  $admin_version_script = '1';

  //Core media script
  wp_enqueue_media();

  wp_enqueue_style('bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css', [], '5.2.0');
  wp_enqueue_style('bootstrap-toggle', 'https://cdn.jsdelivr.net/npm/bootstrap5-toggle@4.3.2/css/bootstrap5-toggle.min.css', [], '4.3.2');

  wp_enqueue_style('fontawesome-free', HYBRID_DIR_URL.'includes/admin/assets/lib/fontawesome-free/css/all.min.css', [], '5.15.1');
  wp_enqueue_style('sg-admin-custom', HYBRID_DIR_URL.'includes/admin/assets/css/sg-admin-custom.css', [], $admin_version_script);

  wp_enqueue_script('kit-fontawesome','https://kit.fontawesome.com/ee83b0058f.js', [], '2.11.5', true );
  wp_enqueue_script('popper','https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js', [], '2.11.5', true );
  wp_enqueue_script('bootstrap-admin','https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js', ['popper'], '5.2.0', true );
  wp_enqueue_script('swal-alert-admin','https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js', ['jquery'], '10.15.5', true);

  wp_enqueue_script('moment-min', HYBRID_DIR_URL.'includes/admin/assets/lib/moment.min.js', [], null, true);
  wp_enqueue_script('countdown-min', HYBRID_DIR_URL.'includes/admin/assets/lib/countdown.min.js', [], '2.6.1', true);
  wp_enqueue_script('moment-countdown', HYBRID_DIR_URL.'includes/admin/assets/lib/moment-countdown.min.js', [], null, true);

  wp_enqueue_script('sg-admin-custom', HYBRID_DIR_URL . 'includes/admin/assets/js/sg-admin-custom.js', ['jquery'], $admin_version_script, true );
}
