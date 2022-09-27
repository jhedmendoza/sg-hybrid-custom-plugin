<?php
$logo = $attributes['logo']['data'];
$details = $attributes['logo']['details'];

$default_logo_url = HYBRID_DIR_URL.'includes/admin/assets/images/placeholder.png';
$logo_url = isset($logo->logo_url) && !empty($logo->logo_url) ? $logo->logo_url : $default_logo_url;
$entry_type_id = isset($logo->entry_type_id) && !empty($logo->entry_type_id) ? $logo->entry_type_id : '';

$club_name     = isset($details->club_name) && !empty($details->club_name) ? $details->club_name : '';
$event_name    = isset($details->event_name) && !empty($details->event_name) ? $details->event_name : '';
$event_country = isset($details->event_country) && !empty($details->event_country) ? $details->event_country : '';

$organisation_name = isset($details->organisation_name) && !empty($details->organisation_name) ? $details->organisation_name : '';
$organisation_city = isset($details->city) && !empty($details->city) ? $details->city : '';
$organisation_country = isset($details->country) && !empty($details->country) ? $details->country : '';

if ($entry_type_id == 1) 
  $organisation = $details->club_name; 
else if ($entry_type_id == 2) 
  $organisation = $details->event_name;
else if ($entry_type_id == 3) 
  $organisation = $details->organisation_name;  

?>
<div class="wrapper fc-wrapper">
  <div class="container mt-3">
    <div class="col">

      <h3><?php echo ($attributes['logo_id'] > 0) ? 'Update' : 'Add' ?> Logo</h3>

      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="<?php echo admin_url('admin.php?page=fc-logo-list') ?>">Logos</a></li>
          <li class="breadcrumb-item active" aria-current="page"><?php echo ($attributes['logo_id'] > 0) ? $organisation : 'Add new logo  ' ?></li>
        </ol>
      </nav>

      <div class="card card-logo_manager p-0 mx-auto ">
        <div class="card-header card-default-color">
            <h5>Logo Details</h5>
        </div>
        <div class="card-body">

          <form id="logo-manager-form">

            <div class="mb-4">
                <img src="<?php echo $logo_url ?>" class="img-thumbnail logo-thumbnail" width="200" alt="">
                <button class="btn btn-outline-info btn-sm upload-image"><?php echo ($attributes['logo_id'] > 0) ? 'Update' : 'Add' ?> logo</button>
            </div>

            <div class="mb-4">
              <label for="entry_type" class="form-label">Entry Type: <span class="required">*</span></label>
              <br/>
   
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="entry_type" id="entry_type_team" value="1" <?php echo ($entry_type_id == 1) || $attributes['logo_id'] == 0 ? 'checked' : '' ?> <?php echo ($entry_type_id != 1 && $attributes['logo_id'] > 0) ? 'disabled' : '' ?> >
                  <label class="form-check-label" for="entry_type_team">Team/Club</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="entry_type" id="entry_type_event" value="2" <?php echo ($entry_type_id == 2) ? 'checked' : '' ?> <?php echo ($entry_type_id != 2 && $attributes['logo_id'] > 0) ? 'disabled' : '' ?>>
                  <label class="form-check-label" for="entry_type_event">Event</label>
                </div>

                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="entry_type" id="entry_type_organization" value="3"  <?php echo ($entry_type_id == 3) ? 'checked' : '' ?> <?php echo ($entry_type_id != 3 && $attributes['logo_id'] > 0) ? 'disabled' : '' ?>>
                  <label class="form-check-label" for="entry_type_organization">Organisation</label>
                </div>
           
            </div>

            <div class="team_club-wrapper" style="display: <?php echo $entry_type_id == 1 || $attributes['logo_id'] == 0  ? 'block' : 'none' ?>">
              <div class="mb-4">
                <label for="team_club_name" class="form-label">Team/Club Name: <span class="required">*</span></label>
                <input type="text" class="form-control" name="team_club_name" id="team_club_name" value="<?php echo $club_name ?>">
              </div>

              <div class="mb-4">
                <label for="type_of_sport" class="form-label">Type of Sport: <span class="required">*</span></label>
                <select class="form-select form-select-lg" name="type_of_sport" id="type_of_sport">
                  <option <?php echo ($attributes['logo_id'] > 0) ? '' : 'selected' ?> value="0">Please select</option>
                  <option <?php echo ($details->type_of_sport_id == 1 ? 'selected' : '') ?> value="1">Football (Soccer)</option>
                  <option <?php echo ($details->type_of_sport_id == 2 ? 'selected' : '') ?> value="2">Rugby Leage</option>
                  <option <?php echo ($details->type_of_sport_id == 3 ? 'selected' : '') ?> value="3">Rugby Union</option>
                </select>
              </div>

              <div class="mb-4">
                <label for="entry_type" class="form-label">Level: <span class="required">*</span></label>
                <br/>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="logo_manager_level" id="level_international" value="1" <?php echo ($details->level_id == 1 || $attributes['logo_id'] == 0 ) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="level_international">International</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="logo_manager_level" id="level_national" value="2" <?php echo ($details->level_id == 2) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="level_national">National</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="logo_manager_level" id="level_club" value="3" <?php echo ($details->level_id == 3) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="level_club">Club</label>
                  </div>
              </div>
            </div>

            <div class="events-wrapper" style="display: <?php echo $entry_type_id == 2 ? 'block' : 'none' ?>">
              <div class="mb-4">
                  <label for="event_name" class="form-label">Event Name: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="event_name" id="event_name" value="<?php echo $event_name ?>">
              </div>
              <div class="mb-4">
                  <label for="event_country" class="form-label">Event Country: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="event_country" id="event_country" value="<?php echo $event_country ?>">
              </div>
            </div>

            <div class="organisations-wrapper" style="display: <?php echo $entry_type_id == 3 ? 'block' : 'none' ?>">
              <div class="mb-4">
                  <label for="organisation_name" class="form-label">Organisation Name: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="organisation_name" id="organisation_name" value="<?php echo $organisation_name ?>">
              </div>
              <div class="mb-4">
                  <label for="organisation_city" class="form-label">City: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="organisation_city" id="organisation_city" value="<?php echo $organisation_city ?>">
              </div>
              <div class="mb-4">
                  <label for="organisation_country" class="form-label">Country: <span class="required">*</span></label>
                  <input type="text" class="form-control" name="organisation_country" id="organisation_country" value="<?php echo $organisation_country ?>">
              </div>
            </div>

          
            <div class="card-footer">
              <input type="hidden" id="logo-id" value="<?php echo $attributes['logo_id'] ?>">
              <button type="submit" class="btn btn-primary btn-submit-logo-details">Save</button>
              <div class="lds-facebook preloader" style="display:none">
                <div></div>
                <div></div>
                <div></div>
              </div>

              <p class="foot-note"><span class="required">*</span> required fields</p>

            </div>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php hybrid_include('includes/admin/template/partials/notification.php'); ?>