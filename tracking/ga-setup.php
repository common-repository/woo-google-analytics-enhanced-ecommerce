<?php
/* if Tracking ID provided in settings
		insert into wp_head 
			if analytics already loaded, do nothing
			if not loaded, run the GA snippet
*/

function wc_ga_ee_tracking_snippet_head() {
	global $wc_ga_ee_tracking_id;
	
	$wc_ga_ee_tracking_snippet = <<<EOD
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o), m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m) })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
	ga('create', '%s', 'auto'); 
	ga('send', 'pageview');
EOD;
	
	$wc_ga_ee_tracking_snippet = sprintf($wc_ga_ee_tracking_snippet, esc_js($wc_ga_ee_tracking_id));
	
	// if analytics is not already loaded, load it
?>
<script>
if(!(window.ga && ga.create)) {
<?php echo $wc_ga_ee_tracking_snippet; ?>
}
</script>
<?php
}

// we want this to be loaded as late as possible in the head, in case another plugin tries to load the GA tracking code
add_action('wp_head', 'wc_ga_ee_tracking_snippet_head', PHP_INT_MAX);