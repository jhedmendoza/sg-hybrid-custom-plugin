<?php
/*
Plugin Name: SG Hybrid Custom Plugin
Description: This plugin is used for extending YITH Auctions for WooCommerce Plugin
Version: 1.0
Author: Hybrid Anchor
Author URI: https://www.hybridanchor.com/
*/
 
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

if ( !class_exists('Hybrid') ) :

class Hybrid {

	/** @var string The plugin version number. */

	var $version = '1';


	function __construct() {
		register_activation_hook( __FILE__  , array($this, 'hybrid_install') );
	}

	function initialize() {

		//allow shop manager user type to access the plugin
		$role = get_role('shop_manager');
		$role->add_cap('manage_options');

		switch ($_SERVER['SERVER_NAME']) {

			case 'scotchgalore-careers.local':
				$this->define('ENV', 'local');
			break;

			case 'scotchgalore.hybridanchor.com/':
				$this->define('ENV', 'staging');
			break;

			default:
				$this->define('ENV', 'prod');
			break;
		}

		// Define constants.
		$this->define('HYBRID_PATH', plugin_dir_path( __FILE__ ) );
		$this->define('HYBRID_DIR_URL', plugin_dir_url( __FILE__ ) );
		$this->define('HYBRID_BASENAME', plugin_basename( __FILE__ ) );
		$this->define('HYBRID_VERSION', $this->version );

		//Include libraries
		// require_once(HYBRID_PATH.'includes/lib/vendor/autoload.php');

		// Include utility functions.
		require_once(HYBRID_PATH.'includes/utility-function.php');

		require_once(HYBRID_PATH.'includes/db-functions.php');

		//Include controllers.
		require_once(HYBRID_PATH.'includes/controllers/Email.php');
		require_once(HYBRID_PATH.'includes/controllers/User.php');
    	require_once(HYBRID_PATH.'includes/controllers/Auction.php');
		require_once(HYBRID_PATH.'includes/controllers/CronJob.php');
		require_once(HYBRID_PATH.'includes/controllers/AdminConfig.php');

		//Admin controllers.
		require_once(HYBRID_PATH.'includes/admin/controllers/AuctionRequests.php');

		//Include shortcodes.
		hybrid_include('includes/shortcodes/forms.php');

		//Include core.
		hybrid_include('includes/hybrid-assets.php');

		hybrid_include('includes/admin/initialize-admin.php');




 	}


	function define( $name, $value = true ) {

		if( !defined($name) ) {
			define( $name, $value );
		}
	}


	function hybrid_install() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'sg_hybrid_user_bid';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
						id INT (11) AUTO_INCREMENT,
						product_id INT(11),
						user_id INT(150),
						bid FLOAT,
						status TINYINT (1),
						date DATETIME DEFAULT CURRENT_TIMESTAMP,
						PRIMARY KEY (id)
					) $charset_collate";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		add_option( 'sg_db_version', HYBRID_VERSION );
	}

}


function hybrid() {

	global $hybrid;

	// Instantiate only once.
	if( !isset($hybrid) ) {
		$hybrid = new Hybrid();
		$hybrid->initialize();
	}

	return $hybrid;

 }

 hybrid();

endif; // class_exists check
