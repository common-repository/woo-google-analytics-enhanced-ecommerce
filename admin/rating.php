<?php

// @TODO - at some point, we'll want to track ratings in our database

function wc_ga_ee_rating_notice() {	
	if ( is_admin() && current_user_can( 'manage_options' ) ) {
		/* If user elected to close the reviews box, don't show */
		if (get_option('wc_ga_ee_review_closed'))
			return;

		/* If user already gave a rating, don't show */
		//if (get_option('wc_ga_ee_review_rating'))
		//	return;
		
		/* If it hasn't been activated for 14 days yet */
		$days14ago = time() - (14 * 86400);
		if (strtotime(get_option('wc_ga_ee_activation_date')) > $days14ago)
			return;
		
		/* If user already answered the "working as expected" question, then show a feedback option, or link to rate on Wordpress (depending on answer) */
		$answer = get_option('wc_ga_ee_review_question');
		
		if (!$answer):	
			$adminEmail = esc_attr(get_option('admin_email'));
			
			echo <<<EOD
<div class="updated wc-ga-ee-global-message notice is-dismissible" data-email="$adminEmail"><p>How&#39;s the WooCommerce Google Analytics <b>Enhanced Ecommerce</b> plugin been working for you? &nbsp; <span class="wc-ga-ee-review-button-wrapper"><a href="" class="wc-ga-ee-notice-link button" data-review="good"><span class="dashicons dashicons-thumbs-up"></span> great</a> &nbsp; <a href="" class="wc-ga-ee-notice-link button" data-review="bad"><span class="dashicons dashicons-thumbs-down"></span> not so good</a></span></p></div>
<style>
	.wc-ga-ee-notice-link span { vertical-align: middle; line-height: .8em; }
	.wc-ga-ee-global-message .notice-dismiss { display: none; }
	@media all and (max-width: 1024px) {
		.wc-ga-ee-review-button-wrapper { display: block; margin-top: 12px; }
	}
</style>
EOD;
	
		elseif ($answer === 'good'):
			echo '<div class="wc-ga-ee-global-message updated notice is-dismissible"><p>Would you mind <a class="wc-ga-ee-rating-link" href="https://wordpress.org/support/plugin/woocommerce-google-analytics-enhanced-ecommerce/reviews/#new-post" target="_blank">rating our plugin</a> on WordPress.org?</p></div>';
		endif;
	}
}


add_action( 'admin_notices', 'wc_ga_ee_rating_notice' );



function wc_ga_ee_update_review_answer () {
		
	$review = sanitize_text_field($_REQUEST['review']);
	
	if ($review != 'good' && $review != 'bad' && $review != 'close') wp_die();
	
	add_option('wc_ga_ee_review_question', $review);
	
	if ($review === 'good'):
		// if answer is "good", then set option in database (so rating form comes up next)
		add_option ('wc_ga_ee_review_question', 'yes');
	else:
		// if answer is "bad", set wc_ga_ee_review_closed = true, then show feedback form
		add_option ('wc_ga_ee_review_closed', true);
	endif;
			
	wp_die();
}

add_action( 'wp_ajax_wc_ga_ee_update_review_answer', 'wc_ga_ee_update_review_answer' );