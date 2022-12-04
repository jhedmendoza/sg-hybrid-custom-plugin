<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly
  
// Globals.
global $__instances;

// Initialize placeholders.
$__instances = array();

function hybrid_new_instances( $class = '' ) {
	global $__instances;
	return $__instances[ $class ] = new $class();
}

function hybrid_get_path( $filename = '' ) {
	return HYBRID_PATH . ltrim($filename, '/');
}

function hybrid_include( $filename = '', $attributes='') {
	$file_path = hybrid_get_path($filename);
	if( file_exists($file_path) ) {
		include_once($file_path);
	}
}

function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}

function printr($data) {
	echo '<pre>';
		print_r($data);
	echo '</pre>';
	exit;
}

function sg_product_auction_list() {

  $requests = new AuctionRequests();
  $data = $requests->get_all_auctioned_products();

  $current_user_id = get_current_user_id();
  $assigned_products = get_assigned_products($current_user_id); 

  if (isset($assigned_products) && !empty($assigned_products) ) {

    if ( isset($data['data']) && !empty($data['data']) ) {
      $data['data'] = array_merge($data['data'], $assigned_products['data']);
    }
    else {
      $data = $assigned_products;
    }
    
  }


  if ( isset($data['data']) && !empty($data['data']) ) {

    foreach($data['data'] as $value) {

      if ( count( (array)$value ) )
      {
        $product_id = $value->product_id;
        $user_id    = $value->user_id;

        $product = wc_get_product($product_id);
        $user = get_user_by('id', $user_id);

        $products[] = array(
          'product_id'     => $product_id,
          'product_name'   => $product->get_title(),
          'user_id'        => $user_id,
          'user_name'      => $user->data->display_name,
          'amount'         => number_format((float)$value->bid, 2, '.', ''),
          'status'         => $value->status,
          'total_bidders'  => isset($value->total_bidders) ? $value->total_bidders : 0,
          'date'           => $value->date,
          'product_status' => isset($value->total_bidders) ? get_product_status($value->product_id) : 'No bidders yet'
        );
      }

    }
  }

  $page = ! empty( $_GET['cpage'] ) ? (int) $_GET['cpage'] : 1;

  $total = count( $products ); //total items in array    

  $items_per_page = 5; //per page    

  $totalPages = ceil( $total/ $items_per_page ); //calculate total pages

  $page = max($page, 1); 
  $page = min($page, $totalPages); 

  $offset = ($page - 1) * $items_per_page;

  if( $offset < 0 ) $offset = 0;

  $data['pagination']['total'] = $total;
  $data['pagination']['page']  = $page;
  $data['pagination']['items_per_page'] = $items_per_page;


  $pagination = render_pagination($data);

  $products = array_slice( $products, $offset, $items_per_page );

  $result = [
    'products' => $products,
    'pagination'=> $pagination
  ];

  hybrid_include('includes/admin/template/auction/product-list.php', $result);
}


function sg_bidder_list() {
  $product_id = filter_input(INPUT_GET, 'product_id');

  $instance  = YITH_Auctions()->bids;
  $bids      = $instance->get_bids_auction($product_id);
  $max_bidder= $instance->get_max_bid( $product_id );

  $terms =  get_the_terms($product_id, 'yith_wcact_auction_status');
  $auction_status = $terms[0]->slug;

    foreach ($bids as $bid) {
      $product_id = $bid->auction_id;
      $user_id    = $bid->user_id;

      $product = wc_get_product($product_id);
      $user = get_user_by('id', $user_id);

      $bidders[] = array(
        'product_id'  => $product_id,
        'product_name'=> $product->get_title(),
        'user_id'     => $user_id,
        'user_name'   => $user->data->display_name,
        'amount'      => $bid->bid,
        'date'        => $bid->date,
        'status'      => ($max_bidder->user_id == $user_id && $auction_status == 'finished') ? 'Won' : '',
        'bidder_status' => get_bidder_status($product_id, true),
      );
    }


    $requests = new AuctionRequests();
    $data = $requests->get_all_user_auction_requests();

    if ( isset($data) && !empty($data) ) {

      foreach($data as $value) {

        $product_id = $value->product_id;
        $user_id    = $value->user_id;

        $product = wc_get_product($product_id);
        $user = get_user_by('id', $user_id);

        $initial_bidders[] = array(
          'product_id'  => $product_id,
          'product_name'=> $product->get_title(),
          'user_id'     => $user_id,
          'user_name'   => $user->data->display_name,
          'amount'      => number_format((float)$value->bid, 2, '.', ''),
          'status'      => $value->status,
          'bidder_status' => get_bidder_status($product_id, $value->status),
          'date'        => $value->date,
        );
      }
    }

  $result = [
    'bidders'         => $bidders,
    'initial_bidders' => $initial_bidders,
    'product_id'      => $product_id
  ];

  hybrid_include('includes/admin/template/auction/bidder-list.php', $result);
}


function render_pagination($data) {

  // printr($data);

  $pagination = paginate_links([
    'base'      => add_query_arg('cpage', '%#%'),
    'format'    => '',
    'prev_text' => __('&laquo;'),
    'next_text' => __('&raquo;'),
    'total'     => ceil($data['pagination']['total'] / $data['pagination']['items_per_page']),
    'current'   => $data['pagination']['page'],
  ]);
  return $pagination;
}
