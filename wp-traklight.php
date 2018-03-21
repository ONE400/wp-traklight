<?php
/*
 * Plugin Name: WP Traklight
 * Version: 1.0
 * Plugin URI: http://www.one-400.com/
 * Description: A Traklight integration plugin for WordPress
 * Author: ONE400
 * Author URI: http://www.one-40.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: wp-traklight
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author ONE400
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin class files
require_once( 'includes/class-wp-traklight.php' );
require_once( 'includes/class-wp-traklight-settings.php' );

// Load plugin libraries
require_once( 'includes/lib/class-wp-traklight-admin-api.php' );
require_once( 'includes/lib/class-wp-traklight-post-type.php' );
require_once( 'includes/lib/class-wp-traklight-taxonomy.php' );

/**
 * Returns the main instance of WP_Traklight to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object WP_Traklight
 */
function WP_Traklight () {
	$instance = WP_Traklight::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = WP_Traklight_Settings::instance( $instance );
	}

	return $instance;
}

WP_Traklight();
