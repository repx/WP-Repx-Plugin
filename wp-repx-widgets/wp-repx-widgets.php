<?php
/**
 * The RepX Wordpress Integration Plugin

 * Provides widgets to add RepX content to a Wordpress install.
 *
 * @package   WP_Repx_Widgets
 * @author    Steven Nance <steven@devtrw.com>
 * @license   MIT
 * @link      http://example.com
 * @copyright 2014 Steven Nance <steven@devtrw.com>

 * @wordpress-plugin
 * Plugin Name:       RepX Widgets
 * Plugin URI:        https://repx-dev.atlassian.net/wiki/display/DOCS/Wordpress+Integration
 * Description:       Integrate RepX with WordPress!
 * Version:           1.0.0
 * Author:            Steven Nance <steven@devtrw.com>
 * Text Domain:       wp-repx-widgets
 * License:           MIT
 * License URI:       http://opensource.org/licenses/MIT
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/repx/wp-repx-widgets
 * Author URI:        http://www.devtrw.com/
 *
 * WordPress-Plugin-Boilerplate: v2.6.1
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-wp-repx-widgets.php' );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, array( 'WP_Repx_Widgets', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'WP_Repx_Widgets', 'deactivate' ) );

/*
 *
 */
add_action( 'plugins_loaded', array( 'WP_Repx_Widgets', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 *
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-wp-repx-widgets-admin.php' );
	add_action( 'plugins_loaded', array( 'WP_Repx_Widgets_Admin', 'get_instance' ) );

}
