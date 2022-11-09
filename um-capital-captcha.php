<?php 
/**
 * Plugin Name: Ultimate Member - Capital Captcha in Register form
 * Description:  This plugin adds capital challenge in the Register forms
 * Version: 1.0.0
 * Author: ZeroOne
 * Author URI: http://ultimatemember.com/
 * Text Domain: um-math-captcha
 * UM version: 2.1.20
 */
if ( function_exists( 'UM' ) && !class_exists( 'UM_Profile_Forms' ) ) {

    if( ! class_exists('CapitalCaptcha') ){
        require_once "includes/class-capital-captcha.php";
    }
    $cpa = new CapitalCaptcha();
     
    add_action("um_submit_form_register", function ($post_form){
	  global $cpa;
	  if( isset( $_REQUEST['um_math_challenge'] ) ){
		$captcha_val = $_REQUEST['um_math_challenge'];

		if( ! $cpa->validate($captcha_val) ){
		  UM()->form()->add_error('um_math_challenge', __( 'Incorrect answer. Please try again.', 'ultimate-member' ) );
		}
	  }
	},1);

    add_action("um_after_register_fields", function (){
	  global $cpa;
	  $cpa->reset_captcha();

	  $captcha_text = 'پایتخت' . $cpa->get_captcha_text() . ' :';
	  echo $cpa->get_captcha_text($captcha_text);
	  echo "<input  type='text' placeholder='".__("Your answer...","um-math-captcha")."' name='um_math_challenge' value=''/>";
	  echo UM()->fields()->field_error( UM()->fields()->show_error( 'um_math_challenge' ) );
	});

}