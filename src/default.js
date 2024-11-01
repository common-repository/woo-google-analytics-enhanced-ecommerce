jQuery.fn.wc_ga_ee_in_viewport = function() {
	var elementTop = jQuery(this).offset().top;
	var elementBottom = elementTop + jQuery(this).outerHeight();
	var viewportTop = jQuery(window).scrollTop();
	var viewportBottom = viewportTop + jQuery(window).height();
	return elementBottom > viewportTop && elementTop < viewportBottom;
};

Array.prototype.indexOf||(Array.prototype.indexOf=function(i,e){var r=this.length>>>0;if((e|=0)<0)e=Math.max(r-e,0);else if(e>=r)return-1;if(void 0===i){do{if(e in this&&void 0===this[e])return e}while(++e!==r)}else do{if(this[e]===i)return e}while(++e!==r);return-1});

(function($) {
	var wc_ga_ee_impression_sent = new Array();
	
	/* impression tracking */
	if ($('.wc-ga-ee-impression').length)
		ga('require', 'ec');
	
	function wc_ga_ee_impression_check() {
		var hasImpressions = false;
		var trackList = '';
		
		$('.wc-ga-ee-impression').each(function() {
			
			if ($(this).wc_ga_ee_in_viewport()) {
				
				//  Track item index so it isn't fired again in page view
				if ( wc_ga_ee_impression_sent.indexOf($(this).data('id')) !== -1)
					return;

				hasImpressions = true;
				
				wc_ga_ee_impression_sent.push($(this).data('id'));

				//  Add items to impression array 				
				var id = $(this).data('id'), name = $(this).data('name'), cat = $(this).data('cat'), 
					list = $(this).data('list'), position = $(this).parent().index() + 1;
				
				trackList = list;
				
				//  addImpression
				ga('ec:addImpression', {
					'id': id,
					'name': name,
					'category': cat,
					'list': list,
					'position': position
				});				
			}
			
		});
		
		//  fire "impression" event 		
		if (hasImpressions) { 
			ga('send', 'event', 'Ecommerce', 'impression', trackList + ' - impression', {'nonInteraction': true});			
		}
		
				
	}
	$(window).on('load resize scroll', function() {
		wc_ga_ee_impression_check();
	});
	
	
	/* product click tracking */
	$('body').on('click', '.woocommerce-loop-product__link, .woocommerce-loop-product__link ~ .add_to_cart_button', function(e) {
		e.preventDefault();
		
		var href = $(this).attr('href');
		var $dataTag = $(this).parents('.product').find('.wc-ga-ee-impression');
		
		ga('ec:addProduct', {
			'id': $dataTag.data('id'),
			'name': $dataTag.data('name'),
			'category': $dataTag.data('cat'),
			'position': $(this).parents('.product').index() + 1
		});
		ga('ec:setAction', 'click', {list: $dataTag.data('list')});

		ga('send', 'event', 'Ecommerce', 'click', $dataTag.data('list') + ' - click', {
			hitCallback: function() {
			  document.location = href;
			},
			nonInteraction: false
		});

		setTimeout(function() {
			location.href = href;  // in case event tracking fails
		}, 2500);
		
	});

	
	
	/* add to cart tracking */
	$('body').on('click', '.ajax_add_to_cart', function() {
		
		ga('require', 'ec');
		$_button = $(this);
		
		// tracking can't be called until $(this) also has a class of "added"
		// so we need to set an interval to check and wait for the "added" class, then fire the ajax call below
		
		var wc_ga_ee_interval = setInterval(function() {
			if ($_button.hasClass('added'))
				wc_ga_ee_track();
		}, 100);
		
		function wc_ga_ee_track() {
						
			$.ajax({
				url: woocommerce_params.ajax_url,
				dataType: 'json',
				type: 'post',
				data: { action: 'wc_ga_ee_ajax_get_cart_info' },
				success: function(response) {
					var data = {
						'id': response['product_id'],
						'name': response['name'],
						'category': response['category'],
						'variant': response['variation'],
						'price': response['price'],
						'quantity' : $(this).data('quantity')
					};
					ga('ec:addProduct', data);
					ga('ec:setAction', 'add');
					ga('send', 'event', { eventCategory: 'Ecommerce', eventAction: 'add', eventLabel: 'category - archive add', nonInteraction: false});
				}
			});
			
			clearInterval(wc_ga_ee_interval); 
			
		}
				
	});
	
	/* remove from cart tracking */
	$('body').on('click', '.woocommerce-cart-form .remove', function() {
		
		ga('require', 'ec');
		$item = $(this).parents('.cart_item');
		
		var product_id = $(this).data('product_sku');
		if (!product_id) product_id = $(this).data('product_id');
		var name = '';
		var price = '';
		var quantity = '';
		
		if ($item.find('.product-name')) name = $item.find('.product-name a').text();
		if ($item.find('.product-price')) price = $item.find('.product-price .woocommerce-Price-amount').text().replace($item.find('.product-price .woocommerce-Price-currencySymbol').text(), '');
		if ($item.find('.product-quantity')) quantity = $item.find('.product-quantity input').val();
		
		var data = {
			'id': product_id,
			'name': name,
			'price': price,
			'quantity' : quantity
		};
		
		ga('ec:addProduct', data);
		ga('ec:setAction', 'remove');
		ga('send', 'event', { eventCategory: 'Ecommerce', eventAction: 'remove', eventLabel: 'cart item removed', nonInteraction: false});
				
	});	
	
})(jQuery);