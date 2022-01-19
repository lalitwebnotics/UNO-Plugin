<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<h2>Orders Synchronization</h2>
<p>Synchronize woocommerce orders with Dynamics NAV Sales Orders.</p>
	<button id="import-orders" class="button-primary woocommerce-save-button" type="button">Synchronize Orders</button>
<div id="import-data-loading" style="display: none;">
		<div class="import-data-message">&nbsp;</div>
</div>
<script type="text/javascript">
	jQuery(document).ready(function($){
		var lastpix=0;
		var barwidth=100;
		$(document).on('click',"#import-orders",function(){
			$("#import-data-loading").show();
			$.post(ajaxurl,{action:'setup_nav_orders_import'},function(r){
				$(".import-data-message").html('<div class="pbar" style="width:'+lastpix+'px;">0%</div>');
		   		r=$.parseJSON(r);
		   		var totalrec=r.length;
		   		if(totalrec>0){
					var myQueue = $({});
					$.each(r, function(key, item){
					    myQueue.queue('stack', function(next) {
							setTimeout(function() {
								var rec=update_import_data(item.post_id,item.meta_value);
				   				lastpix+=(barwidth/totalrec);
				   				var percent=Math.round((key+1)/(totalrec/100));
			   					$(".pbar").css('width',percent+'%');

			   					if(percent==100){
									$(".pbar").html(totalrec+'/'+totalrec+' Orders Updated.');
			   					}else{
			   						$(".pbar").html(percent+'%');	
			   					}
			   					next();
							}, 10);

					   })
					});
					myQueue.dequeue('stack');
					lastpix=0;
		   		}
			})
		})
		
		function update_import_data(post_id,meta_value){
			return $.ajax({
			        type: "POST",
			        url: ajaxurl,
			        async: false,
			        data:{action:'update_nav_orders',post_id:post_id,meta_value:meta_value}
		   	 }).responseText;
		}
	});
</script>		