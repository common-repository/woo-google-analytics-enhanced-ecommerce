<?php
function wc_ga_ee_ajax_get_cart_info() {
	if (session_status() == PHP_SESSION_NONE)
		session_start();
	
	echo json_encode($_SESSION['wc_ga_ee_addcart']);
	unset($_SESSION['wc_ga_ee_addcart']);
	wp_die();
}

add_action( 'wp_ajax_wc_ga_ee_ajax_get_cart_info', 'wc_ga_ee_ajax_get_cart_info' );
add_action( 'wp_ajax_nopriv_wc_ga_ee_ajax_get_cart_info', 'wc_ga_ee_ajax_get_cart_info' );