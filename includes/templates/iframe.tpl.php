<iframe
	id="traklight_iframe"
	style="display:none;"
	src="<?= $url1 ?>"
	name="traklight_app"
	sandbox="allow-same-origin allow-forms allow-scripts allow-popups allow-top-navigation"
	seamless>
</iframe>

<script language="javascript">
	var iframe = jQuery('#traklight_iframe');
	var load = true;
	iframe.on("load", function() {
		// Make sure we wait until the iframe src is fully loaded or the cookie won't be set
		if(load) {
			jQuery(this).prop('src', '<?= $url2 ?>');
			jQuery(this).css({'display': 'block', 'height': '100%', 'width': '100%'});
			load = false;
		}
	});
</script>
