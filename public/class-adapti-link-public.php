<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       www.adapti.me
 * @since      1.0.0
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/public
 * @author     Jonas <jonas@adapti.me>
 */
class Adapti_Link_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $user_link;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/adapti-link-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		require_once(__DIR__ . '/../admin/core/Link.php');

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/adapti-link-public.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script('adapti_api', Adapti_plug_Link::apiroute('/js', 'api'), [], null);

	}

	public function cookie_link(){
		if(!isset($_COOKIE['adapti_link'])){
			$this->user_link = adapti_random_str(10);
			setcookie('adapti_link', $this->user_link, time()+60*60*24*350);
		}
		else{
			$this->user_link = $_COOKIE['adapti_link'];
		}
	}

}
