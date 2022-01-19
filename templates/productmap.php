<?php 

if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}



if(isset($_POST['savechanges'])){

	update_option('nav_mapped_value',serialize($_POST['mapping']));

	$message='Fields from Nav mapping saved successfully!!';

}

$item=$xmlarr->ReadMultiple_Result->ItemList;

?>

<h2>Map &amp; Import Products </h2>

<p>Please enter ERP Credentials and Endpoint. This is used to communicate with your ERP system.</p>

<form method="post" id="mainform" action="options.php" enctype="multipart/form-data">

<?php 

settings_fields( 'nav-wp-link-group' );

do_settings_sections( 'nav-wp-link-group' );

?>

<input type="hidden" name="_uno_settings" id="_uno_settings" value="1" />

<table class="form-table">

	<tbody>

		<tr valign="top">

			<th scope="row" class="titledesc">

				<label for="_uno_proserviceurl">Product Service Endpoint</label>

			</th>

			<td class="forminp forminp-text">

				<input name="_uno_proserviceurl" id="_uno_proserviceurl" style="" value="<?php echo esc_attr( get_option('_uno_proserviceurl') );?>" class="" placeholder="" type="text">

			</td>

		</tr>

	</tbody>

</table>

</form>

<button id="import-products" class="button-primary woocommerce-save-button" type="button" >Import Products</button>

<form name="productmapping" method="post" >

	<h2>Products Field Mapping</h2>

	<p>Please map the fields in ERP System with woocommerce products. SKU will be used as reference field between both systems.</p>

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

			$selectlabels=array('post'=>'Product Fields','meta'=>'Product Meta Fields','attributes'=>'Product Attributes','categories'=>'Product Categories','newpostmeta'=>'Create New Meta');

			$multiselect=array('attributes');

			if(!empty($item)){

				$i=0;

				foreach($item as $field=>$value){

					$selected=$mappedfields[$field];

					if($selected==''){

						$selected='hidden:_new_post_meta';

					}

					?>

					<tr>

						<td>

							<label><?php echo $field;?></label>

						</td>

						<td>

							<select class="mapping-wcproduct-fields" data-id="map-<?php echo $field.'-'.$i;?>" >

								<option value="">Select Mapping Field</option> 

								<?php 

								foreach($mapping_fields as $k=>$v){ 

									echo '<optgroup label="'.$selectlabels[$k].'">';

									foreach($v as $idx=>$mv){

										echo $selected.'=='.$mv;

										echo '<option value="'.$mv.'" '.($selected==$mv?'selected="selected"':'').'>'.$mv.'</option>';

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

			$(document).on('click',"#import-products",function(){

				$.post(ajaxurl,{action:'import_nav_products'},function(r){

					alert(r);

				})

			})



			$(document).on('change','.mapping-wcproduct-fields',function(){

				var mapid=$(this).data('id');

				$("#"+mapid).val($(this).val());

			})

		});

	</script>