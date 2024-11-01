<?php

function wc_ga_ee_checkout_tracking () {
	global $woocommerce;
	
	if (!is_checkout() || is_order_received_page()) return;

	$items = $woocommerce->cart->get_cart();

	echo "<script>ga('require', 'ec');</script>";
	
	echo '<script>';
	foreach ($items as $item):
		$product_id = $item['product_id'];
		$quantity = $item['quantity'];
		$category = '';
		$variation = '';
		
		if (!$quantity) $quantity = 1;
		
		if ($item['variation']):
			$name = $item['data']->get_parent_data()['title'];
			$variation = reset($item['variation']);
			$price = $item['data']->get_price();
			$sku = $item['data']->get_parent_data()['sku']; //get_sku();
			
			if (!$sku):
				$sku = $item['data']->get_sku();
			endif;
		else:
			$name = $item['data']->get_name();
			$price = $item['line_subtotal'];
			$sku = $item['data']->get_sku();
		endif;
		
		$categories = get_the_terms( $product_id, 'product_cat' );
		if ($categories)
			$category = $categories[0]->name;
		
		if ($sku)
			$product_id = $sku;
		
		$name = esc_js($name);
		$variation = esc_js($variation);
		$category = esc_js($category);
		$price = esc_js($prie);
		$product_id = esc_js($product_id);
		$quantity = esc_js($quantity);
		
		echo <<<EOD
		ga('ec:addProduct', {
			'id': '$product_id',
			'name': '$name',
			'category': '$category',
			'variant': '$variation',
			'price': '$price',
			'quantity': $quantity
		});
EOD;
				
	endforeach;
	
	echo '</script>';
	
?>
<script>

ga('ec:setAction','checkout', {
    'step': 1,
});

ga('send', 'event', 'Ecommerce', 'checkout', 'step 1', {nonInteraction: true});
</script>
<?php

}

add_action( 'wp_footer', 'wc_ga_ee_checkout_tracking', 100, 2 );