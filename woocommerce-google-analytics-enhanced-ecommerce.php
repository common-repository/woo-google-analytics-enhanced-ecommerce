<?php
/*
Plugin Name: WooCommerce Google Analytics Enhanced Ecommerce
Plugin URI: https://wordpress.org/plugins/woocommerce-google-analytics-enhanced-ecommerce/
Description: Adds advanced event & purchase tracking to your WooCommerce store. Sends this data to Google Analytics for ecommerce reporting and insights.
Author: Universal Web Services, LLC
Version: 1.0
Author URI: https://www.universalwebservices.net/
Text Domain: woocommerce-google-analytics-enhanced-ecommerce
*/


// WordPress slug  woocommerce-google-analytics-enhanced-ecommerce

defined( 'ABSPATH' ) or die( 'Nice try!' );  // block direct access to script

// add "settings" link to plugins page
add_filter("plugin_action_links_".plugin_basename(__FILE__), "wc_ga_ee_plugin_actions", 10, 4);
function wc_ga_ee_plugin_actions( $actions, $plugin_file, $plugin_data, $context ) {
	array_unshift($actions, "<a href=\"".menu_page_url('wc-ga-enhanced-ecommerce', false)."\">".esc_html__("Settings")."</a>");
	return $actions;
}

require('tracking/cart.php');
require('ajax/cart.php');

function wc_ga_ee_admin_scripts() {
	wp_enqueue_script('wc-ga-ee-admin-js', plugins_url('src/admin.js', __FILE__), array('jquery'), 1.2, true);	
}
function wc_ga_ee_front_scripts() {
	wp_enqueue_script( 'wc-ga-ee-default-js', plugins_url('src/default.js', __FILE__), array('jquery'), 1.2, true);
}

if (is_admin()) {
	add_action( 'admin_enqueue_scripts', 'wc_ga_ee_admin_scripts' );

	require('admin/activate.php');
	register_activation_hook( __FILE__, 'wc_ga_ee_activate' );
	
	require('admin/rating.php');
	require('admin/settings.php');
}
else {
	add_action( 'wp_enqueue_scripts', 'wc_ga_ee_front_scripts' );	

	$wc_ga_ee_tracking_id = trim(get_option('wc_ga_ee_tracking_id'));
		
	if ($wc_ga_ee_tracking_id)
		require('tracking/ga-setup.php');	

	require('tracking/impression.php');
	require('tracking/product-view.php');
	require('tracking/checkout.php');
	require('tracking/purchase.php');
}

