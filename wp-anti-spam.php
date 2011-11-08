<?php
/*
Plugin Name: WP Anti Spam
Plugin URI: http://boliquan.com/wp-anti-spam/
Description: WP Anti Spam can anti automated spambots, it can also anti artificial spams by"comment","ip","name","email","url". If you speak chinese, you can check 'Some Chinese' to anti the spams from other languages. Besides,it can delete its own options, so it is a green plugin ! 
Version: 1.2.0
Author: BoLiQuan
Author URI: http://boliquan.com/
Text Domain: WP-Anti-Spam
Domain Path: /lang
*/

function load_wp_anti_spam_lang() {
		$currentLocale = get_locale();
		if(!empty($currentLocale)) {
			$moFile = dirname(__FILE__) . "/lang/wp-anti-spam-" . $currentLocale . ".mo";
			if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('WP-Anti-Spam', $moFile);
		}
}
add_filter('init','load_wp_anti_spam_lang');

$wp_anti_spam_words = array();
if(!defined('WASINFO')) define('WASINFO','(Protected by <a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP Anti Spam</a>)');

function wp_anti_spam($comment_data){
	
	$wp_anti_spam_words = explode("\r\n", get_option("wp_anti_spam_words"));
	$comment_fields = array('author','email','url','comment');

	foreach($wp_anti_spam_words as $wp_anti_spam_word)
	foreach($comment_fields as $comment_field)
	if( $wp_anti_spam_word != '' && isset($_POST[$comment_field]) ){
		if(eregi($wp_anti_spam_word, $_POST[$comment_field]) !== false) {
 			wp_die(__('Error: The information you submit contains banned words.','WP-Anti-Spam').WASINFO);
		}
	}

	$visitor_ip = (@getenv("REMOTE_ADDR")) ? getenv("REMOTE_ADDR") : $_SERVER['REMOTE_ADDR'];
	if(in_array($visitor_ip,$wp_anti_spam_words)){
		wp_die(__('Error: Your IP has been banned to leave comments.','WP-Anti-Spam').WASINFO);
	}

	if(get_option("wp_anti_spam_cn_must")=='yes'){
		$cn_char = '/[一-龥]/u';
		if(!preg_match($cn_char, $comment_data['comment_content'])) {
			wp_die(__('Error: Comments must contain Chinese.','WP-Anti-Spam').WASINFO);
		}
	}

	return($comment_data);
}

add_filter('preprocess_comment','wp_anti_spam');
?>
<?php if(is_admin()){require_once('wp_anti_spam_admin.php');} ?>
<?php
if(get_option("wp_anti_spam_spambots")!=''){
	session_start();
	session_regenerate_id();
	function wp_anti_spam_hidden($id){
		$unique_value = md5(uniqid(rand(), true));
		$_SESSION['unique_session'] = $unique_value;
		echo '<input type="hidden" name="human_form" value="'.$unique_value.'" />';
	}
	add_action('comment_form','wp_anti_spam_hidden');
	function wp_anti_spam_spambots($spambots_check){
		if(!isset($_POST['human_form']) || $_SESSION['unique_session'] != $_POST['human_form']){
			wp_die(__('Error: You are a automated spambot or your browser does not enable Cookie.','WP-Anti-Spam').WASINFO);
		}
		return $spambots_check;
	}
	add_filter('preprocess_comment','wp_anti_spam_spambots');
}

if(get_option("wp_anti_spam_deactivate")=='yes'){
	function wp_anti_spam_deactivate(){
		global $wpdb;
		$remove_options_sql = "DELETE FROM $wpdb->options WHERE $wpdb->options.option_name like 'wp_anti_spam_%'";
		$wpdb->query($remove_options_sql);
	}
	register_deactivation_hook(__FILE__,'wp_anti_spam_deactivate');
}

if(get_option("wp_anti_spam_support")!='no'){
	function support_author(){
		echo '<div style="text-align:center; font-style:italic; font-size:12px;">Protected by <a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP Anti Spam</a></div>';
	}
	add_action('comment_form','support_author');
}
?>