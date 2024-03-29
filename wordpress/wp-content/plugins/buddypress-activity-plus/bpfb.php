<?php
/*
Plugin Name: BuddyPress Activity Plus
Plugin URI: http://premium.wpmudev.org/project/media-embeds-for-buddypress-activity
Description: A Facebook-style media sharing improvement for the activity box.
Version: 1.4.1
Author: Ve Bailovity (Incsub), designed by Brett Sirianni (The Edge)
Author URI: http://premium.wpmudev.org
WDP ID: 232
*/


define ('BPFB_PLUGIN_SELF_DIRNAME', basename(dirname(__FILE__)), true);
define ('BPFB_PROTOCOL', (@$_SERVER["HTTPS"] == 'on' ? 'https://' : 'http://'), true);


//Setup proper paths/URLs and load text domains
if (is_multisite() && defined('WPMU_PLUGIN_URL') && defined('WPMU_PLUGIN_DIR') && file_exists(WPMU_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('BPFB_PLUGIN_LOCATION', 'mu-plugins', true);
	define ('BPFB_PLUGIN_BASE_DIR', WPMU_PLUGIN_DIR, true);
	define ('BPFB_PLUGIN_URL', str_replace('http://', BPFB_PROTOCOL, WPMU_PLUGIN_URL), true);
	$textdomain_handler = 'load_muplugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . BPFB_PLUGIN_SELF_DIRNAME . '/' . basename(__FILE__))) {
	define ('BPFB_PLUGIN_LOCATION', 'subfolder-plugins', true);
	define ('BPFB_PLUGIN_BASE_DIR', WP_PLUGIN_DIR . '/' . BPFB_PLUGIN_SELF_DIRNAME, true);
	define ('BPFB_PLUGIN_URL', str_replace('http://', BPFB_PROTOCOL, WP_PLUGIN_URL) . '/' . BPFB_PLUGIN_SELF_DIRNAME, true);
	$textdomain_handler = 'load_plugin_textdomain';
} else if (defined('WP_PLUGIN_URL') && defined('WP_PLUGIN_DIR') && file_exists(WP_PLUGIN_DIR . '/' . basename(__FILE__))) {
	define ('BPFB_PLUGIN_LOCATION', 'plugins', true);
	define ('BPFB_PLUGIN_BASE_DIR', WP_PLUGIN_DIR, true);
	define ('BPFB_PLUGIN_URL', str_replace('http://', BPFB_PROTOCOL, WP_PLUGIN_URL), true);
	$textdomain_handler = 'load_plugin_textdomain';
} else {
	// No textdomain is loaded because we can't determine the plugin location.
	// No point in trying to add textdomain to string and/or localizing it.
	wp_die(__('There was an issue determining where Google Maps plugin is installed. Please reinstall.'));
}
$textdomain_handler('bpfb', false, BPFB_PLUGIN_SELF_DIRNAME . '/languages/');

// Override oEmbed width in wp-config.php
if (!defined('BPFB_OEMBED_WIDTH')) define('BPFB_OEMBED_WIDTH', 450, true);

// Override image limit in wp-config.php
if (!defined('BPFB_IMAGE_LIMIT')) define('BPFB_IMAGE_LIMIT', 5, true);


$wp_upload_dir = wp_upload_dir();
define('BPFB_TEMP_IMAGE_DIR', $wp_upload_dir['basedir'] . '/bpfb/tmp/', true);
define('BPFB_TEMP_IMAGE_URL', $wp_upload_dir['baseurl'] . '/bpfb/tmp/', true);
define('BPFB_BASE_IMAGE_DIR', $wp_upload_dir['basedir'] . '/bpfb/', true);
define('BPFB_BASE_IMAGE_URL', $wp_upload_dir['baseurl'] . '/bpfb/', true);


// Hook up the installation routine and check if we're really, really set to go
require_once BPFB_PLUGIN_BASE_DIR . '/lib/class_bpfb_installer.php';
register_activation_hook(__FILE__, array('BpfbInstaller', 'install'));
BpfbInstaller::check();


/**
 * Includes the core requirements and serves the improved activity box.
 */
function bpfb_plugin_init () {
	require_once(BPFB_PLUGIN_BASE_DIR . '/lib/class_bpfb_binder.php');
	require_once(BPFB_PLUGIN_BASE_DIR . '/lib/class_bpfb_codec.php');
	// Group Documents integration
	if (defined('BP_GROUP_DOCUMENTS_IS_INSTALLED') && BP_GROUP_DOCUMENTS_IS_INSTALLED) {
		require_once(BPFB_PLUGIN_BASE_DIR . '/lib/bpfb_group_documents.php');
	}
	do_action('bpfb_init');
	BpfbBinder::serve();
}
// Only fire off if BP is actually loaded.
add_action('bp_loaded', 'bpfb_plugin_init');