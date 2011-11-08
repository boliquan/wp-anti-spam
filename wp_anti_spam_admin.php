<?php
function wp_anti_spam_admin() {
	add_options_page('WP Anti Spam Options', 'WP Anti Spam','manage_options', __FILE__, 'wp_anti_spam_options');
}
function wp_anti_spam_options(){
	add_option('wp_anti_spam_spambots','yes');
	add_option('wp_anti_spam_deactivate','yes');
?>
<div class="wrap">
	
<?php screen_icon(); ?>
<h2>WP Anti Spam</h2>
<div style="text-align:center;"><a href="http://wordpress.org/extend/plugins/wp-anti-spam/" target="_blank">How to use WP Anti Spam ?</a> | <a href="http://boliquan.com/wp-anti-spam/" target="_blank">Homepage</a></div>

<form action="options.php" method="post" enctype="multipart/form-data" name="wp_anti_spam_form">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<?php _e('Banned Words (one per line)','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<textarea type="text" name="wp_anti_spam_words" cols="50" rows="16" style="width:90%;font-size:12px;"><?php echo get_option('wp_anti_spam_words'); ?></textarea>
				<br /><?php _e('Comments will be banned contain above words, no matter the words appear in "comment","ip","name","email","url".','WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Disallow Spambots','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_anti_spam_spambots" value="yes" <?php if(get_option("wp_anti_spam_spambots")=='yes') echo 'checked="checked"'; ?> />
				<?php _e("Disallow automated spambots, but if you open the cache or your browser's Cookie are not enabled, please don't check this.",'WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Some Chinese','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_anti_spam_cn_must" value="yes" <?php if(get_option("wp_anti_spam_cn_must")=='yes') echo 'checked="checked"'; ?> />
				<code><?php _e("Notice: If you do not speak chinese, please don't check this.",'WP-Anti-Spam'); ?></code>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Delete Options','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_anti_spam_deactivate" value="yes" <?php if(get_option("wp_anti_spam_deactivate")=='yes') echo 'checked="checked"'; ?> />
				<?php _e('Delete options while deactivate this plugin.','WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			Support Author
		</th>
		<td>
			<select name="wp_anti_spam_support">
				<option value="yes" <?php if (get_option('wp_anti_spam_support') == 'yes') { echo 'selected="selected"'; } ?>>support author</option>
				<option value="no" <?php if (get_option('wp_anti_spam_support') == 'no') { echo 'selected="selected"'; } ?>>do not support</option>
			</select>
		</td>
	</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="wp_anti_spam_words,wp_anti_spam_spambots,wp_anti_spam_cn_must,wp_anti_spam_deactivate,wp_anti_spam_support" />

<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

<br />
<?php $paypal_url = plugins_url('/img/paypal_32_32.jpg', __FILE__);?>
<?php $blq_donate_url = plugins_url('/img/btn_donateCC_LG.gif', __FILE__);?>
<div class="icon32"><img src="<?php echo $paypal_url; ?>" alt="Paypal" /></div>
<h2>Donate</h2>
<p>
If you find my work useful and you want to encourage the development of more free resources, you can do it by donating.
</p>
<p>
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=SKA6TPPWSATKG&item_name=BoLiQuan&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=CA&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank"><img src="<?php echo $blq_donate_url; ?>" alt="Donate" /></a>
</p>
<br />

<?php $blq_logo_url = plugins_url('/img/blq_32_32.jpg', __FILE__);?>
<div class="icon32"><img src="<?php echo $blq_logo_url; ?>" alt="BoLiQuan" /></div>
<h2>Related Links</h2>
<ul style="margin:0 18px;">
<li><a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP-Anti-Spam FAQ</a> | <a href="http://wordpress.org/extend/plugins/wp-anti-spam/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-code-highlight/" target="_blank">WP Code Highlight</a> | <a href="http://wordpress.org/extend/plugins/wp-code-highlight/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/yg-share/" target="_blank">YG Share</a> | <a href="http://wordpress.org/extend/plugins/yg-share/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/ylife/" target="_blank">YLife</a> | <a href="http://code.google.com/p/ylife/downloads/list" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/" target="_blank">BoLiQuan</a></li>
</ul>

<div style="text-align:center; margin:60px 0 10px 0;">&copy; 2011 BoLiQuan</div>

</div>
<?php 
}
add_action('admin_menu', 'wp_anti_spam_admin');
?>