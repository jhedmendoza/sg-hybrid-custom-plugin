<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Auction {

    public function __construct() {
      add_action('wp_ajax_sg_user_bid', [$this, 'sg_user_bid']);
      add_action('wp_ajax_nopriv_sg_user_bid', [$this, 'sg_user_bid']);
      add_action('wp_ajax_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
      add_action('wp_ajax_nopriv_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
      add_action('wp_ajax_approve_reject_user_auction', [$this, 'approve_reject_user_auction']);
      add_action('wp_ajax_nopriv_approve_reject_user_auction', [$this, 'approve_reject_user_auction']);

      add_action( 'woocommerce_single_product_summary',[$this, 'bid_button_on_product_page'], 32 );

      add_filter('yith_wcact_add_bid', [$this, 'extend_auction_time']);

      add_action('init', [$this,  'set_auction']);
    }

    public function set_auction() {
        if (isset($_GET['dev'])) {
          $saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
          $has_methods   = (bool) $saved_methods;
          echo $has_methods; exit;
            // $current_date = date('Y-m-d H:i:s');
            // $product_id = 17930;
            // update_post_meta($product_id, '_yith_auction_for', strtotime($current_date) );
            // update_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($current_date)));
        }
    }

    public function approve_reject_user_auction() {
      $product_id = sanitize_text_field($_POST['product_id']);
      $user_id    = sanitize_text_field($_POST['user_id']);
      $bid_price  = sanitize_text_field($_POST['bid_price']);
      $status     = sanitize_text_field($_POST['status']);

      //enable bid to product and user
      if ($status) {
        $this->enable_auction_to_product($product_id, $bid_price);
        $userStatus = $this->update_user_status($product_id, $user_id, 1);
        $auctionEnabledToUser = $this->enable_auction_to_user($user_id, $product_id, $bid_price);


        if ($auctionEnabledToUser && $userStatus) {
          echo wp_json_encode([
            'status' => true,
            'msg'    => 'User successfully bid to this product',
          ]);
        }
        else {
          echo wp_json_encode([
            'status' => false,
            'msg'    => 'Something went wrong. Please try again later.',
          ]);
        }
        exit;
      }
      else {
          $userStatus = $this->update_user_status($product_id, $user_id, 0);
          $delete = $this->delete_user_and_product_auction_data($product_id, $user_id);

          if ($userStatus && $delete) {
            echo wp_json_encode([
              'status' => true,
              'msg'    => 'User successfully rejected',
            ]);
          }
          else {
            echo wp_json_encode([
              'status' => false,
              'msg'    => 'Something went wrong. Please try again later.',
            ]);
          }
          exit;
        }
      }
    public function check_user_first_bid_attempt_status() {

      $product_id = sanitize_text_field($_POST['product_id']);
      $user_id    = get_current_user_id();
      $check_user_bid = get_user_auction('sg_hybrid_user_bid', $user_id, $product_id);

      if ( !empty($check_user_bid) ) {
        echo wp_json_encode([
          'status' => true,
          'msg'    => 'You already placed a bid on this product',
        ]);
      }
      exit;
    }

    public function sg_user_bid() {

      $amount     = sanitize_text_field($_POST['amount']);
      $product_id = sanitize_text_field($_POST['product_id']);
      $user_id    = get_current_user_id();
      $status     = 0;

      $save = insert_data('sg_hybrid_user_bid', [
        'product_id' => $product_id,
        'user_id'    => $user_id,
        'bid'        => $amount,
        'status'     => $status
      ]);

      if ($save) {
        $product = wc_get_product($product_id);
        $product_name = html_entity_decode($product->get_title(), ENT_COMPAT, 'UTF-8');

        echo wp_json_encode([
          'status' => true,
          'msg'    => "You successfuly bid to {$product_name}. Please wait for the administrator to approve your bid. Thank you.",
        ]);
      }
      else {
        echo wp_json_encode([
          'status' => false,
          'msg'    => 'Something went wrong. Please try again later.',
        ]);
      }
      exit;
    }

    public function enable_auction_to_product($product_id, $start_price) {

        $auction_config = include hybrid_get_path('includes/config/auction.php');
        $current_date = date('Y-m-d H:i:s');

        foreach ($auction_config as $key => $value) {
            add_post_meta($product_id, $key, $value);
        }

        add_post_meta($product_id, '_yith_auction_start_price', $start_price);
        add_post_meta($product_id, '_yith_auction_for', strtotime($current_date) );
        add_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($current_date)));

        add_post_meta($product_id, 'current_bid', $start_price);

        update_post_meta($product_id, '_price', $start_price); //we should update the base price of product to user's bid

        //we need to remove first the default product type and set it to `auction`
        // wp_remove_object_terms( $product_id, 'simple', 'product_type' );
        wp_set_object_terms( $product_id, 'auction', 'product_type', false );

    }

    public function remove_auction_to_product($product_id) {
      $auction_config = include hybrid_get_path('includes/config/auction.php');

      $regular_price = get_post_meta($product_id, '_regular_price');

      foreach ($auction_config as $key => $value) {
          delete_post_meta($product_id, $key);
      }

      delete_post_meta($product_id, '_yith_auction_start_price');
      delete_post_meta($product_id, '_yith_auction_for' );
      delete_post_meta($product_id, '_yith_auction_to');
      delete_post_meta($product_id, 'current_bid');

      update_post_meta($product_id, '_price', $regular_price[0]);
      wp_set_object_terms( $product_id, 'simple', 'product_type', false );
    }

    public function enable_auction_to_user($user_id, $auction_id, $bid_price) {
        $insert = insert_data('yith_wcact_auction', [
          'user_id'    => $user_id,
          'auction_id' => $auction_id,
          'bid'        => $bid_price
        ]);
        return $insert;
    }

    public function update_user_status($product_id, $user_id, $status) {

      $update = update_data('sg_hybrid_user_bid', $status, $product_id, $user_id);
      return $update;
    }

    public function delete_user_and_product_auction_data($product_id, $user_id) {
      $data = [
        'auction_id' => $product_id,
        'user_id'    => $user_id
      ];
      $format = ['%d', '%d'];

      //delete the auction meta data for a product
      $this->remove_auction_to_product($product_id);

      //delete the user auction data
      delete_data('yith_wcact_auction', $data, $format);
      return true;
    }

    public function extend_auction_time() {
      $date = date('Y-m-d H:i:s');
      $product_id = sanitize_text_field($_POST['product']);
      $bid  = sanitize_text_field($_POST['bid']);

      update_post_meta($product_id, '_price', $bid);
      update_post_meta($product_id, '_yith_auction_for', strtotime($date) );
      update_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($date)));
    }


    function bid_button_on_product_page() {
        global $product;
        $user_id    = get_current_user_id();
        $product_id = $product->get_id();
        $btnMessage = '';

        //check if user has payment method
        $saved_methods = wc_get_customer_saved_methods_list($user_id);
        $has_methods   = (bool) $saved_methods;

        if ( is_user_logged_in() ) {
          //check user if already bid on the product
          $check_user_bid = get_user_auction('sg_hybrid_user_bid', $user_id, $product_id);
          $disableBtn = ($has_methods && empty($check_user_bid) ) ? '' : 'disabled';

          if (!$has_methods) {
            $btnMessage = 'You need to add a valid credit card in order to bid. Please go to payment method in My Account';
          }
          else if ( !empty($check_user_bid) ) {
            $btnMessage = 'You already bid for this product. Wait for the auction manager to approve your bid.';
          }
        }
        else {
          $btnMessage = '';
          $disableBtn = '';
        }

        if ($product->get_type() != 'auction') {
            echo '<button title="'.$btnMessage.'" '.$disableBtn.' type="button" data-product-id="'.$product_id.'"  style="display:none" class="bid-btn single_add_to_cart_button button alt">Bid</button>';
        }
    }
}

$auction = new Auction();
?>
