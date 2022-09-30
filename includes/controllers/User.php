<?php
if (!defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class User {

    public function __construct() {
      add_action('wp_ajax_create_sg_user_login', [$this, 'sg_user_login']);
      add_action('wp_ajax_nopriv_sg_user_login', [$this, 'sg_user_login']);
      add_action('wp_ajax_create_sg_user_registration', [$this, 'sg_user_registration']);
      add_action('wp_ajax_nopriv_sg_user_registration', [$this, 'sg_user_registration']);
    }

    public function sg_user_login() {

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

    public function sg_user_registration() {
      $email    = sanitize_text_field($_POST['email']);
      $username = sanitize_text_field($_POST['username']);
      $password = sanitize_text_field($_POST['password']);
      $repeatPassword = sanitize_text_field($_POST['repeat_password']);

      $validate = $this->validate($email, $username, $password, $repeatPassword);

      if ($validate['count'] > 0) {

        echo wp_json_encode([
          'status' => false,
          'msg'    => $validate['error_msg'],
        ]);
      }
      else {
        $user_id = wp_create_user( $username, $password, $email);

        if( !is_wp_error($user_id) ) {
          $user = get_user_by( 'id', $user_id );
          $user->set_role( 'subscriber' );

          echo wp_json_encode([
            'status' => true,
            'msg'    => 'User successfuly created',
          ]);
        }
      }
      exit;

    }

    public function validate($email, $username, $password, $repeatPassword) {

      $count = 0;

      if ( empty($email) ) {
        $error_msg = 'Please enter your email';
        $count++;
      }
      else if (email_exists($email)) {
        $error_msg = 'Email already exists';
        $count++;
      }
      else if (!is_email($email)) {
        $error_msg = 'Email is not valid email address';
        $count++;
      }

      else if ( empty( $username ) ) {
        $error_msg = 'Please enter your username';
        $count++;
      }

      else if ( username_exists( $username ) ) {
        $error_msg = 'Username already exists';
        $count++;
      }

      else if ( empty( $password ) ) {
        $error_msg = 'Please enter your password';
        $count++;
      }

      else if ( $password != $repeatPassword ) {
        $error_msg = 'Your password does not match';
        $count++;
      }

      return [
        'count'     => $count,
        'error_msg' => $error_msg
      ];

    }

}

$users = new User();
?>
