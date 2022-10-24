<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AuctionRequests {

  public function get_all_user_auction_requests() {
    $product_id = $_GET['product_id'];
    $requests = get_product_bidders('sg_hybrid_user_bid', $product_id);
    return $requests;
  }

  public function get_all_auctioned_products() {
    $product_id = filter_input(INPUT_GET, 'product_id');
    $requests = get_all_data('sg_hybrid_user_bid', 'product_id');
    return $requests;
  }


}

$auctionRequest = new AuctionRequests();
?>
