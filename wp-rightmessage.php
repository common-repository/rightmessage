<?php
// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Plugin Name: RightMessage Wordpress Plugin
 * Plugin URI: https://rightmessage.com/
 * Description: Integrate RightMessage into your website
 * Version: 0.9.7
 * Author: RightMessage
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: rightmessage
 */

 


if ( class_exists( 'WP_RightMessage' ) ) {
	return;
}

define( 'RIGHTMESSAGE_PLUGIN_FILE', plugin_basename( __FILE__ ) );
define( 'RIGHTMESSAGE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'RIGHTMESSAGE_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'RIGHTMESSAGE_PLUGIN_VERSION', '0.9.7' );

require_once RIGHTMESSAGE_PLUGIN_PATH . '/includes/class-rightmessage.php';

if ( is_admin() ) {
	require_once RIGHTMESSAGE_PLUGIN_PATH . '/admin/class-rightmessage-settings.php';
	require_once RIGHTMESSAGE_PLUGIN_PATH . '/admin/section/class-rightmessage-settings-base.php';
	require_once RIGHTMESSAGE_PLUGIN_PATH . '/admin/section/class-rightmessage-settings-general.php';

	$rightmessage_settings = new RightMessage_Settings();
}

WP_RightMessage::init();
