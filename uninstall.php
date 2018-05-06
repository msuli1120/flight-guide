<?php
	/**
	 * @package MSDG Flight Guide
	 */

	if (! defined('WP_UNINSTALL_PLUGIN')) {
		die;
	}


	$option_name = 'wporg_option';
	delete_option( $option_name);


	global $wpdb;

	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}msdg_brands");
	$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}msdg_discs");