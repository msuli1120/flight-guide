<?php
	/**
	 * @package MSDG Flight Guide
	 */

	namespace MSDG\Base;

	class ActivateMSDG
	{
		public static function activate()
		{
			if (version_compare( get_bloginfo( 'version' ), '4.7', '<')){
				wp_die(__('You have to update WordPress to use the plugin!', 'msdg-fg'));
			}

			global $wpdb, $table_prefix;
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			$msdg_brands_table = $table_prefix . 'msdg_brands';
			$msdg_discs_table = $table_prefix . 'msdg_discs';

			if ($wpdb->get_var("SHOW TABLES LIKE '$msdg_brands_table'") != $msdg_brands_table) {
				$createSQL_color_notes = "CREATE TABLE `" . $wpdb->prefix . "msdg_brands` ( `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `brand` VARCHAR(255) NOT NULL , `color` VARCHAR(7) NOT NULL , `text_color` VARCHAR(7) NOT NULL,PRIMARY KEY (`ID`)) ENGINE = InnoDB " . $wpdb->get_charset_collate() . " AUTO_INCREMENT=1;";
				dbDelta($createSQL_color_notes);
			}

			if($wpdb->get_var( "SHOW TABLES LIKE '$msdg_discs_table'" ) != $msdg_discs_table){
				$createSQL_posts_notes = "CREATE TABLE `" . $wpdb->prefix . "msdg_discs` ( `ID` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT , `brand_id` INT(11) UNSIGNED NOT NULL , `disc` VARCHAR(255) NOT NULL,`x` CHAR(1) DEFAULT NULL, `y` INT(2) UNSIGNED DEFAULT NULL, `type` VARCHAR(255) DEFAULT NULL, `speed` FLOAT(3,1) DEFAULT NULL, `glide` FLOAT(3,1) DEFAULT NULL, `turn` FLOAT(3,1) DEFAULT NULL, `fade` FLOAT(3,1) DEFAULT NULL, `pic_link` VARCHAR(255) DEFAULT NULL, `link` VARCHAR(255) NOT NULL, PRIMARY KEY (`ID`)) ENGINE = InnoDB " . $wpdb->get_charset_collate() . " AUTO_INCREMENT=1;";
				dbDelta($createSQL_posts_notes);
			}

			flush_rewrite_rules();
		}
	}