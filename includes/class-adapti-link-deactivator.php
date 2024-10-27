<?php

/**
 * Fired during plugin deactivation
 *
 * @link       www.adapti.me
 * @since      1.0.0
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Adapti_Link
 * @subpackage Adapti_Link/includes
 * @author     Jonas <jonas@adapti.me>
 */
class Adapti_Link_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {

		// Is it a good idea to delete this ? ..

		/*global $wpdb;

		$wpdb->delete('wp_options', [
			'option_name' => 'adapti_config_account'
		]);

		$wpdb->delete('wp_options', [
			'option_name' => 'adapti_config_token'
		]);*/
	}

}
