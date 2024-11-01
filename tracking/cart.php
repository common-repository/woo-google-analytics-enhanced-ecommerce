<?php
function wc_ga_ee_addcart ($cart_item_key, $product_id = null, $quantity = null, $variation_id = null, $variation = null, $cart_item_data = null) {
	global $woocommerce;
	
	if (session_status() == PHP_SESSION_NONE)
		session_start();

	/* validate quantity - the quantity needs to be numeric to work with GA */
	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
	
	if (is_null($quantity)) $quantity = 1;
	if (!$quantity) return;
	
	$items = $woocommerce->cart->get_cart();
	$item = $items[$cart_item_key];

	$product_id = $item['product_id'];
	$category = '';
	$variation = '';

	if (!$quantity) $quantity = 1;
	
	if ($item['variation']):
		$name = $item['data']->get_parent_data()['title'];
		$variation = reset($item['variation']);
		$price = $item['data']->get_price();
		$sku = $item['data']->get_parent_data()['sku'];
		
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

	$_SESSION['wc_ga_ee_addcart'] = array('product_id' => $product_id,
										  'name' => $name, 
										  'quantity' => $quantity,
										  'price' => $price,
										  'variation' => $variation,
										  'category' => $category);
}

function wc_ga_ee_addcart_footer() {
	if (session_status() == PHP_SESSION_NONE)
		session_start();

	if (!isset($_SESSION['wc_ga_ee_addcart']))
		return;
	
	$data = $_SESSION['wc_ga_ee_addcart'];
	unset($_SESSION['wc_ga_ee_addcart']);
?>
<script>
ga('require', 'ec');

var data = {
	'id': '<?php echo esc_js($data['product_id']); ?>',
	'name': '<?php echo esc_js($data['name']); ?>',
	'category': '<?php echo esc_js($data['category']); ?>',
	'variant': '<?php echo esc_js($data['variation']); ?>',
	'price': '<?php echo esc_js(number_format($data['price'], 2, '.', '')); ?>',
	'quantity': <?php echo esc_js($data['quantity']); ?>
}
ga('ec:addProduct', data);

ga('ec:setAction', 'add');

ga('send', 'event', 'Ecommerce', 'add', 'add to cart', {'nonInteraction': true});
</script>
<?php
}

add_action( 'woocommerce_add_to_cart', 'wc_ga_ee_addcart', 10, 1 );
add_action( 'wp_footer', 'wc_ga_ee_addcart_footer', 100, 1 );
	





function wc_ga_ee_removecart ($removed_cart_item_key, $cart) {
	global $woocommerce;
	
	if (session_status() == PHP_SESSION_NONE)
		session_start();

	$quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
	
	if (is_null($quantity)) $quantity = 1;
	if (!$quantity) return;
	
	$items = $woocommerce->cart->get_cart();		
	$item = $items[$removed_cart_item_key];

	$product_id = $item['product_id'];
	$category = '';
	$variation = '';

	if (!$quantity) $quantity = 1;
	
	if ($item['variation']):
		$name = $item['data']->get_parent_data()['title'];
		$variation = reset($item['variation']);
		$price = $item['data']->get_price();
		$sku = $item['data']->get_sku();
		
		if (!$sku):
 			$sku = $item['data']->get_parent_data()['sku'];
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

			
	$_SESSION['wc_ga_ee_removecart'] = array('product_id' => $product_id,
										  'name' => $name, 
										  'quantity' => $quantity,
										  'price' => $price,
										  'variation' => $variation,
										  'category' => $category);
}

function wc_ga_ee_removecart_footer() {
	if (session_status() == PHP_SESSION_NONE)
		session_start();

	if (!isset($_SESSION['wc_ga_ee_removecart']))
		return;
	
	$data = $_SESSION['wc_ga_ee_removecart'];
	unset($_SESSION['wc_ga_ee_removecart']);
?>
<script>
ga('require', 'ec');

var data = {
	'id': '<?php echo esc_js($data['product_id']); ?>',
	'name': '<?php echo esc_js($data['name']); ?>',
	'category': '<?php echo esc_js($data['category']); ?>',
	'variant': '<?php echo esc_js($data['variation']); ?>',
	'price': '<?php echo esc_js(number_format($data['price'], 2, '.', '')); ?>',
	'quantity': <?php echo esc_js($data['quantity']); ?>
}

ga('ec:addProduct', data);

ga('ec:setAction', 'remove');

ga('send', 'event', 'Ecommerce', 'remove', 'cart item removed', {'nonInteraction': true});
</script>
<?php
}
add_action( 'woocommerce_remove_cart_item', 'wc_ga_ee_removecart', 10, 1 );
add_action( 'wp_footer', 'wc_ga_ee_removecart_footer', 100, 2 );