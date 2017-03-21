<?php
/**
* Plugin Name: Simple WP Twitter Cron
*/

add_action( 'admin_menu', 'swptc_add_admin_menu' );
add_action( 'admin_init', 'swptc_settings_init' );


function swptc_add_admin_menu(  ) {

	add_submenu_page( 'tools.php', 'Simple Twitter Cron', 'Simple WP Twitter Cron', 'manage_options', 'simple_wp_twitter_cron', 'swptc_options_page' );

}


function swptc_settings_init(  ) {

	register_setting( 'pluginPage', 'swptc_settings' );

	add_settings_section(
		'swptc_pluginPage_section',
		__( 'Setup twitter details below', 'swptc' ),
		'swptc_settings_section_callback',
		'pluginPage'
	);

	add_settings_field(
		'consumer_key',
		__( 'Consumer Key', 'swptc' ),
		'consumer_key_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'consumer_secret',
		__( 'Consumer Secret', 'swptc' ),
		'consumer_secret_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'access_token',
		__( 'Access Token', 'swptc' ),
		'access_token_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'access_token_secret',
		__( 'Access Token Secret', 'swptc' ),
		'access_token_secret_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'twitter_handle',
		__( 'Twitter Handle', 'swptc' ),
		'twitter_handle_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'num_tweets',
		__( '# Tweets to store', 'swptc' ),
		'num_tweets_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'refresh_rate',
		__( 'How often refresh (hours)', 'swptc' ),
		'refresh_rate_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

	add_settings_field(
		'cron_get_var',
		__( 'URL for Cron', 'swptc' ),
		'cron_get_var_render',
		'pluginPage',
		'swptc_pluginPage_section'
	);

}


function consumer_key_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[consumer_key]' value='<?php echo $options['consumer_key']; ?>'>
	<?php

}


function consumer_secret_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='password' name='swptc_settings[consumer_secret]' value='<?php echo $options['consumer_secret']; ?>'>
	<?php

}


function access_token_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[access_token]' value='<?php echo $options['access_token']; ?>'>
	<?php

}


function access_token_secret_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='password' name='swptc_settings[access_token_secret]' value='<?php echo $options['access_token_secret']; ?>'>
	<?php

}


function twitter_handle_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[twitter_handle]' value='<?php echo $options['twitter_handle']; ?>'>
	<?php

}


function num_tweets_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[num_tweets]' value='<?php echo $options['num_tweets']; ?>'>
	<?php

}


function refresh_rate_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[refresh_rate]' value='<?php echo $options['refresh_rate']; ?>'>
	<?php

}


function cron_get_var_render(  ) {

	$options = get_option( 'swptc_settings' );
	?>
	<input type='text' name='swptc_settings[cron_get_var]' value='<?php echo $options['cron_get_var']; ?>'>
	<i><?php echo home_url() ?>/?<?php echo !empty($options['cron_get_var']) ? $options['cron_get_var'] : 'tweets'  ?>
		<br /><br />
		<p>Cron job: <input type="text" disabled="disabled" value="*/30 * * * * wget -q -O - <?php echo home_url() ?>/?<?php echo !empty($options['cron_get_var']) ? $options['cron_get_var'] : 'tweets'  ?> >> /dev/null 2>&1" size="100" /></p>
	<?php

}


function swptc_settings_section_callback(  ) {



}

function swptc_procces_settings(){
	if(isset($_POST['swptc_settings'])){
		update_option('swptc_settings', $_POST['swptc_settings']);
	}
}

function swptc_update_tweets(){
	if($option = get_option('swptc_settings')){
		$getvar = !empty($option['cron_get_var']) ? $option['cron_get_var'] : 'tweets' ;
		if(isset($_GET[$getvar])){
			include('get_tweets.php');
		}
	}
}
add_action('init', 'swptc_update_tweets');

function swptc_options_page(  ) {

	?>
	<form action='tools.php?page=simple_wp_twitter_cron' method='post'>

		<h2>Simple WP Twitter Cron</h2>

		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>

	</form>
	<?php

}

if(isset($_POST)){
	swptc_procces_settings();
}
?>
