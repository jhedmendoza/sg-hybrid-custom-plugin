<?php
$has_product_bid = get_post_meta($attributes['product_id'], 'yith_wcact_new_bid', true);
$terms =  get_the_terms($attributes['product_id'], 'yith_wcact_auction_status');
$auction_status = $terms[0]->slug;

if (isset($_GET['test-admin'])) {
  printr($terms);
}


?>


<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">
      <a href="<?php echo admin_url('admin.php?page=sg-auction-products') ?>" class="ps-0 pb-3">Back to auction list</a>
    </div>

    <div class="row">
       <h5 class="mb-4 bg-secondary p-1"><a class="text-white text-decoration-none" href="<?php echo get_permalink($attributes['product_id']) ?>" target="_blank"><?php echo $attributes['bidders'][0]['product_name'] ?></a></h5>
    </div>

    <?php if ($has_product_bid): ?>
    <div class="running-bids">
      <div class="row">
        <h5 class="ps-0 pb-0"><?php echo $auction_status == 'finished' ? 'Offer' : 'Running Bids' ?></h5>
      </div>
      <div class="row">
        <table class="table table-striped table-logo_manager">
            <thead>
              <tr>
                <th scope="col">Username</th>
                <th scope="col">User ID</th>
                <th scope="col">Bid Price</th>
                <th scope="col">Date Created</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if ( isset($attributes['bidders']) && !empty($attributes['bidders']) ): ?>
                <?php foreach($attributes['bidders'] as $key => $value): ?>
                <tr>
                  <td class="bidder-name"><?php echo $value['user_name']; ?></td>
                  <td><?php echo $value['user_id']; ?></td>
                  <td>£<?php echo $value['amount'] ?></td>
                  <td><?php echo $value['date']; ?></td>
                  <td>
                    <span class="badge bg-secondary"><?php echo $value['bidder_status'] ?></span>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td class="text-center" colspan="6">No data available</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div class="preloader-container text-center">
            <div class="lds-facebook preloader" style="display:none">
              <div></div>
              <div></div>
              <div></div>
            </div>
          </div>
      </div>
    </div>
  <?php endif; ?>

    <div class="initial-bids mt-3">
      <div class="row">
        <h5 class="ps-0 pb-0">Initial bid</h5>
      </div>
      <div class="row">
        <table class="table table-striped table-logo_manager <?php echo $has_product_bid ? 'table-secondary' : '' ?>">
            <thead>
              <tr>
                <th scope="col">Username</th>
                <th scope="col">User ID</th>
                <th scope="col">Bid Price</th>
                <th scope="col">Date Created</th>
                <th scope="col">Status</th>
                <?php if (!$has_product_bid): ?>
                  <th scope="col">Action</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php if ( isset($attributes['bidders']) && !empty($attributes['bidders']) ): ?>
                <?php foreach($attributes['bidders'] as $key => $value): ?>
                <tr>
                  <td class="bidder-name"><?php echo $value['user_name']; ?></td>
                  <td><?php echo $value['user_id']; ?></td>
                  <td>£<?php echo $value['amount'] ?></td>
                  <td><?php echo $value['date']; ?></td>
                  <td>
                    <span class="badge bg-secondary"><?php echo ($has_product_bid && $value) ? 'Approved' : '' ?></span>
                  </td>

                  <?php if (!$has_product_bid): ?>
                  <td>
                    <button data-bid-price="<?php echo $value['amount'] ?>" data-user-id="<?php echo $value['user_id'] ?>" data-product-id="<?php echo $value['product_id'] ?>" class="btn btn-primary btn-approve btn-sm">Approve</button>
                  </td>
                  <?php endif; ?>

                </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td class="text-center" colspan="6">No data available</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
          <div class="preloader-container text-center">
            <div class="lds-facebook preloader" style="display:none">
              <div></div>
              <div></div>
              <div></div>
            </div>
          </div>
      </div>
    </div>



    <div class="paginate">
          <?php echo $attributes['pagination'] ?>
    </div>

  </div>
</div>
<?php hybrid_include('includes/admin/template/partials/notification.php'); ?>
