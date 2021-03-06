<?php
function wp_anti_spam_admin(){
	add_options_page('WP Anti Spam Options', 'WP Anti Spam','manage_options', __FILE__, 'wp_anti_spam_options');
	add_action('admin_init','wp_anti_spam_register' );
}
function wp_anti_spam_register(){
	register_setting('was-settings','wp_anti_spam_words');
	register_setting('was-settings','wp_anti_spam_links');
	register_setting('was-settings','wp_anti_spam_cn_must');
	register_setting('was-settings','wp_anti_spam_hiddenfield');
	register_setting('was-settings','wp_anti_spam_gravatar');
	register_setting('was-settings','wp_anti_spam_min');
	register_setting('was-settings','wp_anti_spam_max');
	register_setting('was-settings','wp_anti_spam_nicknames');
	register_setting('was-settings','wp_anti_spam_emails');
	register_setting('was-settings','wp_anti_spam_deactivate');
	register_setting('was-settings','wp_anti_spam_support');
}

function wp_anti_spam_options(){
?>
<div class="wrap">
	
<?php screen_icon(); ?>
<h2>WP Anti Spam</h2>
<div style="text-align:center;"><a href="http://wordpress.org/plugins/wp-anti-spam/" target="_blank">How to use WP Anti Spam ?</a> | <a href="http://boliquan.com/wp-anti-spam/" target="_blank">Homepage</a></div>

<form action="options.php" method="post" enctype="multipart/form-data" name="wp_anti_spam_form">
<?php settings_fields('was-settings'); ?>

<table class="form-table">
	<tr valign="top">
		<th scope="row">
			<?php _e('Banned Words (one per line)','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<textarea type="text" name="wp_anti_spam_words" cols="100" rows="11" style="width:800px;height:180px;font-size:12px;"><?php echo get_option('wp_anti_spam_words'); ?></textarea>
				<br /><?php _e('Comments will be banned contain above words, no matter the words appear in "comment","ip","name","email","url".','WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Disallow Links','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_anti_spam_links" value="yes" <?php if(get_option("wp_anti_spam_links")=='yes') echo 'checked="checked"'; ?> />
				<?php _e("Disallow the comments that contain links in nickname or comment content.",'WP-Anti-Spam'); ?>
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
			<?php _e('Generate Hidden Field','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input type="checkbox" name="wp_anti_spam_hiddenfield" value="yes" <?php if(get_option("wp_anti_spam_hiddenfield")=='yes') echo 'checked="checked"'; ?> />
				<?php _e("Generate an hidden field to anti spambots,but if you open the cache or your browser's Cookie are disabled,please don't check this.",'WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('When No Gravatar','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<input name="wp_anti_spam_gravatar" type="radio" value="no-treatment"<?php if (get_option('wp_anti_spam_gravatar') == 'no-treatment') { ?> checked="checked"<?php } ?> />
				<?php _e('no treatment','WP-Anti-Spam'); ?>
			</label>
			&nbsp;
			<label>
				<input name="wp_anti_spam_gravatar" type="radio" value="mark-it-as-spam"<?php if (get_option('wp_anti_spam_gravatar') == 'mark-it-as-spam') { ?> checked="checked"<?php } ?> />
				<?php _e('mark it as spam','WP-Anti-Spam'); ?>
			</label>
			&nbsp;
			<label>
				<input name="wp_anti_spam_gravatar" type="radio" value="block-it"<?php if (get_option('wp_anti_spam_gravatar') == 'block-it') { ?> checked="checked"<?php } ?> />
				<?php _e('block it','WP-Anti-Spam'); ?>
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Word Limit','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<?php _e('min','WP-Anti-Spam'); ?>
				<input type="text" name="wp_anti_spam_min" value="<?php echo get_option('wp_anti_spam_min'); ?>" size="8" style="width:62px;height:24px;" />
			</label>
			&nbsp;
			<label>
				<?php _e('max','WP-Anti-Spam'); ?>
				<input type="text" name="wp_anti_spam_max" value="<?php echo get_option('wp_anti_spam_max'); ?>" size="8" style="width:62px;height:24px;" />
			</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">
			<?php _e('Stop Posing <br /><small>(separated by commas)</small>','WP-Anti-Spam'); ?>
		</th>
		<td>
			<label>
				<?php _e('protected nicknames','WP-Anti-Spam'); ?>
				<input type="text" name="wp_anti_spam_nicknames" value="<?php echo get_option('wp_anti_spam_nicknames'); ?>" size="30" style="width:200px;height:24px;" />
			</label>
			&nbsp;
			<label>
				<?php _e('protected emails','WP-Anti-Spam'); ?>
				<input type="text" name="wp_anti_spam_emails" value="<?php echo get_option('wp_anti_spam_emails'); ?>" size="30" style="width:200px;height:24px;" />
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

<p class="submit">
<input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

<br />
<?php $donate_url = plugins_url('/img/paypal_32_32.jpg', __FILE__);?>
<?php $paypal_donate_url = plugins_url('/img/paypal_donate_email.jpg', __FILE__);?>
<?php $ali_donate_url = plugins_url('/img/alipay_donate_email.jpg', __FILE__);?>
<div class="icon32"><img src="<?php echo $donate_url; ?>" alt="Donate" /></div>
<h2>Donate</h2>
<p>
If you find my work useful and you want to encourage the development of more free resources, you can do it by donating.
</p>
<p>
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=SCWY6NPFRR8EY" target="_blank"><img src="<?php echo $paypal_donate_url; ?>" alt="Paypal Donate" title="Paypal" /></a>
&nbsp;
<a href="https://www.alipay.com/" target="_blank"><img src="<?php echo $ali_donate_url; ?>" alt="Alipay Donate" title="Alipay" /></a>
</p>
<br />

<?php $blq_logo_url = plugins_url('/img/blq_32_32.jpg', __FILE__);?>
<div class="icon32"><img src="<?php echo $blq_logo_url; ?>" alt="BoLiQuan" /></div>
<h2>Related Links</h2>
<ul style="margin:0 18px;">
<li><a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP Anti Spam (FAQ)</a> | <a href="http://boliquan.com/wp-anti-spam/" target="_blank">Submit Translations</a> | <a href="http://wordpress.org/plugins/wp-anti-spam/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-clean-up/" target="_blank">WP Clean Up</a> | <a href="http://wordpress.org/plugins/wp-clean-up/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-smtp/" target="_blank">WP SMTP</a> | <a href="http://wordpress.org/plugins/wp-smtp/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-code-highlight/" target="_blank">WP Code Highlight</a> | <a href="http://wordpress.org/plugins/wp-code-highlight/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/wp-slug-translate/" target="_blank">WP Slug Translate</a> | <a href="http://wordpress.org/plugins/wp-slug-translate/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/yg-share/" target="_blank">YG Share</a> | <a href="http://wordpress.org/plugins/yg-share/" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/ylife/" target="_blank">YLife</a> | <a href="http://code.google.com/p/ylife/downloads/list" target="_blank">Download</a></li>
<li><a href="http://boliquan.com/" target="_blank">BoLiQuan</a></li>
</ul>

<div style="text-align:center; margin:60px 0 10px 0;">&copy; <?php echo date("Y"); ?> BoLiQuan.COM</div>

</div>
<?php 
}
add_action('admin_menu', 'wp_anti_spam_admin');
?>