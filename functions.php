<?php 
if ( ! defined( 'ABSPATH' ) ) {

	exit; // Exit if accessed directly.

}

function remove_http($url) {
   $disallowed = array('http://', 'https://');
   foreach($disallowed as $d) {
      if(strpos($url, $d) === 0) {
         $url = str_replace($d, '', $url);
         $url = rtrim($url, '/\\');
         return $url;

      }
   }

$url = rtrim($url, '/\\');

   return $url;
}



function checkSlug($slug) {
    global $post, $wpdb;
    $prefix = $wpdb->prefix;
    $table = $prefix.'terms';
    $ipquery= $wpdb->get_results("SELECT * FROM $table WHERE slug = $slug");
    return count($ipquery);
}

function curlRequest(){

	try {
	$curl = curl_init();
	//$domain = "example123.com";
	$domain = site_url();
	$domain = remove_http($domain);
    $code = "fvjxJfs5M0eOakspaiiJn8ufl27S1z4gLhkPaafDA2wAGpQuX6Xrag==";
    $ValidateLicenseKey = get_option('licence_key_uno');
    $url = "https://unolicense.captivix.com/api/ValidateLicenseKey/$ValidateLicenseKey?domain=$domain&code=$code";
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

curl_close($curl);
}

catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}


return $response;

}




function checkLk(){
     $response = array('valid'=>false,'msg'=>'Not valid');
     $licence_key_uno = get_option('licence_key_uno');

			try {
			$curlRequest = curlRequest();
			$curlRequest = json_decode($curlRequest);

			$isKeyValid = (isset($curlRequest->isKeyValid))?$curlRequest->isKeyValid:"";
			$expiryDate = (isset($curlRequest->expiryDate))?$curlRequest->expiryDate:"";


			$date_now = date("m/d/Y");

			$date = date_create($expiryDate);
			$date_convert = date_format($date, "m/d/Y");

			if ($date_now > $date_convert) {
				$date = false;
				} else {
				$date = true;
			}



			if(isset($licence_key_uno) && !empty($licence_key_uno)){
					if($isKeyValid){
						if($date){
							$response['valid'] = true;
							$str = 'TGljZW5zZSBBY3RpdmU=';
							$response['msg'] = base64_decode($str);
						}
						else{
						$str = 'VGhlIGxpY2Vuc2UgaGFzIGV4cGlyZWQgYW5kIGlzIG5vIGxvbmdlciB2YWxpZC4gUGxlYXNlIGNvbnRhY3QgQ2FwdGl2aXggdG8gcmVuZXcgdGhlIGxpY2Vuc2Uu';
						$response['msg'] = base64_decode($str);
						}

					}
					elseif(!$isKeyValid && !empty($expiryDate)){
						if(!$date)
                        $str = 'VGhlIGxpY2Vuc2UgaXMgbm90IHZhbGlkLiBQbGVhc2UgY29udGFjdCBDYXB0aXZpeCB0byBnZXQgYSB2YWxpZCBsaWNlbnNl';
						else
						$str = 'VGhlIGxpY2Vuc2UgaXMgbm90IHZhbGlkIGZvciB0aGlzIGRvbWFpbi4gUGxlYXNlIGNvbnRhY3QgQ2FwdGl2aXggdG8gZ2V0IGEgdmFsaWQgbGljZW5zZQ==';
						$response['msg'] = base64_decode($str);
					}
					else{
						$str = 'VGhlIGxpY2Vuc2UgaXMgbm90IHZhbGlkLiBQbGVhc2UgY29udGFjdCBDYXB0aXZpeCB0byBnZXQgYSB2YWxpZCBsaWNlbnNl';
						$response['msg'] = base64_decode($str);

					}
			}
     else{
	     	$str = 'UGxlYXNlIGFjdGl2YXRlIHlvdXIgbGljZW5zZSBrZXk=';
			$response['msg'] = base64_decode($str);

     }
  
}

//catch exception
catch(Exception $e) {
  echo 'Message: ' .$e->getMessage();
}

  // $response['valid'] = true;
     return $response;

}

function uncheckVariation($postid){

           $temp_array = get_post_meta( $postid, '_product_attributes', true );
           $temp_array['pa_brand']['is_variation'] = 0; // Change pa_size to your attribute name
           $temp_array['pa_gender']['is_variation'] = 0;
           $temp_array['pa_brand']['is_visible'] = 0; // Change pa_size to your attribute name
           $temp_array['pa_gender']['is_visible'] = 0;
           
          update_post_meta( $postid, '_product_attributes', $temp_array );

}


// save product attribute

function saveProductAttr($attvals,$attName,$postid){


$attArr=array_unique($attvals);

				$taxonomy = wc_attribute_taxonomy_name($attName); 		

				$attr_label = ucfirst($attName); 						

				$attr_name = ( wc_sanitize_taxonomy_name($attName)); 
				

			    	

				$product_attributes = array();

				if (!taxonomy_exists($taxonomy))

					save_product_attribute_from_name($attr_name, $attr_label);

					$product_attributes[$taxonomy] = array(

															'name' => $taxonomy,

															'value' => '78',

															'position' => '',

															'is_visible' => 0,

															'is_variation' => 1,

															'is_taxonomy' => 1

														);



				if (is_array($attArr) || is_object($attArr)) {

					foreach ($attArr as $value) {

						$term_name = ucfirst($value);

						$term_slug = sanitize_title($value);

						$rid = term_exists($value, $taxonomy);
						echo " rid ";
						print_r($rid);

						if (!term_exists($value, $taxonomy) && !empty($value) && $value != ","){
							echo " term_name : $term_name , ";
							wp_insert_term($term_name, $taxonomy, array('slug' => $term_slug)); 

							wp_set_post_terms($postid, $term_name, $taxonomy, true);
						}

							

							//echo "<br>attName : $attName , postid : $postid<br>";

					}

				}



				wp_set_object_terms( $postid, 'variable', 'product_type' );


}

// save attribute with code

function saveProductAttrWithCode($attvals,$attName,$postid,$code){

$code = $code;

$attArr=array_unique($attvals);



				$taxonomy = wc_attribute_taxonomy_name($attName); 		

				$attr_label = ucfirst($attName); 						

				$attr_name = ( wc_sanitize_taxonomy_name($attName)); 
				



			    	

				$product_attributes = array();

				if (!taxonomy_exists($taxonomy))

					save_product_attribute_from_name($attr_name, $attr_label);

					$product_attributes[$taxonomy] = array(

															'name' => $taxonomy,

															'value' => '',

															'position' => '',

															'is_visible' => 0,

															'is_variation' => 1,

															'is_taxonomy' => 1

														);



				if (is_array($attArr) || is_object($attArr)) {


					foreach ($attArr as $key => $value) {


						$sname = $code[$key];

						$term_name = ucfirst($value);

                        $checkSlug = checkSlug($sname);



						$term_slug = sanitize_title($value);

						if (!term_exists($sname, $taxonomy) && !empty($value) && $value != "," && $checkSlug < 1){
							//echo " sname : $sname , term_name : $term_name , taxonomy : $taxonomy";
							wp_insert_term($term_name, $taxonomy, array('slug' => $sname)); 

							
						}
						

						if (!term_exists($sname, $taxonomy) && !empty($value) && $value != "," && $sname = "000"){
							//echo " sname : $sname , term_name : $term_name , taxonomy : $taxonomy";
							wp_insert_term($term_name, $taxonomy, array('slug' => $sname)); 

							
						}


						wp_set_post_terms($postid, $sname, $taxonomy, true);

							

							//echo "<br>attName : $attName , postid : $postid<br>";

					}

				}



				wp_set_object_terms( $postid, 'variable', 'product_type' );

				$product = new WC_Product_Variable( $postid );

				$product->save();

}




add_role('wholesaler', __( 'Wholesaler'),array());



