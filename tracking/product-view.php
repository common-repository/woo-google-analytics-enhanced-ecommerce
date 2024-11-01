<?php

function wc_ga_ee_product_view_tracking () {
	
	if (!is_product()) return;
	
	global $product;
	
	$sku = $product->get_sku();
	$id = get_the_ID();
	if (!$sku)
		$sku = $id;

	$category = '';
	$categories = get_the_terms( $id, 'product_cat' );
	if ($categories)
		$category = $categories[0]->name;

	$name = get_the_title();
	
?>
<script>
ga('require', 'ec');

ga('ec:addProduct', {
  'id': '<?php echo esc_js($sku); ?>',
  'name': '<?php echo esc_js($name); ?>',
  'category': '<?php echo esc_js($category); ?>'
});

ga('ec:setAction', 'detail');	
ga('send', 'event', 'Ecommerce', 'detail', 'product view', {nonInteraction: true});
</script>
<?php

}

add_action( 'wp_footer', 'wc_ga_ee_product_view_tracking', 100, 2 );