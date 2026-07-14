<?php
/**
 * Plugin Name: MaxPost Core
 * Description: Core content types, metadata, REST API and MaxPost Hub control plane.
 * Version: 0.2.0
 * Requires at least: 6.5
 * Requires PHP: 8.1
 * Author: MaxPost
 * Text Domain: maxpost-core
 */

defined( 'ABSPATH' ) || exit;

define( 'MAXPOST_CORE_VERSION', '0.2.0' );
define( 'MAXPOST_CORE_FILE', __FILE__ );
define( 'MAXPOST_CORE_DIR', plugin_dir_path( __FILE__ ) );

require_once MAXPOST_CORE_DIR . 'includes/post-types.php';
require_once MAXPOST_CORE_DIR . 'includes/meta.php';
require_once MAXPOST_CORE_DIR . 'includes/helpers.php';
require_once MAXPOST_CORE_DIR . 'includes/rest-api.php';
require_once MAXPOST_CORE_DIR . 'includes/admin.php';
require_once MAXPOST_CORE_DIR . 'includes/demo-content.php';
require_once MAXPOST_CORE_DIR . 'includes/hub.php';
require_once MAXPOST_CORE_DIR . 'includes/hub-rest.php';
require_once MAXPOST_CORE_DIR . 'includes/hub-admin.php';

register_activation_hook(
	__FILE__,
	static function (): void {
		maxpost_core_register_post_types();
		maxpost_hub_register_content_types();
		flush_rewrite_rules();
	}
);

register_deactivation_hook(
	__FILE__,
	static function (): void {
		flush_rewrite_rules();
	}
);

add_action( 'plugins_loaded', static function (): void {
	load_plugin_textdomain( 'maxpost-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );
