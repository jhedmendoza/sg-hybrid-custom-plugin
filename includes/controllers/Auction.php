<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Auction extends Email {

    public function __construct() {
      add_action('wp_ajax_sg_user_bid', [$this, 'sg_user_bid']);
      add_action('wp_ajax_nopriv_sg_user_bid', [$this, 'sg_user_bid']);
      add_action('wp_ajax_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
      add_action('wp_ajax_nopriv_check_user_first_bid_attempt_status', [$this, 'check_user_first_bid_attempt_status']);
      add_action('wp_ajax_approve_user_auction', [$this, 'approve_user_auction']);
      add_action('wp_ajax_nopriv_approve_user_auction', [$this, 'approve_user_auction']);

      add_action( 'woocommerce_single_product_summary',[$this, 'bid_button_on_product_page'], 32 );
      add_action( 'woocommerce_before_single_product', [$this, 'override_yith_content'], 10 );

      add_filter('yith_wcact_add_bid', [$this, 'extend_auction_time']);
      add_filter('yith_wcact_after_auction_end', [$this, 'set_auction_status_to_finished']);

      add_action('template_redirect', [$this,  'set_auction']);
    }

    public function set_auction() {
        if (isset($_GET['dev'])) {

          // wp_set_object_terms(20579, 'finished', 'yith_wcact_auction_status', false );
          // wp_set_post_terms(18488, 'finished', 'yith_wcact_auction_status', false );

          // $terms = get_the_terms(18488, 'yith_wcact_auction_status');
          // printr($terms);

          // do_action( 'yith_wcact_email_new_bid', 11, 18630, []);

          // $saved_methods = wc_get_customer_saved_methods_list( get_current_user_id() );
          // $has_methods   = (bool) $saved_methods;
          // echo $has_methods; exit;

            // $current_date = date('Y-m-d H:i:s');
            // $product_id = 17930;
            // update_post_meta($product_id, '_yith_auction_for', strtotime($current_date) );
            // update_post_meta($product_id, '_yith_auction_to', strtotime('+15 minutes', strtotime($current_date)));
        }
    }

    public function approve_user_auction() {
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

          //send email to product bidders and watchlist
          $this->send_approved_bid_notif($user_id, $product_id, $bid_price);

          echo wp_json_encode([
            'status' => true,
            'msg'    => 'You successfully approve this user\'s bid',
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
      // else {
      //     $userStatus = $this->update_user_status($product_id, $user_id, 0);
      //     $delete = $this->delete_user_and_product_auction_data($product_id, $user_id);
      //
      //     if ($userStatus && $delete) {
      //       echo wp_json_encode([
      //         'status' => true,
      //         'msg'    => 'User successfully rejected',
      //       ]);
      //     }
      //     else {
      //       echo wp_json_encode([
      //         'status' => false,
      //         'msg'    => 'Something went wrong. Please try again later.',
      //       ]);
      //     }
      //     exit;
      //   }
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

      global $product;

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

        //send email to auction manager and bidder
        $this->send_initial_bid_notif($user_id, $product_id, $amount);

        echo wp_json_encode([
          'status' => true,
          'msg'    => "You successfuly bid to {$product_name}. Please wait for the seller to approve your bid. Thank you.",
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

        wp_set_object_terms($product_id, 'auction', 'product_type', false );
        wp_set_object_terms($product_id, 'started', 'yith_wcact_auction_status', false );

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

    public function set_auction_status_to_finished($product) {
      $product_id = $product->get_id();
      wp_set_object_terms($product_id, 'finished', 'yith_wcact_auction_status', false );
    }

    public function bid_button_on_product_page() {
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
            $btnMessage = 'You currently have no payment method associated with your account. Please <a href="'.site_url('my-account/payment-methods').'">Add Payment Method</a> to bid.';
          }
          else if ( !empty($check_user_bid) ) {
            $btnMessage = 'You already bid for this product. Wait for the seller to approve your bid.';
          }
        }
        else {
          $btnMessage = '';
          $disableBtn = '';
        }

        if ($product->get_type() != 'auction') {
            echo '<button '.$disableBtn.' type="button" data-product-id="'.$product_id.'"  style="display:none;" class="bid-btn single_add_to_cart_button button alt '.$disableBtn.'">Bid</button>';
            echo "<p class='err-msg'>$btnMessage</p>";
        }
    }

    public function override_yith_content() {
      $product =  wc_get_product();
      $product_id = $product->get_id();
      $user_id = get_current_user_id();
      $user_bidder = get_yith_bidders('yith_wcact_auction', $user_id, $product_id);
      if ( !empty($user_bidder) ) {
        echo '<style>.ywcact-add-to-watchlist-container, .yith-wcact-watchlist-button {display:none}</style>';
      }
    }

/********************** Email methods **********************************************************/
//TODO: separate this methods to a new class

    public function send_initial_bid_notif($user_id, $product_id, $bid) {
      $this->initial_bid_user_template($user_id, $product_id, $bid);
      $this->initial_bid_auction_manager_template($product_id, $bid);
    }

    public function send_approved_bid_notif($user_id, $product_id, $bid) {
      $watchlist = get_watchlist($product_id);
      $rejected_users = get_rejected_product_bidders($product_id);

      //send email to watchlist
      if ( isset($watchlist) && !empty($watchlist) ) {
        foreach ($watchlist as $wl) {
          $this->approved_bid_template($watchlist->user_id, $product_id, $bid);
        }
      }

      //send email to lost bidders
      foreach($rejected_users as $rejected) {
        $this->approved_bid_template($rejected->user_id, $product_id, $bid);
      }

    }

    public function initial_bid_user_template($user_id, $product_id, $bid) {
      $user = get_user_by('id', $user_id);
      $user_email = $user->user_email;
      $product = wc_get_product($product_id);
      $product_name = $product->get_title();

      $subject = "Scotch Galore Whiskies - The Alternative to Auctions - Successful initial bid";
      $headers  = '';
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html; charset=iso-8859-1\r\n";
      $headers .= "From: postmaster@mg.scotchgalore.com\r\n";

      $message.= "<p>Hi $user->user_login,</p>";
      $message.= "<p>You successfully created an initial bid of £$bid to the product <b>$product_name</b>. Please wait for the seller to approve your bid.</p>";

      mail($user_email, $subject, $message, $headers);
    }

    public function initial_bid_auction_manager_template( $product_id, $bid) {
      $product = wc_get_product($product_id);

      $manager_id = get_post_custom_values('shop_manager', $product_id)[0];
      $shop_manager = get_user_by('id', $manager_id);
      $shop_manager_email = $shop_manager->user_email;
      $product_name = $product->get_title();

      $subject = "Scotch Galore Whiskies - The Alternative to Auctions - New initial bid to a product";
      $headers = '';
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html;charset=utf-8\r\n";
      $headers .= "From: postmaster@mg.scotchgalore.com\r\n";

      $message.= "<p>Hi $shop_manager->user_login,</p>";
      $message.= "<p>There is a new initial bid of £$bid to the product <b>$product_name</b></p>";
      mail($shop_manager_email, $subject, $message, $headers);
    }

    public function approved_bid_template($user_id, $product_id, $bid) {

      $user = get_user_by('id', $user_id);
      $user_email = $user->user_email;
      $product = wc_get_product($product_id);
      $product_name = $product->get_title();

      $subject = "Scotch Galore Whiskies - The Alternative to Auctions - New bid to a product";
      $headers = '';
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html;charset=utf-8\r\n";
      $headers .= "From: postmaster@mg.scotchgalore.com\r\n";

      $message.= "<p>Hi $user->user_login,</p>";
      $message.= "<p>There is a new bid to the product <b>$product_name</b></p>";
      $message.= "<p>Current bid: £$bid</p>";
      mail($user_email, $subject, $message, $headers);
    }

    public function reset_product_bid_template($product_id) {

      $product = wc_get_product($product_id);

      $manager_id = get_post_custom_values('shop_manager', $product_id)[0];
      $shop_manager = get_user_by('id', $manager_id);
      $shop_manager_email = $shop_manager->user_email;
      $product_name = $product->get_title();

      $subject = "Scotch Galore Whiskies - The Alternative to Auctions - Bid reset to a product";
      $headers = '';
      $headers .= "MIME-Version: 1.0\r\n";
      $headers .= "Content-Type: text/html;charset=utf-8\r\n";
      $headers .= "From: postmaster@mg.scotchgalore.com\r\n";

      $message.= "<p>Hi $shop_manager->user_login,</p>";
      $message.= "<p>The bid for product <b>$product_name</b> has been reset due to the bidder didn\'t able to pay.</p>";
      mail($shop_manager_email, $subject, $message, $headers);
    }
}

$auction = new Auction();
?>
