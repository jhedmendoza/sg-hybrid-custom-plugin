<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">

     <div class="col">
      <h4 class="mb-4">Auction Requests</h4>
    </div>

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
          </tr>
        </thead>
        <tbody>
          <?php if ( isset($attributes['users']) && !empty($attributes['users']) ): ?>
            <?php foreach($attributes['users'] as $key => $value): ?>
            <tr>
              <td><?php echo $value['user_name']; ?></td>
              <td><?php echo $value['product_name']; ?></td>
              <td>£<?php echo $value['amount'] ?></td>
              <td><?php echo $value['date']; ?></td>
              <td>
                <input <?php echo $value['status'] ? 'checked' : '' ?> type="checkbox" data-bid-price="<?php echo $value['amount'] ?>" data-user-id="<?php echo $value['user_id'] ?>" data-product-id="<?php echo $value['product_id'] ?>" id="chk-status" data-toggle="toggle" data-size="sm" data-on="Approved" data-off="Rejected" />
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">No data available</td></tr>
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