function add_uno_menu() {

	add_menu_page('Dynamics NAV Integration', 'Dynamics NAV', 'manage_options', 'uno', 'get_nav_custom_page', UNOLOGO, 100);

}



function get_nav_custom_page() {

	include UNOPATH.'/templates/home-page.php';

}



function uno_daily_update_scheduler() {

	$navp=setup_nav_import_data(1);

	if(!empty($navp)){

		for($i=0;$i<$navp['total'];$i++){

			import_nav_products_function($i,$navp['impdate'],1);

		}

	}

}

function get_user_role() {

    global $current_user;



    $user_roles = $current_user->roles;

    $user_role = array_shift($user_roles);



    return $user_role;

}

function save_product_attribute_from_name($name, $label = '', $set = true) {

	if (!function_exists('get_attribute_id_from_name'))

	return;

	global $wpdb;

	$label = $label == '' ? ucfirst($name) : $label;

	$attribute_id = get_attribute_id_from_name($name);



	if (empty($attribute_id)) {

		$attribute_id = NULL;

	} else {

		$set = false;

	}



	$args = array(

					'attribute_id' => $attribute_id,

					'attribute_name' => $name,

					'attribute_label' => $label,

					'attribute_type' => 'select',

					'attribute_orderby' => 'menu_order',

					'attribute_public' => 0,

				);

	if (empty($attribute_id))

		$wpdb->insert("{$wpdb->prefix}woocommerce_attribute_taxonomies", $args);



	if ($set) {

		$attributes = wc_get_attribute_taxonomies();

		$args['attribute_id'] = get_attribute_id_from_name($name);

		$attributes[] = (object) $args;

		set_transient('wc_attribute_taxonomies', $attributes);

	} else {

		$attributes = wc_get_attribute_taxonomies();

		$args['attribute_id'] = get_attribute_id_from_name($name);

		$attributes[] = (object) $args;

		set_transient('wc_attribute_taxonomies', $attributes);

		return;

	}

}



function get_attribute_id_from_name($name) {

	global $wpdb;

	$attribute_id = $wpdb->get_col("SELECT attribute_id FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE attribute_name LIKE '$name'");

	return reset($attribute_id);

}

function setup_nav_import_data($isdirect=0) {


$checkLicence = checkLk();
		$valid = $checkLicence['valid'];

		if(!$valid)
			return "No access";


	global $wpdb;

	include_once('classes/classNTLMStream.php');

	include_once('classes/classNTLMSoapClient.php');



	$soapWsdl=get_nav_link_url('_uno_prowsurl');

	stream_wrapper_unregister('http');

	stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");

	$service_params = array(

	    'trace' => TRUE,

	    'cache_wsdl' => WSDL_CACHE_NONE,

	);

	$client = new NTLMSoapClient($soapWsdl, $service_params);

	

	try {

		$result = $client->ReadMultiple(['filter' => [], 'setSize' => 0]);

		//$result = $client->ReadMultiple(['filter' => [['Field'=>'Item_Category_Code', 'Criteria'=>"<>''"],['Field'=>'Unit_Price', 'Criteria'=>'>0'],['Field'=>'Inventory', 'Criteria'=>'>0']], 'setSize' => 0]);

		$items=current($result->ReadMultiple_Result);

	} catch (SoapFault $e){

		echo $e->getMessage();

		exit;

	}

	if(!empty($items)){

		echo json_encode($items);

	}else{

		echo 'Nothing found to import.';

	}

	exit;

}



