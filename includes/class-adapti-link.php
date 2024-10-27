<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.adapti.me
 * @since      1.0.0
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Adapti_Link
 * @subpackage Adapti_Link/includes
 * @author     Jonas <jonas@adapti.me>
 */
class Adapti_Link {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Adapti_Link_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'adapti-link';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Adapti_Link_Loader. Orchestrates the hooks of the plugin.
	 * - Adapti_Link_i18n. Defines internationalization functionality.
	 * - Adapti_Link_Admin. Defines all hooks for the admin area.
	 * - Adapti_Link_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-adapti-link-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-adapti-link-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-adapti-link-public.php';

		$this->loader = new Adapti_Link_Loader();

	}

	/**


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Adapti_Link_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action('admin_head', $plugin_admin, 'js_init');

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links');

		$this->loader->add_filter('manage_pages_columns', $plugin_admin, 'adjust_wp_list_pages');
		$this->loader->add_action( 'manage_pages_custom_column', $plugin_admin, 'adjust_wp_list_pages_content', 10, 2);

		$this->loader->add_filter( 'the_content', $plugin_admin, 'content');

		// Ajax functions
		$this->loader->add_action( 'wp_ajax_set_token', $plugin_admin, 'set_token_callback' );
		$this->loader->add_action( 'wp_ajax_get_token', $plugin_admin, 'get_token_callback' );
		$this->loader->add_action( 'wp_ajax_set_account', $plugin_admin, 'set_account_callback' );
		$this->loader->add_action( 'wp_ajax_page_versions', $plugin_admin, 'page_versions' );
		$this->loader->add_action( 'wp_ajax_nopriv_page_versions', $plugin_admin, 'page_versions' );

		// Meta box
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'add_post_meta_box' );
		$this->loader->add_action( 'wp_insert_post', $plugin_admin, 'save_post_meta_box', 10, 2 );
		$this->loader->add_action( 'wp_insert_post', $plugin_admin, 'new_version', 10, 3 );
		$this->loader->add_filter( 'default_content', $plugin_admin, 'init_content', 10, 2 );

		$this->loader->add_action('init', $plugin_admin, 'init');

		$this->loader->add_action('delete_post', $plugin_admin, 'update_operators', 10, 2);
		$this->loader->add_action( 'trashed_post', $plugin_admin, 'redirect_after_trashing', 10, 1 );

		$this->loader->add_action('page_row_actions', $plugin_admin, 'change_actions_in_list', 10, 2);

		if(get_option('adapti_config_token') == false){
			$this->loader->add_action( 'admin_notices', $plugin_admin, 'notice_config' );
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Adapti_Link_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action('init', $plugin_public, 'cookie_link');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Adapti_Link_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
