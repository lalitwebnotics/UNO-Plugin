//admin js for nav plugin
jQuery(document).ready(function($){
	$(document).on('click','.nav-manuall-sync',function(){
			var post_id=$(this).data('orderid');
			var meta_value=$(this).data('metavalue');
			$.post(ajaxurl,{action:'update_nav_orders',post_id:post_id,meta_value:meta_value},function(){
				alert('Order has been synced successfully');
				window.location.reload(true);

			});
	})

});