function import_nav_products_function() {

	global $wpdb;



	include_once('classes/classNTLMStream.php');

	include_once('classes/classNTLMSoapClient.php');



	$mapping=unserialize(get_option('nav_mapped_value'));

	$item=$_POST['item'];

	$product=	array(

		'post_type' 	=> 'product',

		'menu_order' 	=> '',

		'post_status'	=> 'publish',

		'post_title'	=> '',

		'post_name'		=> '',

		'post_date'		=> '',

		'post_date_gmt'	=> '',

		'post_content'	=> '',

		'post_excerpt'	=> '',

		'post_parent'	=> '',

		'post_password'	=> '',

		'post_author'   => '',

		'comment_status'=> 'open'

	);

	//post meta defaults

	$product_meta=	array(

		'sku'                         => '',

		'downloadable'                => 'no',

		'virtual'                     => 'no',

		'price'                       => '',

		'visibility'                  => 'visible',

		'stock'                       => 0,

		'stock_status'                => 'instock',

		'backorders'                  => 'no',

		'manage_stock'                => 'yes',

		'sale_price'                  => '',

		'regular_price'               => '',

		'weight'                      => '',

		'length'                      => '',

		'width'                       => '',

		'height'                      => '',

		'tax_status'                  => 'taxable',

		'tax_class'                   => '',

		'upsell_ids'                  => array(),

		'crosssell_ids'               => array(),

		'upsell_skus'                 => array(),

		'crosssell_skus'              => array(),

		'sale_price_dates_from'       => '',

		'sale_price_dates_to'         => '',

		'min_variation_price'         => '',

		'max_variation_price'         => '',

		'min_variation_regular_price' => '',

		'max_variation_regular_price' => '',

		'min_variation_sale_price'    => '',

		'max_variation_sale_price'    => '',

		'featured'                    => 'no',

		'file_path'                   => '',

		'file_paths'                  => '',

		'download_limit'              => '',

		'download_expiry'             => '',

		'product_url'                 => '',

		'button_text'                 => '',
		'vendor_item_no'                 => '',

	);



	if(count($item)){

		$itemArr = (object) $item;

		


		if(($itemArr->On_Eshop == true || $itemArr->On_Eshop == 1)){

			$postid=get_checked_product_existed($itemArr->No);

			$productdata=get_product_filled_data($mapping,$product,$itemArr);

			$productdata['post_name'] = $productdata['post_excerpt'] = " ";


			$productdata['post_status']=($itemArr->Blocked==1?'Draft':'Publish');

			$productmeta=get_product_meta_filled_data($mapping,$product_meta,$itemArr);

      /* echo "productmeta";
       print_r($productmeta);*/


			

			if($postid){


				$productdata['ID']=$postid;

				wp_update_post($productdata);

				$saleprice=get_post_meta($postid,'_regular_price', true);

				$wholsaledisc=$saleprice*20/100;

				$wholsaleprice=$saleprice-$wholsaledisc;

				update_post_meta($postid, '_wholesale_price', $wholsaleprice);

				foreach($productmeta as $meta_key=>$meta_value){

					update_post_meta($postid,$meta_key,$meta_value);

				}

			}else{


				$postid=wp_insert_post($productdata);

				$saleprice=get_post_meta($postid,'_regular_price', true);

				$wholsaledisc=$saleprice*20/100;

				$wholsaleprice=$saleprice-$wholsaledisc;

				update_post_meta($postid, '_wholesale_price', $wholsaleprice);

				foreach($productmeta as $meta_key=>$meta_value){

					update_post_meta($postid,$meta_key,$meta_value);

				}	

			}

          // echo "vendor_item_no == ".$productmeta['vendor_item_no'];
            update_post_meta($postid,'vendor_item_code',$productmeta['vendor_item_no']);

            $mainprice = $productmeta['_BestSalesPrice'];


			//Add Category
/*
			if(isset($itemArr->DivisionDescription)){

				$termids=[];

				$parentcat=$itemArr->DivisionDescription;
				$parentSlug = $itemArr->Item_Category_Code;

				$parent_term = term_exists( $parentcat, 'product_cat' );


				if($parent_term !== 0 && $parent_term !== null){

					$termids[]=$parentcat;

				}else{

					$parent_term = wp_insert_term($parentcat,'product_cat', array('slug' => $parentSlug));

					$termids[]=$parentcat;

				}

				wp_set_object_terms( $postid, $termids, 'product_cat');

			}

			

			//Add Sub-category

			if(isset($itemArr->ProductGroupDescription)){

				$termids=[];

				$parentcat=$itemArr->DivisionDescription;

				$childcat=$itemArr->ProductGroupDescription;

				$child_term = term_exists( $childcat, 'product_cat' );

				$parent_term = term_exists( $parentcat, 'product_cat' );

				$parent_term_id = $parent_term['term_id'];

				if($child_term !== 0 && $child_term !== null){

					$termids[]=$childcat;

				}else{

					//$parentSlug = $itemArr->Item_Category_Code;

					$child_term = wp_insert_term($childcat,'product_cat', array('slug' => $childcat, 'parent' => $parent_term_id));

					$termids[]=$childcat;

				}

				wp_set_object_terms( $postid, $termids, 'product_cat');

			}

*/
//Add Category
//echo $itemArr->DivisionDescription;
//echo $itemArr->Item_Category_Description;


			if(isset($itemArr->DivisionDescription)){


				$termids=[];

				$parentcat=$itemArr->DivisionDescription;
				$parentSlug = $itemArr->Division_Code;

				$parent_term = term_exists( $parentSlug, 'product_cat' );
				


				if($parent_term !== 0 && $parent_term !== null){


					$termids[]=$parentSlug;

				}else{

                  
					$parent_term = wp_insert_term($parentcat,'product_cat', array('slug' => $parentSlug));

					$termids[]=$parentSlug;

				}


				wp_set_object_terms( $postid, $termids, 'product_cat');

			}

			

			//Add Sub-category

			if(isset($itemArr->Item_Category_Description)){


				$termids=[];

				$parentcat=$itemArr->DivisionDescription;
				$parentsalg=$itemArr->Division_Code;

				$childcat=$itemArr->Item_Category_Description;
				$childslag=$itemArr->Item_Category_Code;


				$child_term = term_exists( $childslag, 'product_cat' );

				$parent_term = term_exists( $parentsalg, 'product_cat' );

				$parent_term_id = $parent_term['term_id'];

				if($child_term !== 0 && $child_term !== null){


					$termids[]=$childslag;

				}else{

					//$parentSlug = $itemArr->Item_Category_Code;
                   
					$child_term = wp_insert_term($childcat,'product_cat', array('slug' =>$childslag, 'parent' => $parent_term_id));

					$termids[]=$childslag;

				}


				wp_set_object_terms( $postid, $termids, 'product_cat');

			}
			//Add nested Sub-category 

			if(isset($itemArr->ProductGroupDescription)){


				$termids=[];

				$parentcat=$itemArr->Item_Category_Description;
                 $parentslag=$itemArr->Item_Category_Code;
				$subchildcat=$itemArr->ProductGroupDescription;
				$subchildslag=$itemArr->Product_Group_Code;


				$subchild_term = term_exists( $subchildslag, 'product_cat' );


				$subparent_term = term_exists( $parentslag, 'product_cat' );

				$parent_term_id = $subparent_term['term_id'];
          
				if($subchild_term !== 0 && $subchild_term !== null){


					$termids[]=$subchildslag;

				}else{

					//$parentSlug = $itemArr->Item_Category_Code;

					$subchild_term = wp_insert_term($subchildcat,'product_cat', array('slug' =>$subchildslag, 'parent' => $parent_term_id));

					$termids[]=$subchildslag;

				}



				wp_set_object_terms( $postid, $termids, 'product_cat');

			}


			// check variation and create products attributes

			if(isset($itemArr->Number_of_Variants) && $itemArr->Number_of_Variants>0){

				$soapWsdl1=get_nav_link_url('_uno_prowsvarurl');

				stream_wrapper_unregister('http');

				stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");

				$service_params = array(

				    'trace' => TRUE,

				    'cache_wsdl' => WSDL_CACHE_NONE,

				);

				$client1 = new NTLMSoapClient($soapWsdl1, $service_params);

			

				

				try {

					$result1 = $client1->ReadMultiple(['filter' => [['Field'=>'Item_No', 'Criteria'=>$itemArr->No]], 'setSize' => 0]);

					$items1=current($result1->ReadMultiple_Result);
					
					

				} catch (SoapFault $e){

					echo $e->getMessage();

					exit;

				}

				$attvals= $Size_Description = $Brand_Description = $Gender_Description = $Brand = $Gender = $Size_Code = $Color_Code = [];

				
		      if($itemArr->Brand_Description && !empty($itemArr->Brand_Description))
		      	$Brand_Description[]=$itemArr->Brand_Description;

		       if($itemArr->Gender_Description && !empty($itemArr->Gender_Description))
		      	$Gender_Description[]=$itemArr->Gender_Description;

		       if($itemArr->Brand && !empty($itemArr->Brand))
		      	$Brand[]=$itemArr->Brand;

		       if($itemArr->Gender && !empty($itemArr->Gender))
		      	$Gender[]=$itemArr->Gender;


				if(is_array($items1)){
				foreach ($items1 as $key => $value) {
                     
                     if($value->Color_Description == '200 - Brown'){
                     $brown = str_replace('200 - Brown', '200–Brown', $value->Color_Description);
					 if($value->Color_Description && !empty($value->Color_Description)) $attvals[]=$brown; 
					 } else {
						if($value->Color_Description && !empty($value->Color_Description)) $attvals[]=$value->Color_Description; 
					 }
					
					if($value->Size_Description && !empty($value->Size_Description)) $Size_Description[]=$value->Size_Description;
					if($value->Size && !empty($value->Size)) $Size_Code[]=$value->Size;
					if($value->Color && !empty($value->Color)) $Color_Code[]=$value->Color;


				}
				}
				else{
					 
					   
					 if($items1->Color_Description == '200 – Brown'){
                     $Color_brown = str_replace('200 – Brown', '200–Brown', $items1->Color_Description);
					 if($items1->Color_Description && !empty($items1->Color_Description)) $attvals[]= $Color_brown;
					 } else {
						 if($items1->Color_Description && !empty($items1->Color_Description)) $attvals[]=$items1->Color_Description;
					 }
					
					
					if($items1->Size_Description && !empty($items1->Size_Description)) $Size_Description[]=$items1->Size_Description;
					if($items1->Size && !empty($items1->Size)) $Size_Code[]=$items1->Size;
					if($items1->Color && !empty($items1->Color)) $Color_Code[]=$items1->Color;
				}



                  saveProductAttrWithCode($Size_Description,'size',$postid,$Size_Code);
                  
                  saveProductAttrWithCode($Brand_Description,'brand',$postid,$Brand);
                  saveProductAttrWithCode($attvals,'color',$postid,$Color_Code);
                  saveProductAttrWithCode($Gender_Description,'gender',$postid,$Gender);



				$product = wc_get_product($postid);

          $Size_DescriptionImplod = implode("|",$Size_Description);

          $colorImplod = implode("|",$attArr);

          $brandImplod = implode("|",$Brand);
           $Gender_DescriptionImplod = implode("|",$Gender_Description);
           $Brand_DescriptionImplod = implode("|",$Brand_Description);
           $Size_CodeImplod = implode("|",$Size_Code);
           $Color_CodeImplod = implode("|",$Color_Code);

				//setVariationFun($postid,$itemArr->Unit_Price);



//echo $postid;

$my_product_attributes = array('size' => $Size_DescriptionImplod, 'color' => $colorImplod, 'brand' => $brandImplod, 'gender' => $GenderImplod, 'Gender_Description' => $Gender_DescriptionImplod, 'Brand_Description' => $Brand_DescriptionImplod, 'Size_Code' => $Size_CodeImplod,'Color_Code' => $Color_CodeImplod);

$my_product_attributes_array = array('size' => $Size_Description, 'color' => $attvals, 'brand' => $Brand_Description, 'gender' => $Gender_Description);

$my_product_attributes_array1 = array('size' => $Size_Description, 'color' => $attvals);


foreach($my_product_attributes_array as $key => $link) 
{ 
	
    if(empty($link)) 
    { 
       
        unset($my_product_attributes_array[$key]); 
    } 
} 



				$product->save();
				
			
             $i = 0;
				foreach ($items1 as $varkey => $variablepro) {
					$i++;

                    

				// $attr_id = save_product_attribute_from_name($name, $label = '', $set = true);

					$varsku=$variablepro->No;

              

  					$checkvarid = $wpdb->get_results( "SELECT post_id from $wpdb->postmeta where meta_value = '".$varsku."'", ARRAY_A );

  					if(!empty($checkvarid) && $checkvarid[0]['post_id']!='' && 1==2){

  				

  						$saleprice=$itemArr->Unit_Cost;

						$wholsaledisc=$saleprice*20/100;

						$wholsaleprice=$saleprice-$wholsaledisc;

  						$variation_id = $checkvarid[0]['post_id'];    

  						update_post_meta( $variation_id, '_regular_price', $itemArr->Unit_Price );

  						update_post_meta( $variation_id, '_stock', $itemArr->Inventory );

						update_post_meta($variation_id, '_wholesale_price', $wholsaleprice);

  						wp_update_post( array(

				            'ID' => $variation_id,

				            'post_parent' => $postid,

				            'post_type' => 'product_variation'

				        ));

                
  						$variation_data =  array(

						    
						    'attributes' => array(

						        'color' => 'ZIGZAG',
								'brand_description'  => 'Australian Gold',
        					    'brand' => '035',


						    ),

						);

						

						foreach ($variation_data['attributes'] as $attribute => $term_name ){
                          
							
					        $taxonomy = 'pa_'.$attribute;

					        if( ! taxonomy_exists( $taxonomy ) ){

					            register_taxonomy(

					                $taxonomy,

					               'product_variation',

					                array(

					                    'hierarchical' => false,

					                    'label' => ucfirst( $attribute ),

					                    'query_var' => true,

					                    'rewrite' => array( 'slug' => sanitize_title($attribute) ), 

					                )

					            );

					        }

					        if( ! term_exists( $term_name, $taxonomy ) ){
					        	// woo_insert_term( $term_name, $taxonomy );
					        	  wp_insert_term( $term_name, $taxonomy );
					        }

					            



					        $term_slug = get_term_by('name', $term_name, $taxonomy )->slug; 

					        $post_term_names =  wp_get_post_terms( $postid, $taxonomy, array('fields' => 'names') );

					        if( ! in_array( $term_name, $post_term_names ) )

					            wp_set_post_terms( $postid, $term_name, $taxonomy, true );

                            

					        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );

					    }
 						
					    wp_set_object_terms( $postid, 'variable', 'product_type' );


					}else{



$current_products = $product->get_children();



if(empty($current_products)){

 

                         wp_set_object_terms( $postid, 'variable', 'product_type' );
						$saleprice=$itemArr->Unit_Price;

						$wholsaledisc=$saleprice*20/100;

						$wholsaleprice=$saleprice-$wholsaledisc;

						$variation_post = array(

					        'post_title'  => $product->get_title(),

					        'post_name'   => $itemArr->Description.' - '.$variablepro->Description,

					        'post_status' => 'publish',

					        'post_parent' => $postid,

					        'post_type'   => 'product_variation',

					        'guid'        => $product->get_permalink()

					    );


                       if(!empty($variablepro->Size) || $i < 2)
						$variation_id = wp_insert_post( $variation_post );

					    $variation = new WC_Product_Variation( $variation_id );

                      $mainprice = (isset($variablepro->_x003C_Unit_Price_Including_VAT_x003E_))?$variablepro->_x003C_Unit_Price_Including_VAT_x003E_:$mainprice;
						
					    $variation_data =  array(

						    'attributes' => $my_product_attributes_array,

						    'sku'           => '',

						    'regular_price' => $mainprice,

						    'wholesale_price' => $wholsaleprice,

						    'stock_qty'     => $itemArr->Inventory,

						    'description'	=> $itemArr->Description.' - '.$variablepro->Description,

						);



                         // setVariationCode($postid,$my_product_attributes_array,$itemArr->Unit_Cost,$itemArr->No);

                         $product_attributes_data = array();
						
							foreach ($variation_data['attributes'] as $attribute => $term_name) // Loop round each attribute
							{
								$product_attributes_data['pa_'.$attribute] = array( // Set this attributes array to a key to using the prefix 'pa'
						
									'name'         => 'pa_'.$attribute,
									'value'        => '',
									'is_visible'   => '1',
									'is_variation' => '1',
									'is_taxonomy'  => '1'
						
								);
							}



					

                    update_post_meta($postid, '_product_attributes', $product_attributes_data);
                   


                   saveProductAttrWithCode($Size_Description,'size',$postid,$Size_Code);
                  
                  saveProductAttrWithCode($Brand_Description,'brand',$postid,$Brand);
                  saveProductAttrWithCode($attvals,'color',$postid,$Color_Code);
                  saveProductAttrWithCode($Gender_Description,'gender',$postid,$Gender);

                   uncheckVariation($postid);

						foreach ($variation_data['attributes'] as $attribute => $term_name ){

							//echo "attribute : $attribute , term_name : $term_name , ";

					        $taxonomy = 'pa_'.$attribute;
                          
					        if( ! taxonomy_exists( $taxonomy ) ){

					            register_taxonomy(

					                $taxonomy,

					               'product_variation',

					                array(

					                    'hierarchical' => false,

					                    'label' => ucfirst( $attribute ),

					                    'query_var' => true,

					                    'rewrite' => array( 'slug' => sanitize_title($attribute) ), 

					                )

					            );

					        }

					        /*if( ! term_exists( $term_name, $taxonomy ) )

					            wp_insert_term( $term_name, $taxonomy ); */

 

					         $term_slug = get_term_by('name', $term_name[0], $taxonomy )->slug; 


					        $post_term_names =  wp_get_post_terms( $postid, $taxonomy, array('fields' => 'names') );

					        if($term_name[0] == "200 - Brown")
					        	$term_slug  = "200";
                              
                              //echo ", postid : $postid , taxonomy : $taxonomy , term_slug : $term_slug , term_name : $term_name[0] ,";
                             if($i < 2)
					          update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );
                            

					        /*if( ! in_array( $term_name, $post_term_names ) )

					            wp_set_post_terms( $postid, $term_name, $taxonomy, true );

                           
                            
					        update_post_meta( $variation_id, 'attribute_'.$taxonomy, $term_slug );*/


                           if(!empty($variablepro->Color)){
                           	$clr = strtolower($variablepro->Color);
                           	update_post_meta( $variation_id, 'attribute_pa_color', $clr );
                           //	echo " , variation_id : $variation_id ,  postid : $postid ,";
                           }
                           	

                           //if(!empty($variablepro->Size) || $variablepro->Size=="000")
                           	if(isset($variablepro->Size))
                           	update_post_meta( $variation_id, 'attribute_pa_size', $variablepro->Size );

					      

					    }
                         
						update_post_meta( $variation_id, '_regular_price',  $variation_data['regular_price'] );
						update_post_meta( $variation_id, '_price',  $variation_data['regular_price'] );
						update_post_meta( $variation_id, '_tax_status',  'taxable' );
						update_post_meta( $variation_id, '_tax_class',  'parent' );
						update_post_meta( $variation_id, '_stock_status', 'instock' );
						update_post_meta( $variation_id, '_sku', $variation_data['sku'] );
						update_post_meta( $variation_id, '_stock', 0 );
						update_post_meta( $variation_id, '_manage_stock', 'no' );
					/*	update_post_meta( $variation_id, 'attribute_pa_brand', '' );
						update_post_meta( $variation_id, 'attribute_pa_gender', '' ); 
						 update_post_meta( $variation_id, 'attribute_pa_size', '' );
						update_post_meta( $variation_id, 'attribute_pa_color', '' );*/
						update_post_meta( $variation_id, 'attribute_pa_Color_Code', '' );
						update_post_meta( $variation_id, 'attribute_pa_Size_Code', '' );

						update_post_meta( $variation_id, '_stock', $variablepro->Inventory );
						update_post_meta( $variation_id, '_manage_stock', "yes" );




						


                 
                         
                      
						/*if( ! empty( $variation_data['description'] ) )

					        $variations->set_description( $variation_data['description'] );



					    if( ! empty( $variation_data['sku'] ) )

					        $variations->set_sku( $variation_data['sku'] );*/


                       // echo 'vera'.$variation_id.'-price= '.$variation_data['regular_price'];
					
					   /* update_post_meta( $variation_id, '_wholesale_price', $wholesale_price );
						update_post_meta( $variation_id, '_regular_price',  44 );
						update_post_meta( $variation_id, '_stock_status', 'instock' );*/

                       

					    /*if( ! empty($variation_data['stock_qty']) ){

					        $variation->set_stock_quantity( $variation_data['stock_qty'] );

					        $variation->set_manage_stock(true);

					        $variation->set_stock_status('');

					    } else {

					        $variation->set_manage_stock(false);

					    }*/

					   // $variation->set_weight(''); 

					    $variation->save();

					}
					else{
						if(is_array($current_products)){
							foreach ($current_products as $key => $value) {
								update_post_meta( $value, '_regular_price',  $mainprice );
							}
                          
						}

					}


                 


				}

				}

wp_set_object_terms( $postid, 'variable', 'product_type' );

			}else{

				wp_set_object_terms( $postid, 'simple', 'product_type' );

			}





			$imgurl='http://captivixnav2018.centralus.cloudapp.azure.com:49000/NAVImages/CRO'.$productmeta['_sku'].'-1.jpg';

			AddProdImage($postid, $imgurl);

		}

	}



	if($isdirect){

		return true;

	}else{

		echo 'Products from NAV have been imported successfully.';

	}

	exit;

}



