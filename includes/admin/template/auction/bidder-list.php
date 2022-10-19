<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">
      <a href="<?php echo admin_url('admin.php?page=sg-auction-products') ?>" class="">Back to auction list</a>
    </div>

    <div class="row">
       <h5 class="mb-4 bg-secondary p-1 text-white">Bidders on Ardbeg Fermutation I.D. No: 2193</h5>
    </div>

    <div class="row">

    <table class="table table-striped table-logo_manager">
        <thead>
          <tr>
            <th scope="col">Username</th>
            <th scope="col">Product Name</th>
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
              <td><?php echo $value['user_name']; ?></td>
              <td><a class="product-name" target="_blank" href="<?php echo get_permalink($value['product_id']) ?>"><?php echo $value['product_name']; ?></a></td>
              <td>£<?php echo $value['amount'] ?></td>
              <td><?php echo $value['date']; ?></td>
              <td>
                <span data-bid-price="<?php echo $value['amount'] ?>" data-user-id="<?php echo $value['user_id'] ?>" data-product-id="<?php echo $value['product_id'] ?>" class="badge bg-secondary">Pending</span>
              </td>
              <td>
                <button class="btn btn-primary btn-approve btn-sm">Approve</button>
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
