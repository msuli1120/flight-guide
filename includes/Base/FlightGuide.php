<?php

	/**
	 * @package MSDG Flight Guide
	 */

	namespace MSDG\Base;
	
	class FlightGuide extends MSDGBaseController
	{
		public function register()
		{
			add_action('page_template', array($this, 'flight_guide_page_template'));
		}

		public function flight_guide_page_template()
		{
			if ( is_page( 'flight-guide' ) ) {
				$page_template = $this->plugin_path . 'templates/flightguide.php';
			}
			return $page_template;
		}
	}