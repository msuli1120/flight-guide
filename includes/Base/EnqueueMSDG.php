<?php
	/**
	 * @package MSDG Flight Guide
	 */

	namespace  MSDG\Base;

	class EnqueueMSDG extends MSDGBaseController
	{
		public function register()
		{
			add_action('wp_enqueue_scripts', array($this, 'enqueue'));
		}

		public function enqueue()
		{
			if ( is_page( 'flight-guide' ) ) {
				wp_enqueue_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js', null, null, true);
				wp_enqueue_style('msdgstyles',$this->plugin_url . 'assets/css/styles.css');
				wp_enqueue_script('flightguidegraphjs', $this->plugin_url . 'assets/js/flightguidegraph.js', array('jquery'), null, true);
				if (current_user_can('manage_options')) {
					wp_enqueue_script('msdgcolorpicker', $this->plugin_url . 'assets/js/colorpicker.js', null, null, true);
					wp_enqueue_script('msdgscripts',$this->plugin_url . 'assets/js/scripts.js', array('jquery'), null, true);
				}
			}
		}
	}