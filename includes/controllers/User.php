<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class User {

    public function __construct() {
      add_action('wp_ajax_create_user_login', [$this, 'user_login']);
      add_action('wp_ajax_nopriv_user_login', [$this, 'user_login']);

      add_action('wp_ajax_create_user_registration', [$this, 'user_registration']);
      add_action('wp_ajax_nopriv_user_registration', [$this, 'user_registration']);
    }

    public function user_login() {

      $username = sanitize_text_field($_POST['username']);
      $password = sanitize_text_field($_POST['password']);

      $signon = wp_signon([
        'user_login'    => $username,
        'user_password' => $password,
      ]);

      if ( isset($signon->data) && !empty($signon->data) ) {
          echo wp_json_encode([
            'status' => true,
            'msg'    => 'Login successful',
          ]);
      }
      else {

        if ( isset($signon->errors['empty_username']) || isset($signon->errors['empty_password']) ) {
          $errors = 'Please enter username and password';
        }
        else if ( isset($signon->errors['invalid_username']) ) {
          $errors = $signon->errors['invalid_username'][0];
        }
        else if ( isset($signon->errors['invalid_password']) ) {
          $errors = $signon->errors['invalid_password'][0];
        }

        echo wp_json_encode([
          'status' => false,
          'error'  => strip_tags($errors),
          'msg'    => 'Error validation',
        ]);
      }
      exit;
    }

    public function user_registration() {

    }

}

$users = new User();
?>
