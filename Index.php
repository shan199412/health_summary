<?php

/*
Plugin Name: Health Summary
Description: Short Code: [Health-City-Summary]
Version: 1.1
Author: Alex
*/

// User cannot access the plugin directly
if (!defined('ABSPATH')) {
	exit;
}

// Add short code for the plugin
function generate_hcs_short_code() {
	include 'health-summary.php';
}

add_shortcode('Health-City-Summary', 'generate_hcs_short_code');

// Add the scripts
function add_hcs_scripts() {
//	wp_enqueue_script('ecscity_script', plugins_url('/js/ecscity_script.js',__FILE__), array('jquery'),'1.1', true);
//	wp_enqueue_style( 'ecscity_style', plugins_url('/css/ecscity_style.css', __FILE__), array(), '1.1');
}

add_action('wp_enqueue_scripts', 'add_hcs_scripts');