function AddProdImage($post_id, $imgurl){

	$image_name       = wp_basename( $imgurl );

	$upload_dir       = wp_upload_dir();

	$file_headers = @get_headers($imgurl);

	if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') return;

	$image_data       = file_get_contents($imgurl); 

	$unique_file_name = wp_unique_filename( $upload_dir['path'], $image_name ); 

	$filename         = basename( $unique_file_name );



	if( wp_mkdir_p( $upload_dir['path'] ) ) {

	    $file = $upload_dir['path'] . '/' . $filename;

	} else {

	    $file = $upload_dir['basedir'] . '/' . $filename;

	}

	file_put_contents( $file, $image_data );

	$wp_filetype = wp_check_filetype( $filename, null );

	$attachment = array(

	    'post_mime_type' => $wp_filetype['type'],

	    'post_title'     => sanitize_file_name( ucwords($filename) ),

	    'post_content'   => '',

	    'post_status'    => 'inherit'

	);

	$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

	wp_update_attachment_metadata( $attach_id, $attach_data );

	set_post_thumbnail( $post_id, $attach_id );

}



function load_nav_link_custom_css_js($hook) {

	wp_enqueue_script( 'uno-adminjs', UNOURL.'/js/nav-admin-js.js', array(), null );

	wp_enqueue_style( 'uno-admincss', UNOURL.'/css/admin.css', array(), null );

	if($hook != 'toplevel_page_uno') {

		return;

	}

}



