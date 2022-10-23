<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">
      <h4 class="mb-4 bg-secondary p-1 text-white">Auction Manager</h4>
    </div>

    <div class="row">

    <table class="table table-striped table-logo_manager">
        <thead>
          <tr>
            <th scope="col">Product Name</th>
            <th scope="col">Product ID</th>
            <th scope="col">Price</th>
            <th scope="col">Bid Count</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ( isset($attributes['products']) && !empty($attributes['products']) ): ?>
            <?php foreach($attributes['products'] as $key => $value): ?>
            <tr>
              <td><a class="product-name" target="_blank" href="<?php echo get_permalink($value['product_id']) ?>"><?php echo $value['product_name']; ?></a></td>
              <td><?php echo $value['product_id'] ?></td>
              <td>£<?php echo $value['amount'] ?></td>
              <td><span class="badge bg-danger"><?php echo $value['total_bidders'] ?></span></td>
              <td>
                <span data-bid-price="<?php echo $value['amount'] ?>" data-user-id="<?php echo $value['user_id'] ?>" data-product-id="<?php echo $value['product_id'] ?>" class="badge bg-secondary"><?php echo $value['product_status'] ?></span>
              </td>
              <td>
                <a href="<?php echo admin_url('admin.php?page=sg-bidder-list&action=bidders&product_id='.$value['product_id']) ?>" class="btn btn-primary btn-sm">View</a>
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

    <div class="paginate">

          <?php echo $attributes['pagination'] ?>
    </div>

  </div>
</div>
<?php hybrid_include('includes/admin/template/partials/notification.php'); ?>
