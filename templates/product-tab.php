<?php 



if ( ! defined( 'ABSPATH' ) ) {



	exit; // Exit if accessed directly.



}





if(isset($_POST['savechanges'])){



	update_option('nav_mapped_value',serialize($_POST['mapping']));



	$message='Fields from Nav mapping saved successfully!!';



}







$mapping_fields=array();



	$product = array('post_author','post_date','post_content','post_title','post_excerpt','post_status',



					 'comment_status','ping_status','post_password','post_name','to_ping','pinged',



					 'post_modified','post_content_filtered','guid');



	$mapping_fields['post']=$product;



	$meta=array('sku','downloadable','virtual','price','visibility','stock','stock_status','backorders',



				'manage_stock','sale_price','regular_price','weight','length','width','height',



				'tax_status','tax_class','upsell_ids','crosssell_ids','upsell_skus','crosssell_skus',



				'featured','file_path','file_paths','download_limit','download_expiry','product_url',



				'button_text','vendor_item_no');



	$mapping_fields['meta']=$meta;



	



	$attr=wc_get_attribute_taxonomies();



	$dfattrs=array();



	if(!empty($attr)){



		foreach($attr as $k=>$v){



			$dfattrs[]=$v->attribute_name;



		}



	}



	$mapping_fields['attributes']=$dfattrs;



	$args = array('taxonomy'   => "product_cat",



				  'orderby'    => 'name',



				  'order'      => 0,



				  'hide_empty' => 0,



			);



	$pcates = get_terms($args);



	$cates=array();



	if(!empty($pcates)){



		foreach($pcates as $k=>$vp){



			$cates[$vp->slug]=$vp->name;



		}



	}



	$mapping_fields['categories']=$cates;







	$mappedfields=array('Key'=>'','No'=>'sku','Description'=>'post_name','Type'=>'',



						'Inventory'=>'stock','Created_From_Nonstock_Item'=>'','Substitutes_Exist'=>'',



						'Stockkeeping_Unit_Exists'=>'','Assembly_BOM'=>'','Production_BOM_No'=>'',



						'Routing_No'=>'','Base_Unit_of_Measure'=>'','Shelf_No'=>'','Costing_Method'=>'',



						'Cost_is_Adjusted'=>'','Standard_Cost'=>'',	'Unit_Cost'=>'',



						'Last_Direct_Cost'=>'','Price_Profit_Calculation'=>'','Profit_Percent'=>'',



						'Unit_Price'=>'','Inventory_Posting_Group'=>'','Gen_Prod_Posting_Group'=>'',



						'Item_Disc_Group'=>'','Search_Description'=>'','Overhead_Rate'=>'',



						'Indirect_Cost_Percent'=>'','Blocked'=>'post_status',



						'Last_Date_Modified'=>'post_date','Sales_Unit_of_Measure'=>'',



						'Replenishment_System'=>'','Purch_Unit_of_Measure'=>'',



						'Manufacturing_Policy'=>'','Flushing_Method'=>'','Assembly_Policy'=>'',

						

						'Item_Category_Code'=>'','Number_of_Variants'=>'','On_Eshop'=>'',

						

						'Unit_Price_Including_VAT'=>'sale_price','Vendor_Item_No'=>'vendor_item_no', 'ProductGroupDescription'=>'',

						

						'Title'=>'post_title', 'Details'=>'post_content', 'DivisionDescription'=>'', 'Brand'=>'', 'Brand_Description'=>'',

						

						'Gender'=>'', 'Gender_Description'=>''

					);







	//get xml soap data



	$soapWsdl = get_nav_link_url('_uno_prowsurl');



	



	try {



		$options = [



			'soap_version' => 'SOAP_1_1',



			'connection_timeout' => 120,



			'login' => get_option('_uno_username'),



			'password' => get_option('_uno_password'),



		];







		$client = new SoapClient($soapWsdl, $options);



		$xmlarr = $client->ReadMultiple(['filter' => [], 'setSize' => 1]);



	} catch (Exception $e) {



		echo $e->getMessage();



	}



	



	$item=(isset($xmlarr->ReadMultiple_Result))?current($xmlarr->ReadMultiple_Result):"";



    //show((array) $item);



	//$mappedfields=(array) $item;



	if(isset($_GET['show']) && $_GET['show']=='productmap'){ ?>







	<h2>Products Fields Mapping</h2>



	<form name="productmapping" method="post" >



		<p>Map the fields in Dynamics NAV with woocommerce products. SKU will be used as reference field between both systems.</p>



		<table class="widefat widefat_importer">



			<thead>



				<tr>



					<th>Column Name</th>



					<th>Map To</th>



					<th>Value</th>



					<th>Actions</th>



				</tr>



			</thead>



			<tbody>



			<?php



			$selectlabels=array('post'=>'Product Fields','meta'=>'Product Meta Fields',



								'attributes'=>'Product Attributes','categories'=>'Product Categories'



						  );



			$multiselect=array('attributes');



			if(!empty($item)){



				$i=0;



				foreach($item as $field=>$value){



					$selected=$mappedfields[$field];



					if($selected==''){



						$selected='';



					}



					?>



					<tr>



						<td>



							<label><?php echo $field;?></label>



						</td>



						<td>



							<select class="mapping-wcproduct-fields" data-id="map-<?php echo $field.'-'.$i;?>" >



								<option value="">Do Not Import</option> 



								<?php 



								foreach($mapping_fields as $k=>$v){ 



									echo '<optgroup label="'.$selectlabels[$k].'">';



									foreach($v as $idx=>$mv){



										$mvpf='';



										if($k=='attributes') $mvpf='attribute: ';



										if($k=='meta') $mvpf='meta: ';



										echo '<option value="'.$mv.'" '.($selected==$mv?'selected="selected"':'').'>'.$mvpf.$mv.'</option>';



									}



									echo '</optgroup>';



								}



								?>



							</select>



							<input type="hidden" id="map-<?php echo $field.'-'.$i;?>" name="mapping[<?php echo $field;?>]" value="<?php echo $selected;?>">   



						</td>



						<td><?php echo $value;?></td>



						<td>&nbsp;</td>



					</tr>



					<?php



					$i++;} 



				}



				?>



			</tbody>



		</table>



		<p class="submit">



			<button name="savechanges" class="button-primary woocommerce-save-button" type="submit" value="Save changes">Save Changes</button>



			<input id="_wpnonce" name="_wpnonce" value="3f20ccc046" type="hidden">



			<input name="_wp_http_referer" value="/navdemo/wp-admin/admin.php?page=uno&amp;tab=products" type="hidden">



		</p>







	</form>



	<script type="text/javascript">



	jQuery(document).ready(function($){



		$(document).on('change','.mapping-wcproduct-fields',function(){



			var mapid=$(this).data('id');



			$("#"+mapid).val($(this).val());



		})



	});



	</script>



<?php



}else{



?>



<h2>Products Synchronization</h2>



<p>Synchronize woocommerce products with Dynamics NAV items.</p>



	<button id="import-products" class="button-primary woocommerce-save-button" type="button" >Synchronize Products</button>



	<div id="mapping-before-import" style="display: none;">



		<h2>Products Fields Mapping</h2>



	<form name="productmapping" method="post" >



		<p>Map the fields in Dynamics NAV with woocommerce products. SKU will be used as reference field between both systems.</p>



		<table class="widefat widefat_importer">



			<thead>



				<tr>



					<th>Column Name</th>



					<th>Map To</th>



					<th>Value</th>



					<th>Actions</th>



				</tr>



			</thead>



			<tbody>



			<?php



			$selectlabels=array('post'=>'Product Fields','meta'=>'Product Meta Fields','attributes'=>'Product Attributes','categories'=>'Product Categories');



			$multiselect=array('attributes');



			if(!empty($item)){



				$i=0;



				foreach($item as $field=>$value){


                    if(isset($mappedfields[$field]))
					$selected=$mappedfields[$field];



					if($selected==''){



						$selected='';



					}



					?>



					<tr>



						<td>



							<label><?php echo $field;?></label>



						</td>



						<td>



							<select class="mapping-wcproduct-fields" data-id="map-<?php echo $field.'-'.$i;?>" >



								<option value="">Do Not Import </option> 



								<?php 



								foreach($mapping_fields as $k=>$v){ 



									echo '<optgroup label="'.$selectlabels[$k].'">';



									foreach($v as $idx=>$mv){



										$mvpf='';



										if($k=='attributes') $mvpf='attribute: ';



										if($k=='meta') $mvpf='meta: ';



										echo '<option value="'.$mv.'" '.($selected==$mv?'selected="selected"':'').'>'.$mvpf.$mv.'</option>';



									}



									echo '</optgroup>';







								}



								?>



							</select>



							<input type="hidden" id="map-<?php echo $field.'-'.$i;?>" name="mapping[<?php echo $field;?>]" value="<?php echo $selected;?>">   



						</td>



						<td><?php echo $value;?></td>



						<td>&nbsp;</td>



					</tr>



					<?php



					$i++;} 



				}



				?>



			</tbody>



		</table>



		<p class="submit">



			<input type="hidden" name="startimport" value="1">



			<button name="savechanges" class="button-primary woocommerce-save-button" type="submit" value="Save changes">Import Products</button>



			<input id="_wpnonce" name="_wpnonce" value="3f20ccc046" type="hidden">



			<input name="_wp_http_referer" value="/wp-admin/admin.php?page=uno&amp;tab=products" type="hidden">



		</p>







	</form>



	</div>



<br/><br/>



	<a href="<?php echo admin_url();?>admin.php?page=uno&amp;tab=products&show=productmap">Edit Product Mapping</a>







<div id="import-data-loading" style="display: none;">



		<div class="import-data-message">



			Wait... Fetching data from NAV



		</div>



</div>	



<script type="text/javascript">



	jQuery(document).ready(function($){



		var lastpix=0;



		var barwidth=100;



		$(document).on('click',"#import-products",function(){



			$("#mapping-before-import").show();



		});







		var ifimport='<?php echo (isset($_POST['startimport']) && $_POST['startimport']==1?'1':'0');?>';



		if(ifimport=='1'){



			start_importing_products();;



		}



		$(document).on('click',"#import-products-now",function(){



			start_importing_products();



		})



		function start_importing_products(){



			$("#import-data-loading").show();



			$.post(ajaxurl,{action:'setup_nav_import'},function(r){



				$(".import-data-message").html('<div class="pbar" style="width:0px;">0%</div>');



		   		r=$.parseJSON(r);



		   		var totalrec=r.length;



		   		if(totalrec>0){



					var myQueue = $({});



					$.each(r, function(key, item){



					    myQueue.queue('stack', function(next) {



							setTimeout(function() {



								var rec=get_import_data(item);



				   				var percent=Math.round((key+1)/(totalrec/100));



			   					$(".pbar").css('width',percent+'%');







			   					if(percent==100){



									$(".pbar").html(totalrec+'/'+totalrec+' Products Imported.');



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







	   	// 	var totalrec=r.total;



	   	// 	if(r.total>0){



	   	// 		for(i=0;i<r.total;i++){



	   	// 			lastpix+=(barwidth/r.total);



	   	// 			var percent=Math.round(lastpix/(barwidth/100));



	   	// 			var rec=get_import_data(r.impdate,i);



	   	// 			$(".import-data-message").html('<div class="pbar" style="width:'+percent+'%;">'+percent+'%</div>');



					// }



					// $(".import-data-message").html('<div class="pbar" style="width:100%;">'+totalrec+'/'+totalrec+' Products Imported.</div>');



					// lastpix=0;



	   	// 	}



				



			})



		}



		function get_import_data(item){



			return $.ajax({



			        type: "POST",



			        url: ajaxurl,



			        async: false,



			        data:{action:'import_nav_products',item:item}



		   	 }).responseText;



		}



	});



</script>



<?php



}



?>