function uno_pre_setup() {

	global $wpdb;

	$q="CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}nav_import_data_log` (

		  `id` int(11) NOT NULL AUTO_INCREMENT,

		  `impdate` varchar(64) NOT NULL,

		  `items` text NOT NULL,

		  PRIMARY KEY (`id`)

		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	$wpdb->query($q);

	

	$shopord="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}shpord (

		  id int(11) NOT NULL AUTO_INCREMENT,

		  shpordno varchar(128) NOT NULL,

		  order_no varchar(64) NOT NULL,

		  shipment_method_code varchar(32) NOT NULL,

		  shipping_agent_code varchar(32) NOT NULL,

		  package_tracking_no varchar(64) NOT NULL,

		  shipment_date varchar(64) NOT NULL,

		  added varchar(64) NOT NULL,

		  edited varchar(64) NOT NULL,

		  PRIMARY KEY (id)

		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	$wpdb->query($shopord);

	

	$shoporddtl="CREATE TABLE IF NOT EXISTS {$wpdb->prefix}shporddtl (

		  id int(11) NOT NULL AUTO_INCREMENT,

		  shpord_id int(11) NOT NULL,

		  order_no varchar(32) NOT NULL,

		  no varchar(64) NOT NULL,

		  description varchar(128) NOT NULL,

		  quantity varchar(16) NOT NULL,

		  added varchar(32) NOT NULL,

		  PRIMARY KEY (id)

		) ENGINE=InnoDB DEFAULT CHARSET=latin1";

	$wpdb->query($shoporddtl);



	$cron_recurrence=get_option('_cron_recurrence');

	if (! wp_next_scheduled ( 'nav_link_daily_update' )) {

		wp_schedule_event(time(), $cron_recurrence, 'nav_link_daily_update');

   }

}



function uno_post_uninstall() {

	global $wpdb;

	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}nav_import_data_log");

	wp_clear_scheduled_hook('nav_link_daily_update');

	//remove default options

	$dfoptions=['_uno_baseurl','_uno_compname','_uno_prowsurl','_uno_ordwsurl','_uno_ordwsgeturl','_uno_shipmenturl','_uno_custwsurl','_uno_username','_uno_password','_uno_wscust','_uno_spcode','_uno_products','_uno_orders'];

	foreach($dfoptions as $key=>$option){

		delete_option($option);	

	}

	

}



function register_uno_settings() {

	if(isset($_POST['_uno_settings']) && $_POST['_uno_settings']=='1'){

		foreach ($_POST as $key => $value) {

			if($key=='submit' || $key=='_uno_settings') continue;

			if ( get_option( $key ) !== false ) {

				update_option( $key, $value );

			}else{

				$deprecated = null;

				$autoload = 'no';

				add_option( $key, $value, $deprecated, $autoload );

			}

			register_setting( 'nav-wp-link-group', $key);

		}

	}

}



function get_general_page() {

	echo get_option('_uno_servicesurl');

	include UNOPATH.'/templates/general-tab.php';

	exit;

}



function xml_attribute($object, $attribute) {

	if(isset($object[$attribute]))

		return (string) $object[$attribute];

}



function get_nav_link_url($endpoint='') {

	if($endpoint!=''){

		$baseurl=get_option('_uno_baseurl');

		$company=get_option('_uno_compname');

		$eplink = get_option($endpoint);

		return $baseurl.str_replace("%2C",",",rawurlencode($company)).'/'.$eplink;

	}

}



function get_product_filled_data($mapping,$product,$item) {

	if(!empty($item)){

		foreach($item as $nkey=>$nval){

			$pmapped=$mapping[$nkey];

			if(array_key_exists($pmapped, $product)){

				if($pmapped=='post_name') $nval=ucwords(strtolower($nval));

				$product[$pmapped]=$nval;

			}

			if($pmapped=='post_name'){

				$product['post_name']=ucwords(strtolower($product['post_name']));

				$product['post_title']=ucwords(strtolower($product['post_name']));

				$product['post_content']=$product['post_name'];

				$product['post_excerpt']=$product['post_name'];

			}

		}

		return $product;

	}

}



