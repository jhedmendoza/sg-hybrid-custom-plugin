<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Auction {

    public function __construct() {
        add_action( 'woocommerce_single_product_summary',[$this,  'bid_button_on_product_page'], 32 );
    }

    function bid_button_on_product_page() {
        global $product;
        
        if ($product->get_type() != 'auction')
            echo '<button type="button" data-product-id="'.$product->get_id().'" style="display:none" class="bid-btn single_add_to_cart_button button alt">Bid</button>';
    }
}

$auction = new Auction();
?>
