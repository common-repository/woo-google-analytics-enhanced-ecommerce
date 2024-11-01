<?php
$wc_ga_ee_impression_counter = 0;

function wc_ga_ee_archive_impression_tracking() {
	global $product, $wc_ga_ee_impression_counter;
	
	$wc_ga_ee_impression_counter++;
	
	$id = $product->get_sku();
	if (!$id) $id = get_the_ID();
	$name = get_the_title();
	$category = '';
	$list = '';
	
	if (is_archive()):
		$category = single_cat_title('', false);
		$list = 'Archive/Category Listing';
	elseif (is_home()):
		$list = 'Home Page Listing';
	elseif (is_product()):
		$list = 'Related Products Listing';
	endif;
	
	$position = $wc_ga_ee_impression_counter;
	
	echo '<span class="wc-ga-ee-impression" data-id="' . esc_attr($id) . '" data-name="' . esc_attr($name) . '" data-cat="' . esc_attr($category) . '" data-list="' . esc_attr($list) . '"></span>';
}
add_action( 'woocommerce_after_shop_loop_item', 'wc_ga_ee_archive_impression_tracking', 10 );



function wc_ga_ee_search_impression_tracking($post) {
	if( !is_search() ) return;
	
	global $wc_ga_ee_impression_counter;
	
	$wc_ga_ee_impression_counter++;
	
	$product = wc_get_product( get_the_ID() );

	if (!$product) return;
	
	$id = $product->get_sku();
	if (!$id) $id = get_the_ID();

	$name = get_the_title();
	$category = '';
	$list = 'Search Results';
	$position = $wc_ga_ee_impression_counter;
	
	echo '<span class="wc-ga-ee-impression" data-id="' . esc_attr($id) . '" data-name="' . esc_attr($name) . '" data-cat="' . esc_attr($category) . '" data-list="' . esc_attr($list) . '"></span>';
}

add_action( 'the_post', 'wc_ga_ee_search_impression_tracking', 10);