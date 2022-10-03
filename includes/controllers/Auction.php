<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Auction {

    public function __construct() {
      add_action( 'woocommerce_single_product_summary',[$this, 'bid_button_on_product_page'], 32 );

      add_action('wp_ajax_sg_user_bid', [$this, 'sg_user_bid']);
      add_action('wp_ajax_nopriv_sg_user_bid', [$this, 'sg_user_bid']);

      add_action('init', [$this,  'set_auction']);
      add_action('wp_ajax_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
      add_action('wp_ajax_nopriv_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
    }

    public function set_auction() {
        if (isset($_GET['dev'])) {
            $current_date = date('Y-m-d H:i:s');
            $product_id = 17930;
            update_post_meta($product_id, '_yith_auction_for', strtotime($current_date) );
            update_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($current_date)));
        }
    }

    public function check_user_first_bid_attempt_status() {

      $product_id = sanitize_text_field($_POST['product_id']);
      $user_id    = get_current_user_id();
      $check_user_bid = get_user_auction('sg_hybrid_user_bid', $user_id, $product_id);

      if ( !empty($check_user_bid) ) {
        echo wp_json_encode([
          'status' => true,
          'msg'    => "User already pre-bid on this product",
        ]);
      }
      exit;


    }

    public function sg_user_bid() {

      $amount     = sanitize_text_field($_POST['amount']);
      $product_id = sanitize_text_field($_POST['product_id']);
      $user_id    = get_current_user_id();
      $status     = 0;

      //save bidder's product
      $save = insert_data('sg_hybrid_user_bid', [
        'product_id' => $product_id,
        'user_id'    => $user_id,
        'bid'        => $amount,
        'status'     => $status
      ]);

      if ($save) {
        $product = wc_get_product($product_id);
        $product_name = html_entity_decode($product->get_title(),ENT_COMPAT, 'UTF-8');

        echo wp_json_encode([
          'status' => true,
          'msg'    => "You successfuly bid to {$product_name}. Please wait for the administrator to approve your bid. Thank you.",
        ]);
      }
      else {
        echo wp_json_encode([
          'status' => false,
          'msg'    => "Something went wrong. Please try again later.",
        ]);
      }
      exit;
    }

    public function enable_auction_to_product($product_id, $start_price) {

        //TODO: Update bid time to every 15 mins when there is succeeding bid.

        $auction_config = include hybrid_get_path('includes/config/auction.php');
        $current_date = date('Y-m-d H:i:s');

        foreach ($auction_config as $key => $value) {
            add_post_meta($product_id, $key, $value);
        }

        add_post_meta($product_id, '_yith_auction_start_price', $start_price);
        add_post_meta($product_id, '_price', $start_price);
        add_post_meta($product_id, 'current_bid', $start_price);

        add_post_meta($product_id, '_yith_auction_for', strtotime($current_date) );
        add_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($current_date)));

        //we need to remove first the default product type and set it to `auction`
        wp_remove_object_terms( $product_id, 'simple', 'product_type' );
        wp_set_object_terms( $product_id, 'auction', 'product_type', true );

    }

    public function enable_auction_to_user() {
        //wp_yith_wcact_auction table
    }

    function bid_button_on_product_page() {
        global $product;

        if ($product->get_type() != 'auction')
            echo '<button type="button" data-product-id="'.$product->get_id().'" style="display:none" class="bid-btn single_add_to_cart_button button alt">Bid</button>';
    }

}

$auction = new Auction();
?>
