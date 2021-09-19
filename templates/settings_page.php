<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if (rgpost("uninstall")) {
	check_admin_referer("uninstall", "gf_RayPay_uninstall");
	self::uninstall();
	echo '<div class="updated fade" style="padding:20px;">' . __("درگاه با موفقیت غیرفعال شد و اطلاعات مربوط به آن نیز از بین رفت برای فعالسازی مجدد میتوانید از طریق افزونه های وردپرس اقدام نمایید .", "gravityformsRayPay") . '</div>';

	return;
} else if (isset($_POST["gf_RayPay_submit"])) {

	check_admin_referer("update", "gf_RayPay_update");
	$settings = array(
		"gname" => rgpost('gf_RayPay_gname'),
        "user_id" => rgpost('gf_RayPay_user_id'),
        "marketing_id" => rgpost('gf_RayPay_marketing_id'),
        "sandbox" => rgpost('gf_RayPay_sandbox')
	);
	update_option("gf_RayPay_settings", array_map('sanitize_text_field', $settings));
	if (isset($_POST["gf_RayPay_configured"])) {
		update_option("gf_RayPay_configured", sanitize_text_field($_POST["gf_RayPay_configured"]));
	} else {
		delete_option("gf_RayPay_configured");
	}
} else {
	$settings = get_option("gf_RayPay_settings");
}

if (!empty($_POST)) {
	echo '<div class="updated fade" style="padding:6px">' . __("تنظیمات ذخیره شدند .", "gravityformsRayPay") . '</div>';
} else if (isset($_GET['subview']) && $_GET['subview'] == 'gf_RayPay' && isset($_GET['updated'])) {
	echo '<div class="updated fade" style="padding:6px">' . __("تنظیمات ذخیره شدند .", "gravityformsRayPay") . '</div>';
}
?>

<form action="" method="post">
	<?php wp_nonce_field("update", "gf_RayPay_update") ?>
	<h3>
		<span>
			<i class="fa fa-credit-card"></i>
			<?php _e("تنظیمات RayPay", "gravityformsRayPay") ?>
		</span>
	</h3>
	<table class="form-table">
		<tr>
			<th scope="row"><label
					for="gf_RayPay_configured"><?php _e("فعالسازی", "gravityformsRayPay"); ?></label>
			</th>
			<td>
				<input type="checkbox" name="gf_RayPay_configured"
					   id="gf_RayPay_configured" <?php echo get_option("gf_RayPay_configured") ? "checked='checked'" : "" ?>/>
				<label class="inline"
					   for="gf_RayPay_configured"><?php _e("بله", "gravityformsRayPay"); ?></label>
			</td>
		</tr>
		<?php
		$gateway_title = __("RayPay", "gravityformsRayPay");
		if (sanitize_text_field(rgar($settings, 'gname'))) {
			$gateway_title = sanitize_text_field($settings["gname"]);
		}
		?>
		<tr>
			<th scope="row">
				<label for="gf_RayPay_gname">
					<?php _e("عنوان", "gravityformsRayPay"); ?>
					<?php gform_tooltip('gateway_name') ?>
				</label>
			</th>
			<td>
				<input style="width:350px;" type="text" id="gf_RayPay_gname" name="gf_RayPay_gname"
					   value="<?php echo $gateway_title; ?>"/>
			</td>
		</tr>
        <tr>
            <th scope="row"><label
                        for="gf_RayPay_user_id"><?php _e("شناسه کاربری", "gravityformsRayPay"); ?></label></th>
            <td>
                <input style="width:350px;text-align:left;direction:ltr !important" type="text"
                       id="gf_RayPay_user_id" name="gf_RayPay_user_id"
                       value="<?php echo sanitize_text_field(rgar($settings, 'user_id')) ?>"/>
            </td>
        </tr>
        <tr>
            <th scope="row"><label
                        for="gf_RayPay_marketing_id"><?php _e("شناسه کسب و کار", "gravityformsRayPay"); ?></label></th>
            <td>
                <input style="width:350px;text-align:left;direction:ltr !important" type="text"
                       id="gf_RayPay_marketing_id" name="gf_RayPay_marketing_id"
                       value="<?php echo sanitize_text_field(rgar($settings, 'marketing_id')) ?>"/>
            </td>
        </tr>
        <tr>
            <th scope="row"><label
                        for="gf_RayPay_sandbox"><?php _e("فعالسازی SandBox", "gravityformsRayPay"); ?></label>
            </th>
            <td>
                <input type="checkbox" name="gf_RayPay_sandbox"
                       id="gf_RayPay_sandbox" <?php echo rgar($settings, 'sandbox') ? "checked='checked'" : "" ?>/>
                <label class="inline"
                       for="gf_RayPay_sandbox"><?php _e("بله", "gravityformsRayPay"); ?></label>
            </td>
        </tr>
		<tr>
			<td colspan="2"><input style="font-family:tahoma !important;" type="submit"
								   name="gf_RayPay_submit" class="button-primary"
								   value="<?php _e("ذخیره تنظیمات", "gravityformsRayPay") ?>"/></td>
		</tr>
	</table>
</form>
<form action="" method="post">
	<?php
	wp_nonce_field("uninstall", "gf_RayPay_uninstall");
	if (self::has_access("gravityforms_RayPay_uninstall")) {
		?>
		<div class="hr-divider"></div>
		<div class="delete-alert alert_red">
			<h3>
				<i class="fa fa-exclamation-triangle gf_invalid"></i>
				<?php _e("غیر فعالسازی افزونه درگاه پرداخت RayPay", "gravityformsRayPay"); ?>
			</h3>
			<div
				class="gf_delete_notice"><?php _e("تذکر : بعد از غیرفعالسازی تمامی اطلاعات مربوط به RayPay حذف خواهد شد", "gravityformsRayPay") ?></div>
			<?php
			$uninstall_button = '<input  style="font-family:tahoma !important;" type="submit" name="uninstall" value="' . __("غیر فعال سازی درگاه RayPay", "gravityformsRayPay") . '" class="button" onclick="return confirm(\'' . __("تذکر : بعد از غیرفعالسازی تمامی اطلاعات مربوط به RayPay حذف خواهد شد . آیا همچنان مایل به غیر فعالسازی میباشید؟", "gravityformsRayPay") . '\');"/>';
			echo apply_filters("gform_RayPay_uninstall_button", $uninstall_button);
			?>
		</div>
	<?php } ?>
</form>