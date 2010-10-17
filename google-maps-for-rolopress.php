<?php
/**
Plugin Name: Google Maps for RoloPress
Plugin URI: http://rolopress.com/plugins/google-maps-for-rolopress
Description: Show a Google Map from your Contact and Company addresses. | REQUIREMENTS: <a href="http://rolopress.com/themes/rolopress-core">RoloPress Core Theme</a>.
Author: <a href="http://rolopress.com">RoloPress</a> and <a href="http://slipfire.com">SlipFire LLC.</a>
Version: 0.1

// Copyright (c) 2010 RoloPress, All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is a plugin for WordPress
// http://wordpress.org/
//
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
// **********************************************************************
**/

// Set constant path to the plugin directory.
define( GMAP_ROLO_DIR, WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)) );

// Load Language
load_plugin_textdomain('gmap-rolo', false, GMAP_ROLO_DIR . 'language');


// Launch the plugin.
add_action( 'plugins_loaded', 'gmap_rolo_plugin_init' );

/**
 * Initializes the plugin and it's features.
 */
function gmap_rolo_plugin_init() {

	// Loads and registers the new widget.
	add_action( 'widgets_init', 'gmap_rolo_load_widget' );
	
}

/**
 * Register the widget. 
 *
 * @uses register_widget() Registers individual widgets.
 * @link http://codex.wordpress.org/WordPress_Widgets_Api
 */
function gmap_rolo_load_widget() {

	//Load widget file.
	require_once( 'gmap-widget.php' );

	// Register widget.
	register_widget( 'Rolo_Gmap' );
}

?>