function get_product_meta_filled_data($mapping,$product_meta,$item) {

	if(!empty($item)){

		foreach($item as $nkey=>$nval){

			$pmapped=$mapping[$nkey];

			if(array_key_exists($pmapped, $product_meta)){

				$product_meta[$pmapped]=$nval;



			}else{

				$product_meta['_'.$nkey]=$nval;

			}

		}

		$product_meta['_regular_price']=$product_meta['sale_price'];

		$product_meta['_sale_price']=$product_meta['sale_price'];

		$product_meta['_price']=$product_meta['sale_price'];

		$product_meta['_sku']=$product_meta['sku'];

		$product_meta['_stock']=$product_meta['stock'];

		$product_meta['_stock_status']=$product_meta['stock_status'];

		$product_meta['_manage_stock']=$product_meta['manage_stock'];

		return $product_meta;

	}

}



function  get_checked_product_existed($sku='') {

	if($sku!=''){

			global $wpdb;

			$q="SELECT post_id FROM {$wpdb->prefix}postmeta WHERE meta_key='_sku' AND meta_value='".$sku."'";

			$res=$wpdb->get_results($q,ARRAY_A);

		if(!empty($res)){

			return $res[0]['post_id'];

		}else{

			return 0;

		}

	}

}



function nav_order_create_complete($orderid) {

	global $wpdb;

	$navcid=get_option('_uno_wscust');

	$navsp=get_option('_uno_spcode');



	$Order=new WC_Order($orderid);

    $orderdata=$Order->get_data();



	include_once('classes/classNTLMStream.php');

	include_once('classes/classNTLMSoapClient.php');



	if ( is_user_logged_in() ) {

		$customer_id=get_current_user_id();

		$navid=get_user_meta( $customer_id, 'navuserid', true );

		//$navid = '01121212';

		if(empty($navid)){

			$cuserrole=get_user_role();

			$CustArr=['name' => $orderdata['billing']['first_name'],

					'name2' => $orderdata['billing']['last_name'],

					'address' => $orderdata['billing']['address_1'],

					'address2' => $orderdata['billing']['address_2'],

					'city' => $orderdata['billing']['city'],

					'state' => $orderdata['billing']['state'],

					'zipCode' => $orderdata['billing']['postcode'],

					'countryRegionCode' => $orderdata['billing']['country'],

					'phoneNo' => $orderdata['billing']['phone'],

					'email' => $orderdata['billing']['email']

					];



		   	$soapWsdl1=get_nav_link_url('_uno_createcust');

		   	stream_wrapper_unregister('http');

			stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");

			$service_params1 = array(

								    'trace' => TRUE,

								    'cache_wsdl' => WSDL_CACHE_NONE,

								);

			$client1 = new NTLMSoapClient($soapWsdl1, $service_params1);

			try {

				if($cuserrole=='customer'){

					$result1 = $client1->CreateB2CCustomer($CustArr);

				}else{

					$result1 = $client1->CreateB2BCustomer($CustArr);

				}

				$navid = $result1->return_value;

				update_user_meta( $customer_id, 'navuserid', $navid);

			} catch (SoapFault $e){

				echo $e->getMessage();

				exit;

			}

		}



	   	$soapWsdl=get_nav_link_url('_uno_ordwsurl');

		stream_wrapper_unregister('http');

		stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocol");

		$service_params = array(

							    'trace' => TRUE,

							    'cache_wsdl' => WSDL_CACHE_NONE,

							);

		$client = new NTLMSoapClient($soapWsdl, $service_params);

		try {

			$result = $client->CreateSalesHeader(['customerNo' => $navid, 'postingDate' => $orderdata['date_created']->date('m-d-Y'),'salesPersonCode'=>$navsp]);

			$NavOrdID = $result->return_value;

			if($NavOrdID){

				$OrdLineItems = $Order->get_items();

				foreach( $OrdLineItems as $key => $OrdLineItem){

					$item_id = $OrdLineItem['product_id'];

					$product = new WC_Product($item_id);

					$item_sku = $product->get_sku();

					$resLine=$client->CreateSalesLine(['salesHeaderNo' => $NavOrdID, 'itemNo' => $item_sku,'quantity'=>$OrdLineItem['quantity'],'lineAmount'=>$OrdLineItem['total'],'lineDiscountPercent'=>0.00]);

				}



				$client->Release(['salesOrderNo'=>$NavOrdID]);

			}



			update_post_meta($orderid,'_nav_order_id',$NavOrdID);

			echo '<script type="text/javascript"> 

				jQuery(".order").html("Order Number:<strong>'.$NavOrdID.'</strong>");	

     		</script>';



		} catch (SoapFault $e){

			echo $e->getMessage();

			exit;

		}



	}   

}



function nav_add_order_no_column( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'order_status' === $column_name ) {

            $new_columns['nav_orderno'] = 'NAV Order#';

            $new_columns['nav_orderstatus'] = 'NAV Order Status';

            $new_columns['nav_action'] = 'Actions';

        }

    }



    return $new_columns;

}



function nav_add_order_no_column_value( $column ) {

    global $post;

    if ( 'nav_orderno' === $column ) {

         echo get_post_meta($post->ID,'_nav_order_id',true);

    }

    if ( 'nav_orderstatus' === $column ) {

         echo $neworderstatus = get_post_meta($post->ID,'_nav_order_status',true);

         //if($neworderstatus=='Released') echo 'Shipped';

    }

    if ( 'nav_action' === $column ) {

         echo '<a class="nav-manuall-sync" href="javascript:void(0);" data-metavalue="'.get_post_meta($post->ID,'_nav_order_id',true).'" data-orderid="'.$post->ID.'">Sync Now</a>';

    }

}



function setup_nav_orders_import_function() {

	global $wpdb;

	$q="SELECT post_id,meta_value FROM {$wpdb->prefix}postmeta WHERE meta_key='_nav_order_id'";

	$res=$wpdb->get_results($q,ARRAY_A);

	echo json_encode($res);

	exit;

}



function update_nav_orders_function() {

	global $wpdb, $woocommerce;

	$wooorder_id=$_POST['post_id'];

	$NAVOrderID=$_POST['meta_value'];



	$Order=new WC_Order($wooorder_id);

       

	include_once('classes/classNTLMStream.php');

	include_once('classes/classNTLMSoapClient.php');

	

	if($_POST['post_id']=='' || $_POST['meta_value']==''){

		exit;

	}

	$value['post_id']=$_POST['post_id'];

	$value['meta_value']=$_POST['meta_value'];

	



	

	$soapWsdl=get_nav_link_url('_uno_ordwsgeturl');

	stream_wrapper_unregister('http');

	stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocoll");

	$service_params = array(

						    'trace' => TRUE,

						    'cache_wsdl' => WSDL_CACHE_NONE,

							);

	$client = new NTLMSoapClient($soapWsdl, $service_params);

	

	try {

		$result = $client->Read(['No' => $value['meta_value']]);

		$items=current($result);



		$newstatus='';

		if($items->Completely_Shipped==1){

			$newstatus='Completely Shipped';

			$Order->update_status('shipped'); 

			nav_order_ship_info($NAVOrderID);

		}elseif($items->Shipped==1){

			$newstatus='Shipped';

			$Order->update_status('shipped'); 

			nav_order_ship_info($NAVOrderID);

		}elseif($items->Shipped_Not_Invoiced==1){

			$newstatus='Shipped';

			$Order->update_status('shipped'); 

			nav_order_ship_info($NAVOrderID);

		}else{

			$newstatus='Released';

			$Order->update_status('processing'); 

			nav_order_ship_info($NAVOrderID);



		}

		update_post_meta($value['post_id'],'_nav_order_status',$newstatus);



	} catch (SoapFault $e){

		echo $e->getMessage();

		exit;

	}

	exit;

}



if(!function_exists('show')){

	function show($arr){

		echo '<pre>';

		print_r($arr);

		echo '</pre>';

	}

}



function change_woocommerce_order_number( $order_id, $order ) {

	$neworderno=get_post_meta($order_id, '_nav_order_id', true);

    return $neworderno;

}



