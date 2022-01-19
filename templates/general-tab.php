<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
settings_errors();
?>
<p>Configure plugin to connect with your Microsoft Dynamics NAV installation.</p>
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
				<label for="_uno_baseurl">Integration Status</label>
			</th>
			<td class="forminp forminp-text">
				<fieldset>
                    <p>
                        <label>
                            <input value="live" name="_uno_status[]" checked="checked" type="radio"> Real Time
                        </label>
                        <label style="margin-left:12px!important;">
                            <input value="stage" name="_uno_status[]" type="radio"> Schedule
                        </label>
                    </p>
                </fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_baseurl">Dynamics NAV Address (URL) <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_baseurl" id="_uno_baseurl" style="" value="<?php echo esc_attr( get_option('_uno_baseurl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_compname">Company Name on NAV <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_compname" id="_uno_compname" style="" value="<?php echo esc_attr( get_option('_uno_compname') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_prowsurl">Products WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_prowsurl" id="_uno_prowsurl" style="" value="<?php echo esc_attr( get_option('_uno_prowsurl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_prowsvarurl">Products Variations WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_prowsvarurl" id="_uno_prowsvarurl" style="" value="<?php echo esc_attr( get_option('_uno_prowsvarurl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_ordwsurl">Create Orders WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_ordwsurl" id="_uno_ordwsurl" style="" value="<?php echo esc_attr( get_option('_uno_ordwsurl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_ordwsgeturl">Get Orders WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_ordwsgeturl" id="_uno_ordwsgeturl" style="" value="<?php echo esc_attr( get_option('_uno_ordwsgeturl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_shipmenturl">Get Shipment WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_shipmenturl" id="_uno_shipmenturl" style="" value="<?php echo esc_attr( get_option('_uno_shipmenturl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_custwsurl">Customers WebService <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_custwsurl" id="_uno_custwsurl" style="" value="<?php echo esc_attr( get_option('_uno_custwsurl') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_createcust">Create Customer <i>(required)</i></label>
			</th>
			<td class="forminp forminp-text">
				<input name="_uno_createcust" id="_uno_createcust" style="" value="<?php echo esc_attr( get_option('_uno_createcust') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_username">Username <i>(required)</i></label>							</th>
			<td class="forminp forminp-text">
				<input name="_uno_username" id="_uno_username" style="" value="<?php echo esc_attr( get_option('_uno_username') );?>" class="" placeholder="" type="text">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_password">Password <i>(required)</i></label>							</th>
			<td class="forminp forminp-text">
				<input name="_uno_password" id="_uno_password" style="" value="<?php echo esc_attr( get_option('_uno_password') );?>" class="" placeholder="" type="password">
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_spcode">Nav Web Sales Person Code</label>							</th>
			<td class="forminp forminp-text">
				<input name="_uno_spcode" id="_uno_spcode" style="" value="<?php echo esc_attr( get_option('_uno_spcode') );?>" class="" placeholder="" type="text">
			</td>
		</tr>  
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_password">Integration Required for</label>
			</th>
			<td class="forminp forminp-text">
				<fieldset>
					<legend class="screen-reader-text"><span>Integrate Products</span></legend>
					<label for="_uno_products">
						<input name="_uno_products" id="_uno_products" class="" value="1" type="checkbox" <?php echo (esc_attr( get_option('_uno_products') )=='1'?'checked="checked"':'');?>> Products
					</label>
				</fieldset>
				<fieldset>
					<legend class="screen-reader-text"><span>Integrate Orders</span></legend>
					<label for="_uno_orders">
						<input name="_uno_orders" id="_uno_orders" class="" value="1" type="checkbox" <?php echo (esc_attr( get_option('_uno_orders') )=='1'?'checked="checked"':'');?>> Orders
					</label>
				</fieldset>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="_uno_wscust">Cron Recurrence</label></th>
			<td class="forminp forminp-text">
			<select name="_uno_cron" id="_uno_cron">
			<option value="">Select Cron Recurrence</option>
			<option value="hourly" <?php echo esc_attr( get_option('_uno_cron')=='hourly'?'selected="selected"':'' );?>>hourly</option>
			<option value="twicedaily" <?php echo esc_attr( get_option('_uno_cron')=='twicedaily'?'selected="selected"':'' );?>>twicedaily</option>
			<option value="daily" <?php echo esc_attr( get_option('_uno_cron')=='daily'?'selected="selected"':'' );?>>daily</option>
			</select>
			</td>
		</tr>
	</tbody>
</table>
<?php submit_button(); ?>
</form>