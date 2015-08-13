(function($){
	$(document).ready(function(){
		$('input.tzelanmetaboxadd').on('click',function(e){
			e.preventDefault();
			var metaboxid = $("select#product_metabox_tzelan").find(":selected").val();
			var productid = $("input[name=productid]").val();
			if( metaboxid )
				tzelan_update_option( metaboxid, productid );
		});

		function tzelan_update_option( metaboxid, productid ) {
			$('.tzelan_spinner').show(); 
			var data = {
				'action': 'tzelan_ajax_product',
				'metaboxid': metaboxid,
				'productid': productid
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				$('.tzelan_spinner').hide(); 
				$('span.tzelan_check_success').show(); 
				setTimeout(function(){
					$('span.tzelan_check_success').hide(); 
				},2000);
				console.log('Got this from the server: ' + response);
			});
		}
	});
})(jQuery);