function register_my_new_order_statuses() {

    register_post_status( 'wc-shipped', array(

        'label'                     => _x( 'Shipped', 'Order status', 'woocommerce' ),

        'public'                    => true,

        'exclude_from_search'       => false,

        'show_in_admin_all_list'    => true,

        'show_in_admin_status_list' => true,

        'label_count'               => _n_noop( 'Shipped <span class="count">(%s)</span>', 'Shipped<span class="count">(%s)</span>', 'woocommerce' )

    ) );

}





function my_new_wc_order_statuses( $order_statuses ) {

    $order_statuses['wc-shipped'] = _x( 'Shipped', 'Order status', 'woocommerce' );

    return $order_statuses;

}



function custom_bulk_admin_footer() {

    global $post_type;



    if ( $post_type == 'shop_order' ) {

        ?>

            <script type="text/javascript">

                jQuery(document).ready(function() {

                    jQuery('<option>').val('mark_shipped').text('<?php _e( 'Mark Shipped', 'textdomain' ); ?>').appendTo("select[name='action']");

                    jQuery('<option>').val('mark_shipped').text('<?php _e( 'Mark Shipped', 'textdomain' ); ?>').appendTo("select[name='action2']");   

                });

            </script>

        <?php

    }

}



function save_extra_user_profile_fields( $user_id ) {

    if ( !current_user_can( 'edit_user', $user_id ) ) { 

        return false; 

    }

    update_user_meta( $user_id, 'navuserid', $_POST['navuserid'] );

}



function extra_user_profile_fields( $user ) { ?>

    <h3><?php _e("NAV Information", "blank"); ?></h3>



    <table class="form-table">

    <tr>

        <th><label for="navuserid"><?php _e("NAV ID"); ?></label></th>

        <td>

            <input type="text" name="navuserid" id="navuserid" value="<?php echo esc_attr( get_the_author_meta( 'navuserid', $user->ID ) ); ?>" class="regular-text" /><br />

            <span class="description"><?php _e("Please enter your navuserid."); ?></span>

        </td>

    </tr>

    </table>

<?php }

function nav_order_ship_info($navorder_id){

	global $wpdb, $woocommerce;

	include_once('classes/classNTLMStream.php');

	include_once('classes/classNTLMSoapClient.php');



	$soapWsdl=get_nav_link_url('_uno_shipmenturl');

	stream_wrapper_unregister('http');

	stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocoll");

	$service_params = array(

						    'trace' => TRUE,

						    'cache_wsdl' => WSDL_CACHE_NONE,

							);

	$client = new NTLMSoapClient($soapWsdl, $service_params);

	try {

		$result = $client->ReadMultiple(['filter' => ['Field'=>'Order_No', 'Criteria'=>$navorder_id], 'setSize' => 0]);

		$items=current($result->ReadMultiple_Result);



		$wpdb->query("DELETE FROM {$wpdb->prefix}shpord WHERE order_no={$navorder_id}");

		$wpdb->query("DELETE FROM {$wpdb->prefix}shporddtl WHERE order_no={$navorder_id}");



		if(is_object($items)){

			$tblshpord=$wpdb->prefix.'shpord';

			$shpord_qry=$wpdb->insert( $tblshpord, array('shpordno' => $items->No, 'order_no' => $items->Order_No, 'shipment_method_code' => $items->Shipment_Method_Code, 'shipment_date' => $items->Shipment_Date, 'added' => time(), 'edited' => time()), array('%s', '%s', '%s', '%s', '%s', '%s'));			

			$shpord_insertid=$wpdb->insert_id;

			$LineitemssArr=$items->SalesShipmLines->Mini_Postd_Sales_Shpt_Line;

			if(is_object($LineitemssArr)){

				$tblshporddtl=$wpdb->prefix.'shporddtl';

				$shporddtl_qry=$wpdb->insert($tblshporddtl, array('shpord_id' => $shpord_insertid, 'order_no' => $LineitemssArr->Order_No,  'no' => $LineitemssArr->No,  'description' => $LineitemssArr->Description, 'quantity' => $LineitemssArr->Quantity, 'added' => time()), array('%d', '%s', '%s', '%s', '%s'));

			}else{

				foreach ($LineitemssArr as $key => $Lineitems) {

					$tblshporddtl=$wpdb->prefix.'shporddtl';

					$shporddtl_qry=$wpdb->insert($tblshporddtl, array('shpord_id' => $shpord_insertid, 'order_no' => $Lineitems->Order_No,  'no' => $Lineitems->No,  'description' => $Lineitems->Description, 'quantity' => $Lineitems->Quantity, 'added' => time()), array('%d', '%s', '%s', '%s', '%s'));

				}

			}

		}else{

			foreach ($items as $item) {

				$tblshpord=$wpdb->prefix.'shpord';

				$shpord_qry=$wpdb->insert( $tblshpord, array('shpordno' => $item->No, 'order_no' => $item->Order_No, 'shipment_method_code' => $item->Shipment_Method_Code, 'shipment_date' => $item->Shipment_Date, 'added' => time(), 'edited' => time()), array('%s', '%s', '%s', '%s', '%s', '%s'));			

				$shpord_insertid=$wpdb->insert_id;

				$LineitemsArr=$item->SalesShipmLines->Mini_Postd_Sales_Shpt_Line;

				if(is_object($LineitemssArr)){

					$tblshporddtl=$wpdb->prefix.'shporddtl';

					$shporddtl_qry=$wpdb->insert($tblshporddtl, array('shpord_id' => $shpord_insertid, 'order_no' => $LineitemssArr->Order_No,  'no' => $LineitemssArr->No,  'description' => $LineitemssArr->Description, 'quantity' => $LineitemssArr->Quantity, 'added' => time()), array('%d', '%s', '%s', '%s', '%s'));

				}else{

					foreach ($LineitemssArr as $key => $Lineitems) {

						$tblshporddtl=$wpdb->prefix.'shporddtl';

						$shporddtl_qry=$wpdb->insert($tblshporddtl, array('shpord_id' => $shpord_insertid, 'order_no' => $Lineitems->Order_No,  'no' => $Lineitems->No,  'description' => $Lineitems->Description, 'quantity' => $Lineitems->Quantity, 'added' => time()), array('%d', '%s', '%s', '%s', '%s'));

					}

				}

			}

		}

		

		

	} catch (SoapFault $e){

		echo $e->getMessage();

	}

}



