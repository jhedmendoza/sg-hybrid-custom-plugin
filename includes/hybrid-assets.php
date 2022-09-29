<?php
if ( !defined('ABSPATH') ) exit; // Exit if accessed directly

add_action('wp_head', 'js_inline_script' );
add_action('wp_enqueue_scripts', 'hybrid_enqueue_script');

function hybrid_enqueue_script() {

	$version_script = '1';
	wp_enqueue_style('sg-hybrid-custom', HYBRID_DIR_URL . 'assets/css/custom.css', [], $version_script);

	//enqueue external js lib
	wp_enqueue_script('swal-alert','https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js', ['jquery'], '10.15.5', true);

	//enqueue js
	wp_enqueue_script('sg-hybrid-register-script', HYBRID_DIR_URL . 'assets/js/register.js', ['jquery'], $version_script, true);
	wp_enqueue_script('sg-hybrid-login-script', HYBRID_DIR_URL . 'assets/js/login.js', ['jquery'], $version_script, true);
	wp_enqueue_script('sg-hybrid-layout-script', HYBRID_DIR_URL . 'assets/js/layout.js', ['jquery'], $version_script, true);

}

function js_inline_script() {
?>
<script type="text/javascript">
   		var sg_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
		var siteurl = "<?php echo site_url(); ?>";
		var isUserLogin = "<?php echo is_user_logged_in() ? 1: 0 ?>";
</script>
<?php
}
