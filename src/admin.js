(function($) {
	
	$('body').on('click', '.activate-extended-description', function(e) {
		e.preventDefault();
		
		$(this).parents('.field').find('.extended-description').slideDown();
	});
	
})(jQuery);

/* rating */
if (jQuery('.wc-ga-ee-global-message').length) {
	(function($) {
	
		Userback = window.Userback || {};
	    Userback.access_token = '1456|1878|RBLbLRNlALYvIvvofmc5tnHIRRUGc0s4Begn53JGPIcydIWt48';
	    Userback.email = $('.wc-ga-ee-global-message').data('email');
	    
	    (function(id) {
	        if (document.getElementById(id)) {return;}
	        var s = document.createElement('script');
	        s.id = id;
	        s.src = 'https://static.userback.io/widget/v1.js';
	        var parent_node = document.head || document.body;
	        parent_node.appendChild(s);
	    })('userback-sdk');
	    
		$('body').on('click', '.wc-ga-ee-rating-link, .wc-ga-ee-global-message .notice-dismiss', function(e) {
			$.ajax({
				url: ajaxurl,
				dataType: 'json',
				type: 'post',
				data: { action: 'wc_ga_ee_update_review_answer', review: 'close' },
			});
		});
		
		$('body').on('click', '.wc-ga-ee-notice-link', function(e) {
			e.preventDefault();
			
			var review = $(this).data('review');
			
			$.ajax({
				url: ajaxurl,
				dataType: 'json',
				type: 'post',
				data: { action: 'wc_ga_ee_update_review_answer', review: review },
			});
	
			if (review === 'good') {
				wc_ga_ee_show_rating();
			}
			else {
				$('.wc-ga-ee-global-message').html('<p>Sorry to hear our plugin isn\'t meeting your needs. Would you mind providing some feedback in the box to the right?</p>');
				
				wc_ga_ee_show_feedback();
			}
			
		});
		
		function wc_ga_ee_show_rating() {
			$('.wc-ga-ee-global-message .notice-dismiss').show().addClass('wc-ga-ee-global-message-dismiss');
			$('.wc-ga-ee-global-message p').html('Would you mind <a class="wc-ga-ee-rating-link" href="https://wordpress.org/support/plugin/woocommerce-google-analytics-enhanced-ecommerce/reviews/#new-post" target="_blank">rating our plugin</a> on WordPress.org?');		
		}
		
		function wc_ga_ee_show_feedback() {
		    Userback.open('form');
		}
		
	})(jQuery);
}