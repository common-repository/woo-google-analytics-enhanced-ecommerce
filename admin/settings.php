<?php
add_action( 'admin_menu', 'wc_ga_ee_menu' );
add_action( 'admin_init', 'wc_ga_ee_register_settings' );

function wc_ga_ee_register_settings() { // whitelist options
	register_setting( 'wc_ga_ee_group', 'wc_ga_ee_tracking_id'); // the ga tracking id  (only used if it doesn't already exist)
	register_setting( 'wc_ga_ee_group', 'wc_ga_ee_confirm_enabled_ga_ecomm');   // user confirms that they've enabled enhanced e-commerce 
}

function wc_ga_ee_menu() {
	add_options_page(__("WooCommerce Google Analytics Enhanced Ecommerce Options", 'wc-ga-ee'), __("WooCommerce Google Analytics Enhanced Ecommerce", 'wc-ga-ee'), 'manage_options', 'wc-ga-enhanced-ecommerce', 'wc_ga_ee_options');
}


function wc_ga_ee_options() {
	add_thickbox();
?>
<style>
	.field { margin: 16px 0; font-size: 16px; }
	.field input[type=text] { padding: 8px; font-size: 16px; }
	
	.field span, .field label input { display: inline-block; margin: 4px 0; } 
	.field label span { width: 300px; }
	.field span.description { font-size: 16px; vertical-align: middle; }
	.field .extended-description { display: none; margin: 8px 0; }
	.activate-extended-description { cursor: pointer; }
	.extended-description a { text-decoration: none; }
	.extended-description a:hover { text-decoration: underline; }
	
	.field-wc_ga_ee_confirm_enabled_ga_ecomm .activate-extended-description { position: relative; top: 1px; }
	
	.form-controls { margin-top: 64px; }
</style>
	
<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br>
	</div>
	<h1>
		<?php esc_html_e("WooCommerce Google Analytics Enhanced Ecommerce Options", 'wc-ga-ee'); ?>
	</h1>
	
	<form method="post" action="options.php">
		<?php settings_fields('wc_ga_ee_group'); ?>

		<div class="field">
			<label>
				<span><?php esc_html_e("Google Analytics Tracking ID", 'wc-ga-ee'); ?></span>
				<input type="text" name="wc_ga_ee_tracking_id" value="<?php echo esc_attr(get_option('wc_ga_ee_tracking_id')); ?>" placeholder="UA-XXXXX-XX" data-lpignore="true">
			</label>
			<span class="description"><?php echo __('Optional', 'wc-ga-ee'); ?> <a class="dashicons dashicons-editor-help activate-extended-description"></a></span>
			<p class="extended-description"><?php echo __('You only need to enter your Google Analytics Tracking ID if you don\'t have Google Analytics setup on this site already.<br>Where do I find my Tracking ID? <a href="https://support.google.com/analytics/answer/1008080?hl=en" target="_blank">Guide</a> | <a class="thickbox" href="' .  plugins_url() . '/woocommerce-google-analytics-enhanced-ecommerce/src/tracking-id-location.png">Quick View</a>', 'wc-ga-ee'); ?></p>
		</div>

		<div class="field field-wc_ga_ee_confirm_enabled_ga_ecomm">
			<label>
				<span><?php esc_html_e("I have enabled Enhanced Ecommerce", 'wc-ga-ee'); ?></span>
				<input type="checkbox" name="wc_ga_ee_confirm_enabled_ga_ecomm"<?php if (get_option('wc_ga_ee_confirm_enabled_ga_ecomm')) echo ' checked'; ?> value="1">
			</label>
			<span class="description"><a class="dashicons dashicons-editor-help activate-extended-description"></a></span>
			<p class="extended-description"><?php echo __('Check this box to confirm that you have enabled Enhanced Ecommerce in your Google Analytics account.<br>How do I enabled Enhanced Ecommerce? <a href="https://support.google.com/analytics/answer/6032539?hl=en" target="_blank">Guide</a> | <a class="thickbox" href="' .  plugins_url() . '/woocommerce-google-analytics-enhanced-ecommerce/src/enable-enhanced-ecommerce-step-1.jpg">Quick View (1)</a> <a class="thickbox" href="' .  plugins_url() . '/woocommerce-google-analytics-enhanced-ecommerce/src/enable-enhanced-ecommerce-step-2.jpg">Quick View (2)</a>', 'wc-ga-ee'); ?></p>
		</div>

		<div class="form-controls">
			<input type="submit" class="button-primary" value="<?php esc_html_e('Save Changes', 'wc-ga-ee') ?>">
		</div>
	</form>
</div>

<script>

</script>

<?php
}