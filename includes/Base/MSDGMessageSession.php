<?php

	namespace MSDG\Base;

	class MSDGMessageSession
	{
		public function register()
		{
			add_action('init', array($this, 'msdg_session'));
		}

		public function msdg_session()
		{
			if (! session_id()) {
				session_start();
				$_SESSION = ['msdg_message' => NULL, 'msdg_error' => NULL];
			}
		}
	}