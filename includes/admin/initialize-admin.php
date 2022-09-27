<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

add_action( 'admin_menu', 'initialize_fc_logo_manager_menus' );

function initialize_fc_logo_manager_menus() {
  
  $title = 'Logo Manager';
  $slug  = 'logo-manager';
  $icon  = 'dashicons-images-alt2';
  $position = 50;

  add_menu_page( $title, $title, 'manage_options', $slug, '', $icon, $position );

  $logo_list_menu  = add_submenu_page( $slug, 'Logos', 'Logos', 'manage_options', 'fc-logo-list', 'fc_logo_manager_list');
  $logo_add_menu = add_submenu_page( $slug, 'Add Logo', 'Add Logo', 'manage_options', 'fc-logo', 'fc_logo_manager');

  remove_submenu_page($slug, $slug);

  add_action('load-'.$logo_list_menu, 'load_admin_css_js');
  add_action('load-'.$logo_add_menu, 'load_admin_css_js');

/*-------------------------------------------------------------------------------------------------------------------- */

  $slug_qualifications  = 'qualifications';

  add_menu_page( 'Qualifications', 'Qualifications', 'manage_options', $slug_qualifications, '', 'dashicons-welcome-learn-more', 49 );

  $organisations_list_menu = add_submenu_page( $slug_qualifications, 'Organisations', 'Organisations', 'manage_options', 'fc-organisation-list', 'fc_organisation_list');
  $organisation_add_menu   = add_submenu_page( $slug_qualifications, 'Add Organisation', 'Add Organisation', 'manage_options', 'fc-organisation', 'fc_organisation');
  $qualifications_add_menu = add_submenu_page( $slug_qualifications, 'Add Qualification', 'Add Qualification', 'manage_options', 'fc-qualification', 'fc_qualification');

  remove_submenu_page($slug_qualifications, $slug_qualifications);

  add_action('load-'.$organisations_list_menu, 'load_admin_css_js');
  add_action('load-'.$organisation_add_menu, 'load_admin_css_js');
  add_action('load-'.$qualifications_add_menu, 'load_admin_css_js');
 
}

function load_admin_css_js() {
  add_action( 'admin_enqueue_scripts', 'enqueue_admin_css_js');
}

function enqueue_admin_css_js() {

  $admin_version_script = '1.7';

  //Core media script
  wp_enqueue_media();

  wp_enqueue_style('bootstrap-admin', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css', [], '5.2.0');

  wp_enqueue_style('fontawesome-free', HYBRID_DIR_URL.'includes/admin/assets/lib/fontawesome-free/css/all.min.css', [], '5.15.1');
  wp_enqueue_style('fc-general-admin', HYBRID_DIR_URL.'includes/admin/assets/css/fc-general-admin.css', [], $admin_version_script);
  wp_enqueue_style('fc-admin-logo-manager-page', HYBRID_DIR_URL.'includes/admin/assets/css/fc-admin-logo-manager.css', [], $admin_version_script);
  wp_enqueue_style('fc-admin-qualification-page', HYBRID_DIR_URL.'includes/admin/assets/css/fc-admin-qualifications.css', [], $admin_version_script);


  wp_enqueue_script('kit-fontawesome','https://kit.fontawesome.com/ee83b0058f.js', [], '2.11.5', true );
  wp_enqueue_script('popper','https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js', [], '2.11.5', true );
  wp_enqueue_script('bootstrap-admin','https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js', ['popper'], '5.2.0', true );

  wp_enqueue_script('jquery-validate', HYBRID_DIR_URL.'includes/admin/assets/lib/jquery-validate/jquery.validate.min.js', ['jquery'], '1.19.5', true );
  wp_enqueue_script('jquery-validate-additional-methods', HYBRID_DIR_URL.'includes/admin/assets/lib/jquery-validate/additional-methods.min.js', ['jquery', 'jquery-validate'], '1.19.5', true );
  wp_enqueue_script('fc-admin-tools', HYBRID_DIR_URL . 'includes/admin/assets/js/fc-admin-tools.js', ['jquery'], $admin_version_script, true );
  wp_enqueue_script('fc-admin-logo-manager-page', HYBRID_DIR_URL.'includes/admin/assets/js/fc-admin-logo-manager.js', [], $admin_version_script, true );
  wp_enqueue_script('fc-admin-qualification-page', HYBRID_DIR_URL.'includes/admin/assets/js/fc-admin-qualifications.js', [], $admin_version_script, true );
}


function fc_logo_manager() {

  $logo_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

  $logoManager = new LogoManager();

  $result = [
    'logo_id' => $logo_id,
    'logo'    => $logoManager->get_logo($logo_id)
  ];
  hybrid_include('includes/admin/template/logo_manager/add-edit.php', $result);
}

function fc_logo_manager_list() {

  $logoManager = new LogoManager();
  $logos = $logoManager->get_all_logo();
  $pagination = render_pagination($logos);

  $result = [
    'logos'     => $logos,
    'pagination'=> $pagination
  ];

  unset($result['logos']['pagination']);

  hybrid_include('includes/admin/template/logo_manager/list.php', $result);
}


function fc_organisation_list() 
{
  $qualifications = new Qualifications();
  $organisations = $qualifications->get_all_data('qualifications_organisations');

  foreach ($organisations as $organisation) {

    $contacts = $qualifications->get_contacts_by_organisation_id($organisation->id);

    $result['organisations'][] = array(
      'id'                => $organisation->id,
      'organisation_name' => $organisation->organisation_name,
      'logo_url'          => $organisation->logo_url,
      'date_created'      => $organisation->date_created,
      'contact_name'      => !empty($contacts->first_name) ? $contacts->first_name.' '.$contacts->last_name : '<p style="color:#696969;font-style:italic">contact not available</p>',
    );
  }

  hybrid_include('includes/admin/template/qualifications/list-organisation.php', $result);
}

function fc_organisation() 
{
  $organisation_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $qualifications  = new Qualifications();
  $organisation    = $qualifications->get_data($organisation_id, 'qualifications_organisations');
  $contacts = $qualifications->get_contacts_by_organisation_id($organisation_id);

  $result = [
    'organisation_id' => $organisation_id,
    'organisation'    => $organisation,
    'contacts'        => $contacts
  ];

  hybrid_include('includes/admin/template/qualifications/add-edit-organisation.php', $result);
}

function fc_qualification() 
{
  $qualification_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  $qualifications = new Qualifications();

  $result = [
    'organisations'    => $qualifications->get_all_data('qualifications_organisations'),
    'qualification_id' => $qualification_id    
  ];

  hybrid_include('includes/admin/template/qualifications/add-edit-qualification.php', $result);
}

function render_pagination($data)
{
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

