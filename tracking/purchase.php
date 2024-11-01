<?php
$wc_ga_ee_order = null;
$wc_ga_ee_order_id = null;

function wc_ga_ee_purchase_tracking ($order_id) {
	global $wc_ga_ee_order, $wc_ga_ee_order_id;
	
	$wc_ga_ee_order = new WC_Order( $order_id );	
	$wc_ga_ee_order_id =  $order_id;
}


function wc_ga_ee_purchase_tracking_next() {
	if (!is_order_received_page()) return;
	
	global $wc_ga_ee_order, $wc_ga_ee_order_id;
	
	$total = $wc_ga_ee_order->get_total();
	$items = $wc_ga_ee_order->get_items();
?>
<script>
ga('require', 'ec');

ga('set', 'currencyCode', '<?php echo esc_js($wc_ga_ee_order->get_currency()); ?>');


<?php 
foreach ($items as $item):
	$variation = '';
	$name = $item['name'];
	$category = '';
	$product_id = $item['product_id'];
	
	$product = $wc_ga_ee_order->get_product_from_item( $item );
	$sku = $product->get_sku();
	
	if (intval($item['variation_id']) > 0):
		$parts = explode('-', $item['name']);
		$variation = trim(array_pop($parts));
		
		$name = trim(implode('-', $parts)); 	
		
		$parentId = $product->get_parent_id();
		$parentProduct = wc_get_product($parentId);
		$parentSku = $parentProduct->get_sku();
		
		if ($parentSku) $sku = $parentSku;
	endif;

	if (!$sku) $sku = $product_id;
	
	$categories = get_the_terms( $product_id, 'product_cat' );
	if ($categories)
		$category = $categories[0]->name;
	
?>

ga('ec:addProduct', {
  'id': '<?php echo esc_js($sku); ?>',
  'name': '<?php echo esc_js($name); ?>',
  'category': '<?php echo esc_js($category); ?>',
  'variant': '<?php echo esc_js($variation); ?>',
  'price': '<?php echo esc_js(number_format($item['line_subtotal'] / $item['qty'], 2, '.', '')); ?>',
  'quantity': <?php echo esc_js($item['qty']); ?>
});
<?php endforeach; ?>

ga('ec:setAction', 'purchase', {
  'id': '<?php echo esc_js($wc_ga_ee_order_id); ?>',
  'revenue': '<?php echo esc_js(number_format($wc_ga_ee_order->get_total(), 2, '.', '')); ?>',
  'tax': '<?php echo esc_js(number_format($wc_ga_ee_order->get_total_tax(), 2, '.', '')); ?>',
  'shipping': '<?php echo esc_js(number_format($wc_ga_ee_order->get_shipping_total(), 2, '.', '')); ?>',
  'coupon': '<?php echo esc_js(implode(',', $wc_ga_ee_order->get_used_coupons())); ?>'    // User added a coupon at checkout.
});

ga('send', 'event', 'ecommerce', 'purchase', {'nonInteraction': true});
</script>
<?php

}

add_action( 'woocommerce_thankyou', 'wc_ga_ee_purchase_tracking', 10, 1 );
add_action( 'wp_footer', 'wc_ga_ee_purchase_tracking_next', 100, 2 );