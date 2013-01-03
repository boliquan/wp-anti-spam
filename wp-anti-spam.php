<?php
/*
Plugin Name: WP Anti Spam
Plugin URI: http://boliquan.com/wp-anti-spam/
Description: WP Anti Spam can anti automated spambots, it can also anti artificial spams by"comment","ip","name","email","url". It has many other ways to deal with the spam comments. Besides,it can delete its own options, so it is a green plugin ! 
Version: 1.2.6
Author: BoLiQuan
Author URI: http://boliquan.com/
Text Domain: WP-Anti-Spam
Domain Path: /lang
*/

function load_wp_anti_spam_lang(){
	$currentLocale = get_locale();
	if(!empty($currentLocale)){
		$moFile = dirname(__FILE__) . "/lang/wp-anti-spam-" . $currentLocale . ".mo";
		if(@file_exists($moFile) && is_readable($moFile)) load_textdomain('WP-Anti-Spam', $moFile);
	}
}
add_filter('init','load_wp_anti_spam_lang');

$wp_anti_spam_words = array();
if(!defined('WASINFO')){define('WASINFO','(Protected by <a href="http://boliquan.com/wp-anti-spam/" target="_blank">WP Anti Spam</a>)');}

function wp_anti_spam($comment_data){
	
	$wp_anti_spam_words = explode("\r\n" , get_option("wp_anti_spam_words"));
	$comment_fields = array('author','email','url','comment');

	foreach($wp_anti_spam_words as $wp_anti_spam_word){
		$wp_anti_spam_word = trim($wp_anti_spam_word);
		foreach($comment_fields as $comment_field){
			if( $wp_anti_spam_word != '' && isset($_POST[$comment_field]) ){
				//if(eregi($wp_anti_spam_word, $_POST[$comment_field]) !== false){
				if(stristr($_POST[$comment_field],$wp_anti_spam_word) !== false){
 					wp_die(__('Error: The information you submit contains banned words.','WP-Anti-Spam').WASINFO);
				}
			}
		}
	}

	$visitor_ip = (@getenv("REMOTE_ADDR")) ? getenv("REMOTE_ADDR") : $_SERVER['REMOTE_ADDR'];
	if(in_array($visitor_ip,$wp_anti_spam_words)){
		wp_die(__('Error: Your IP has been banned to leave comments.','WP-Anti-Spam').WASINFO);
	}

	if(get_option("wp_anti_spam_links")=='yes'){
		$links = '/http:\/\/|https:\/\/|www\./u';
		if( preg_match($links,$comment_data['comment_author']) || preg_match($links,$comment_data['comment_content']) ){
			wp_die(__('Error: Links are not allowed in nickname or comment content.','WP-Anti-Spam').WASINFO);
		}
	}

	if(get_option("wp_anti_spam_cn_must")=='yes'){
		$cn_char = '/[一-龥]/u';
		if(!preg_match($cn_char, $comment_data['comment_content'])){
			wp_die(__('Error: Comments must contain Chinese.','WP-Anti-Spam').WASINFO);
		}
	}

	return($comment_data);
}
add_filter('preprocess_comment','wp_anti_spam',1);

if(get_option("wp_anti_spam_hiddenfield")!=''){
	if(!isset($_SESSION)){
		session_start();
		session_regenerate_id();
	}
	function wp_anti_spam_hidden(){
		$unique_value = md5(uniqid(rand(),TRUE));
		$_SESSION['unique_session'] = $unique_value;
		echo '<input type="hidden" name="hidden_form" value="'.$unique_value.'" />';
	}
	add_action('comment_form','wp_anti_spam_hidden');
	function wp_anti_spam_hiddenfield($hidden_check){
		$check_result = 0;
		if(isset($_SESSION['unique_session'])){
			if(!isset($_POST['hidden_form']) || $_SESSION['unique_session'] != $_POST['hidden_form']){
				$check_result = 1;
			}
		}
		elseif(!isset($_SESSION['unique_session'])){
			$check_result = 1;
		}
		if($check_result){
			wp_die(__('Error: You are a automated spambot or your browser does not enable Cookie.','WP-Anti-Spam').WASINFO);
		}
		return $hidden_check;
	}
	add_filter('preprocess_comment','wp_anti_spam_hiddenfield',1);
}

