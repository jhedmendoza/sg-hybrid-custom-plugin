<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AuctionRequests {

  public function get_all_user_auction_requests() {
    $requests = get_all_data('sg_hybrid_user_bid');
    return $requests;
  }

}

$auctionRequest = new AuctionRequests();
?>
