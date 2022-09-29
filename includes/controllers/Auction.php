<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Auction {

    public function __construct() {
        add_action( 'woocommerce_single_product_summary',[$this,  'bid_button_on_product_page'], 32 );
        add_action('init', [$this,  'set_auction']);
    }

    public function set_auction() {
        if (isset($_GET['auction'])) {
            $this->set_auction_by_product(9999, '180.00');
        }
    }

    public function set_auction_by_product($product_id, $start_price) {

        $auction_config = include hybrid_get_path('includes/config/auction.php');

        foreach ($auction_config as $key => $value) {
            add_post_meta($product_id, $key, $value);
        }

        add_post_meta($product_id,'_yith_auction_start_price', $start_price);
        add_post_meta($product_id,'current_bid', $start_price);
        add_post_meta($product_id,'_yith_auction_for', '1661990400');
        add_post_meta($product_id,'_yith_auction_to', '1667174460');

    }

    function bid_button_on_product_page() {
        global $product;
        
        if ($product->get_type() != 'auction')
            echo '<button type="button" data-product-id="'.$product->get_id().'" style="display:none" class="bid-btn single_add_to_cart_button button alt">Bid</button>';
    }
}

$auction = new Auction();
?>