if(get_option("wp_anti_spam_gravatar")!='no-treatment'){
	function was_get_headers_curl($url){
		$curl = curl_init($url);
		$useragent = "Mozilla/5.0 (Windows NT 5.1; rv:6.0.1) Gecko/20100101 Firefox/6.0.1";
		curl_setopt($curl, CURLOPT_USERAGENT, $useragent);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$headers = trim(curl_exec($curl));
		$headers = explode("\n",$headers);
		curl_close($curl);
		return $headers;
	}
	function was_no_avatar_to_spam($comment){
		$emaildata = md5( strtolower($comment['comment_author_email']) );
		$urldata = 'http://www.gravatar.com/avatar/'. $emaildata .'?d=404';
		$avatarheaders = was_get_headers_curl($urldata);
		if(preg_match("/404/",$avatarheaders[0])){
			if(get_option("wp_anti_spam_gravatar")=='mark-it-as-spam' && !is_user_logged_in()){
				add_filter('pre_comment_approved', create_function('', 'return "spam";'));
			}
			if(get_option("wp_anti_spam_gravatar")=='block-it' && !is_user_logged_in()){
				wp_die(__('Error: You have to apply for an avatar at Gravatar.com.','WP-Anti-Spam').WASINFO);
			}
		}
		return $comment;
	}
	add_action('preprocess_comment', 'was_no_avatar_to_spam', 1);
}

if(get_option("wp_anti_spam_min")!='' && get_option("wp_anti_spam_max")!=''){
	function was_comment_length($incoming_comment){
		if(mb_strlen($incoming_comment['comment_content'],'utf-8')<get_option("wp_anti_spam_min") || mb_strlen($incoming_comment['comment_content'],'utf-8')>get_option("wp_anti_spam_max")){
			wp_die(__('Error: The words you input does not meet the Word Limit.','WP-Anti-Spam').WASINFO);
		}
		return($incoming_comment);
	}
	add_filter('preprocess_comment','was_comment_length',1);
}

function was_commenter_check($incoming_comment){
	$isposing = 0;
	if(get_option("wp_anti_spam_nicknames")!=''){
		$wp_anti_spam_nicknames = explode("," , get_option("wp_anti_spam_nicknames"));
		foreach($wp_anti_spam_nicknames as $wp_anti_spam_nickname){
			if($incoming_comment['comment_author'] == trim($wp_anti_spam_nickname)) { $isposing = 1; }
		}
	}
	if(get_option("wp_anti_spam_emails")!=''){
		$wp_anti_spam_emails = explode("," , get_option("wp_anti_spam_emails"));
		foreach($wp_anti_spam_emails as $wp_anti_spam_email){
			if($incoming_comment['comment_author_email'] == trim($wp_anti_spam_email)) { $isposing = 1; }
		}
	}
	if(!$isposing || is_user_logged_in()) { return $incoming_comment; }
	wp_die(__('Error: Only the logged user can use this nickname or email.','WP-Anti-Spam').WASINFO);
}
add_filter('preprocess_comment','was_commenter_check',1);

function wp_anti_spam_activate(){
	add_option('wp_anti_spam_words','');
	add_option('wp_anti_spam_links','yes');
	add_option('wp_anti_spam_cn_must','');
	add_option('wp_anti_spam_hiddenfield','');
	add_option('wp_anti_spam_gravatar','mark-it-as-spam');
	add_option('wp_anti_spam_min','0');
	add_option('wp_anti_spam_max','9999');
	add_option('wp_anti_spam_nicknames','');
	add_option('wp_anti_spam_emails','');
	add_option('wp_anti_spam_deactivate','');
	add_option('wp_anti_spam_support','yes');
}
register_activation_hook( __FILE__, 'wp_anti_spam_activate' );

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

function wp_anti_spam_settings_link($action_links,$plugin_file){
	if($plugin_file==plugin_basename(__FILE__)){
		$was_settings_link = '<a href="options-general.php?page=' . dirname(plugin_basename(__FILE__)) . '/wp_anti_spam_admin.php">' . __("Settings") . '</a>';
		array_unshift($action_links,$was_settings_link);
	}
	return $action_links;
}
add_filter('plugin_action_links','wp_anti_spam_settings_link',10,2);

if(is_admin()){require_once('wp_anti_spam_admin.php');}
?>