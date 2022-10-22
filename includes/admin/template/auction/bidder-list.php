<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">
      <a href="<?php echo admin_url('admin.php?page=sg-auction-products') ?>" class="ps-0 pb-3">Back to auction list</a>
    </div>

    <div class="row">
       <h5 class="mb-4 bg-secondary p-1"><a class="text-white text-decoration-none" href="<?php echo get_permalink($attributes['bidders'][0]['product_id']) ?>" target="_blank"><?php echo $attributes['bidders'][0]['product_name'] ?></a></h5>
    </div>

    <?php if (1): ?>
    <div class="running-bids">
      <div class="row">
        <h5 class="ps-0 pb-0">Running Bid</h5>
      </div>
      <div class="row">
        <table class="table table-striped table-logo_manager">
            <thead>
              <tr>
                <th scope="col">Username</th>
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
                  <td>£<?php echo $value['amount'] ?></td>
                  <td><?php echo $value['date']; ?></td>
                  <td>
                    <span class="badge bg-secondary"><?php echo ($value['status']) ? 'Ongoing bid' : '' ?></span>
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
        <table class="table table-striped table-logo_manager"><!--table-secondary-->
            <thead>
              <tr>
                <th scope="col">Username</th>
                <th scope="col">Bid Price</th>
                <th scope="col">Date Created</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if ( isset($attributes['bidders']) && !empty($attributes['bidders']) ): ?>
                <?php foreach($attributes['bidders'] as $key => $value): ?>
                <tr>
                  <td class="bidder-name"><?php echo $value['user_name']; ?></td>
                  <td>£<?php echo $value['amount'] ?></td>
                  <td><?php echo $value['date']; ?></td>
                  <td>
                    <span class="badge bg-secondary"><?php echo ($value['status']) ? 'Approved' : 'Pending' ?></span>
                  </td>
                  <td>
                    <button  data-bid-price="<?php echo $value['amount'] ?>" data-user-id="<?php echo $value['user_id'] ?>" data-product-id="<?php echo $value['product_id'] ?>" class="btn btn-primary btn-approve btn-sm">Approve</button>
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



    <div class="paginate">
          <?php echo $attributes['pagination'] ?>
    </div>

  </div>
</div>
<?php hybrid_include('includes/admin/template/partials/notification.php'); ?>
