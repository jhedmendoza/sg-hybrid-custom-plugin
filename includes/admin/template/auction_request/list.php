<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">

     <div class="col">
      <h2>Auction Requests</h2>
    </div>

    </div>

    <div class="row">

    <table class="table table-striped table-logo_manager">
        <thead>
          <tr>
            <th scope="col">Bidder's Name</th>
            <th scope="col">Product Name</th>
            <th scope="col">Bid Price</th>
            <th scope="col">Date Created</th>
            <th scope="col">Status</th>
          </tr>
        </thead>
        <tbody>
          <?php// if ( isset($attributes['logos'][0]['data']) ): ?>
            <?php// foreach($attributes['logos'] as $key => $value): ?>
            <tr>
              <td>John Doe</td>
              <td>MACALLAN SIR PETER BLAKE I.D. NO: 749</td>
              <td>£2999</td>
              <td><?php //echo date('Y/m/d H:i:s', strtotime($logo->date_created) ); ?></td>
              <td>
                <input type="checkbox" id="chk-status" data-toggle="toggle" data-size="sm" data-on="Approved" data-off="Rejected" />
              </td>
            </tr>
            <?php //endforeach; ?>
          <?php //else: ?>
            <tr><td colspan="5">No data available</td></tr>
          <?php// endif; ?>
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
          <?php// echo $attributes['pagination'] ?>
    </div>

  </div>
</div>
<?php hybrid_include('includes/admin/template/partials/notification.php'); ?>
