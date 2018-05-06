<?php
	/**
	 * @package MSDG Flight Guide
	 */
	/**
	Plugin Name: MSDG Flight Guide
	Plugin URI: https://xingapps.tech
	Description: Flight Guide Page
	Version: 1.0
	Author: XingApps
	Author URI: https://xingapps.tech
	Text Domain: msdg-fg
	License: A "Slug" license name e.g. GPL2
	*/

	if (! defined('ABSPATH')) {
		die;
	}

	/**
	 * Require composer autoload
	 */
	if (file_exists(__DIR__ . '/vendor/autoload.php')) {
		require_once __DIR__ . '/vendor/autoload.php';
	}


	/**
	 * Activate plugin
	 */
	function activate_msdg_plugin() {
		MSDG\Base\ActivateMSDG::activate();
	}
	register_activation_hook(__FILE__, 'activate_msdg_plugin');

	/**
	 * Deactivate plugin
	 */
	function deactivate_msdg_plugin() {
		MSDG\Base\DeactivateMSDG::deativate();
	}
	register_deactivation_hook(__FILE__, 'deactivate_msdg_plugin');

	/**
	 * Initialize all the classes of the plugin
	 */
	if (class_exists(MSDG\InitMSDG::class)) {
		MSDG\InitMSDG::register_services();
	}