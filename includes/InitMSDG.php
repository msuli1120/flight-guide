<?php
	namespace MSDG;

	final class InitMSDG
	{
		/**
		 * Store all classes
		 * @return array list of classes
		 */
		public static function get_services()
		{
			return [
				Base\EnqueueMSDG::class,
				Base\FlightGuide::class,
				Base\MSDGMessageSession::class,
			];
		}

		/**
		 * Loop through classes, initialize,
		 * and call register method if it exists
		 * @return void
		 */
		public static function register_services()
		{
			foreach (self::get_services() as $class) {
				$service = self::instantiate($class);
				if (method_exists($service, 'register')) {
					$service->register();
				}
			}
		}

		/**
		 * Initialize the class
		 *
		 * @param $class
		 *
		 * @return mixed
		 */
		private static function instantiate($class)
		{
			return new $class;
		}
	}