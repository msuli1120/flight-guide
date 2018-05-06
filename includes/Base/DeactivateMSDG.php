<?php
	/**
	 * @package MSDG Flight Guide
	 */

	namespace MSDG\Base;

	class DeactivateMSDG
	{
		public static function deativate()
		{
			flush_rewrite_rules();
		}
	}