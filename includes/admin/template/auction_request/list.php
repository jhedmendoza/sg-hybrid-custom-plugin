<div class="wrapper fc-wrapper">
  <div class="container mt-3">

    <div class="row">
      
     <div class="col">
      <h2>All Logos</h2>
    </div> 
      <div class="col text-end">
          <a href="<?php echo admin_url('admin.php?page=fc-logo') ?>" class="btn btn-primary">Add Logo</a>
      </div>

    </div>

    <div class="row">
      <div class="input-group mb-3 mt-3">
        <input type="text" class="form-control" placeholder="Enter known keywords, e.g part of a company name or sport" id="search-text-input">
      
        <button class="btn btn-outline-secondary dropdown-toggle btn-logo-types" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-event-type-id="0">All Logo Types</button>
        <ul class="dropdown-menu choose-logo-type">
          <li><a class="dropdown-item" href="#" data-event-type-id="0">All Logo Types</a></li>
          <li><a class="dropdown-item" href="#" data-event-type-id="1">Team/Clubs</a></li>
          <li><a class="dropdown-item" href="#" data-event-type-id="2">Events</a></li>
          <li><a class="dropdown-item" href="#" data-event-type-id="3">Organisations</a></li>
        </ul>

        <button class="btn btn-success" type="button" id="btn-search-logo">Search</button>
      </div>
    </div>

    <div class="row">

    <table class="table table-striped table-logo_manager">
        <thead>
          <tr>
            <th style="width:15%" scope="col">Logo</th>
            <th scope="col">Organisation</th>
            <th scope="col">Logo Type</th>
            <th scope="col">Date Created</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ( isset($attributes['logos'][0]['data']) ): ?> 
            <?php foreach($attributes['logos'] as $key => $value): ?>
              <?php

                $logo = $value['data'];
                $details = $value['details'];

                if ($logo->entry_type_id == 1) {
                  $organisation = $details->club_name; 
                  $logo_type = 'Team/Club';
                } 
                else if ($logo->entry_type_id == 2) {
                  $organisation = $details->event_name;
                  $logo_type = 'Event';
                } 
                else if ($logo->entry_type_id == 3) {
                  $organisation = $details->organisation_name; 
                  $logo_type = 'Organisation'; 
                }
              ?>
            <tr>
              <td><img src="<?php echo $logo->logo_url ?>" class="img-thumbnail" alt="<?php echo $organisation ?>" width="100"></td>
              <td>
                <p class="club-name">
                  <?php echo $organisation ?>
                </p>
              <td>
                <p><?php echo $logo_type ?></p>
              </td>  
              </td>
              <td><?php echo date('Y/m/d H:i:s', strtotime($logo->date_created) ); ?></td>
              <td>
                <a href="<?php echo admin_url('admin.php?page=fc-logo&action=edit&id='.$logo->id) ?>" class="btn btn-info btn-action me-2"><i class="fas fa-edit"></i></a>
                <a data-logo-id="<?php echo $logo->id ?>" href="#" class="btn btn-warning btn-action btn-delete"><i class="fa-solid fa-trash-can"></i></a>
              </td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="4">No data available</td></tr>
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