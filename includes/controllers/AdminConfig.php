<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AdminConfig {

    public function __construct() {
        add_action('admin_head', [$this, 'remove_menu']);
    }

    public function remove_menu() {

      $user = wp_get_current_user();

      if ( in_array('shop_manager', (array) $user->roles ) )
      {
        remove_menu_page('edit-comments.php'); //Comments
        remove_menu_page('plugins.php'); //Plugins
        remove_menu_page('tools.php'); //Tools
        remove_menu_page('users.php'); //Users
        remove_menu_page('upload.php'); //Media
        remove_menu_page('themes.php'); // Appearance
        remove_menu_page('options-general.php'); //Settings

        remove_menu_page('edit.php'); //Posts
        remove_menu_page('edit.php?post_type=page'); // Pages
        remove_menu_page('edit.php?post_type=acf-field-group');
        remove_menu_page('edit.php?post_type=product');
        remove_menu_page('edit.php?post_type=testimonials');
        remove_menu_page('edit.php?post_type=shop_order');


        remove_menu_page('yith_plugin_panel');
        remove_menu_page('cptui_main_menu');

        remove_submenu_page( 'index.php', 'update-core.php');
      }
   }
}

$adminConfig = new AdminConfig();
?>
