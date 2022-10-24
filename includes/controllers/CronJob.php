<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class CronJob {

    public function __construct() {
       add_action('template_redirect', [$this, 'reset_bidders_to_auctioned_products']);
    }

    //check if users did not pay for the product after 24 hours
    public function reset_bidders_to_auctioned_products() {

      if ( isset($_GET['action']) && $_GET['action'] == 'update_bidders' &&
          isset($_GET['sg_cron']) && $_GET['sg_cron'] == 1 ) {

        $auction    = new Auction();
        $instance   = YITH_Auctions()->bids;
        $bidders    = get_all_bidders();

        foreach ($bidders as $bidder) {

          $product_id = $bidder->auction_id;
          $user_has_paid = get_post_meta($product_id, '_yith_auction_paid_order', true);
          $current_date = date('Y-m-d H:i:s');
          $timediff = strtotime($current_date) - strtotime($bidder->date);

          if ($user_has_paid == 'no') {

            //user who won the bid but didn`t pay
            $max_bidder = $instance->get_max_bid( $product_id );

            //check if 24 hours has passed
            if ($timediff > 86400) {
              $auction->reset_product_bid_template($max_bidder->auction_id);
              $auction->remove_auction_to_product($max_bidder->auction_id);
              $instance->remove_customer_bids( $max_bidder->user_id, $max_bidder->auction_id );
            }
          }
        }
      }
    }
}
$cron = new CronJob();
?>