function nav_view_order_function( $order_id ){ 

	global $wpdb, $woocommerce;

	$Order=new WC_Order($order_id);

	$NAVOrderID=get_post_meta( $order_id, '_nav_order_id', true );

    $newstatus =get_post_meta($order_id,'_nav_order_status',true);



    if($newstatus!='Completely Shipped'){

    	include_once('classes/classNTLMStream.php');

		include_once('classes/classNTLMSoapClient.php');



		$soapWsdl=get_nav_link_url('_uno_ordwsgeturl');



		stream_wrapper_unregister('http');

		stream_wrapper_register('http', 'NTLMStream') or die("Failed to register protocoll");

		$service_params = array(

							    'trace' => TRUE,

							    'cache_wsdl' => WSDL_CACHE_NONE,

								);

		$client = new NTLMSoapClient($soapWsdl, $service_params);

		try {

			$result = $client->Read(['No' => $NAVOrderID]);

			$items=current($result);

			$newstatus='';

			if($items->Completely_Shipped==1){

				$newstatus='Completely Shipped';

				$Order->update_status('shipped');

				nav_order_ship_info($NAVOrderID);

			}elseif($items->Shipped==1){

				$newstatus='Shipped';

				$Order->update_status('shipped');

				nav_order_ship_info($NAVOrderID);

			}elseif($items->Shipped_Not_Invoiced==1){

				$newstatus='Shipped';

				$Order->update_status('shipped'); 

			}else{

				$newstatus='Released';

				$Order->update_status('processing'); 

			}

			update_post_meta($order_id,'_nav_order_status',$newstatus);

		} catch (SoapFault $e){

			echo $e->getMessage();

		}

    }else{

        $Order->update_status('processing'); 

    }

    if($newstatus=='Completely Shipped' || $newstatus=='Shipped'){

    	echo '<h2>Shipment Detail</h2>';

    	$q="SELECT * FROM {$wpdb->prefix}shpord WHERE order_no='".$NAVOrderID."'";

		$res=$wpdb->get_results($q,ARRAY_A);

		if(!empty($res)){

			foreach ($res as $key => $value) {

				echo '<table class="woocommerce-table shop_table gift_info">';

				echo '<thead>';

				echo '<tr>';

				echo '<th>PRODUCT</th>';

				echo '<th>SKU</th>';

				echo '<th>QTY SHIPPED</th>';

				echo '<th>SHIPPING DATE</th>';

				echo '<th>TRACKING#</th>';

				echo '</tr>';

				echo '</thead>';

				$qdtl="SELECT * FROM {$wpdb->prefix}shporddtl WHERE order_no='".$NAVOrderID."'";

				$resdtl=$wpdb->get_results($qdtl,ARRAY_A);

				if(!empty($resdtl)){

					echo '<tbody>';

					foreach ($resdtl as $keydtl => $valuedtl) {

						echo '<tr>';

						echo '<td>'.$valuedtl['description'].'</td>';

						echo '<td>'.$valuedtl['no'].'</td>';

						echo '<td>'.$valuedtl['quantity'].'</td>';

						echo '<td>'.$value['shipment_date'].'</td>';

						echo '<td>&nbsp;</td>';

						echo '</tr>';

					}

					echo '</tbody>';

				}

				echo '</table>';

			}

		}

	}

}



 

function uno_paypal_enable_manager( $available_gateways ) {

	if ( get_user_role()=='wholesaler' )  unset( $available_gateways['cod'] );

	return $available_gateways;

}



// Add "Wholesale Price" custom field to Products option pricing

add_action( 'woocommerce_product_options_pricing', 'uno_add_product_options_pricing' );

function uno_add_product_options_pricing()

{

    woocommerce_wp_text_input( array(

        'id' => '_wholesale_price',

        'class' => 'wc_input_wholesale_price short',

        'label' => __( 'Wholesale Price', 'woocommerce' ) . ' ('.get_woocommerce_currency_symbol().')',

        'type' => 'text'

    ));

}



// Add custom field to VARIATIONS option


add_action( 'woocommerce_product_after_variable_attributes', 'variation_settings_fields', 10, 3 );

add_action( 'woocommerce_save_product_variation', 'save_variation_settings_fields', 10, 2 );

function variation_settings_fields( $loop, $variation_data, $variation ) {
    woocommerce_wp_text_input( 
        array( 
            'id'          => 'price_1_50[' . $variation->ID . ']',
            'class'       => 'price_1_50',
            'type'        => 'text',  
            'label'       => __( 'Price | Product amount: 1-49', 'woocommerce' ), 
            'placeholder' => '',
            'desc_tip'    => 'true',
            'description' => __( 'Enter price for product amount 1-49 (if available)', 'woocommerce' ),
            'value'       => get_post_meta( $variation->ID, 'price_1_50', true )
        )
    );
}

// End VARIATIONS option


add_action( 'woocommerce_variation_options_pricing', 'uno_add_variation_options_pricing', 20, 3 );

function uno_add_variation_options_pricing( $loop, $variation_data, $post_variation )

{

    $value  = get_post_meta( $post_variation->ID, 'wholesale_price', true );

    $symbol = ' (' . get_woocommerce_currency_symbol() . ')';

    $key = 'wholesale_price[' . $loop . ']';



    echo '<div class="variable_wholesale-price"><p class="form-row form-row-first">

        <label>' . __( "Wholesale Price", "woocommerce" ) . $symbol . '</label>

        <input type="text" size="5" name="' . $key .'" value="' . esc_attr( $value ) . '" />

    </p></div>';

}



// Save "Wholesale Price" custom field to Products

add_action( 'woocommerce_process_product_meta_simple', 'uno_save_product_wholesale_price', 20, 1 );

function uno_save_product_wholesale_price( $product_id ) {

    if( isset($_POST['_wholesale_price']) )

        update_post_meta( $product_id, '_wholesale_price', $_POST['_wholesale_price'] );

}



// Save "Wholesale Price" custom field to VARIATIONS

add_action( 'woocommerce_save_product_variation', 'uno_save_product_variation_wholesale_price', 20, 2 );

function uno_save_product_variation_wholesale_price( $variation_id, $i ){

    if ( isset( $_POST['wholesale_price'][$i] ) ) {

        update_post_meta( $variation_id, '_ragular_price', floatval( $_POST['wholesale_price'][$i] ) );

    }

}





add_filter('woocommerce_product_get_price', 'custom_price', 99, 2 );

add_filter('woocommerce_product_get_regular_price', 'custom_price', 99, 2 );

// Variations 

add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2 );

add_filter('woocommerce_product_variation_get_price', 'custom_price', 99, 2 );



// Variable (price range)

add_filter('woocommerce_variation_prices_price', 'custom_variable_price', 99, 3 );

add_filter('woocommerce_variation_prices_regular_price', 'custom_variable_price', 99, 3 );



function get_price_multiplier() {

    return 0.8; 

}



function custom_price( $price, $product ) {

	wc_delete_product_transients( $product->get_id() );

	if(get_user_role()=='wholesaler'){

		return $price * get_price_multiplier();

	}else{

		return $price;

	}

}



function custom_variable_price( $price, $variation, $product ) {

	wc_delete_product_transients( $product->get_id() );

    if(get_user_role()=='wholesaler'){

		return $price * get_price_multiplier();

	}else{

		return $price;

	}

}







function add_shipping_meta_box(){

  //  add_meta_box("shipping-meta-box", "Shipment Details", "shipping_meta_box_markup", "shop_order", "normal", "default", null);

}

function shipping_meta_box_markup(){

    global $wpdb, $woocommerce, $post;

	$order_id=$post->ID;

	$Order=new WC_Order($order_id);

	//nav_view_order_function($order_id);

	$NAVOrderID=get_post_meta( $order_id, '_nav_order_id', true );

    $currstatus =get_post_meta($order_id,'_nav_order_status',true);

    $q="SELECT * FROM {$wpdb->prefix}shpord WHERE order_no='".$NAVOrderID."'";

	$res=$wpdb->get_results($q,ARRAY_A);

	if(!empty($res)){

		foreach ($res as $key => $value) {

			echo '<table class="wp-order-ship-items" cellspacing="0" cellpadding="0">';

			echo '<thead>';

			echo '<tr>';

			echo '<th>PRODUCT</th>';

			echo '<th>SKU</th>';

			echo '<th>QTY SHIPPED</th>';

			echo '<th>SHIPPING DATE</th>';

			echo '<th>TRACKING#</th>';

			echo '</tr>';

			echo '</thead>';

			$qdtl="SELECT * FROM {$wpdb->prefix}shporddtl WHERE order_no='".$NAVOrderID."'";

			$resdtl=$wpdb->get_results($qdtl,ARRAY_A);

			if(!empty($resdtl)){

				echo '<tbody>';

				foreach ($resdtl as $keydtl => $valuedtl) {

					echo '<tr>';

					echo '<td>'.$valuedtl['description'].'</td>';

					echo '<td>'.$valuedtl['no'].'</td>';

					echo '<td>'.$valuedtl['quantity'].'</td>';

					echo '<td>'.$value['shipment_date'].'</td>';

					echo '<td>'.$value['package_tracking_no'].'</td>';

					echo '</tr>';

				}

				echo '</tbody>';

			}

			echo '</table>';

		}

	}else{

	    echo 'Order has not been shipped.';

	}

}

?>