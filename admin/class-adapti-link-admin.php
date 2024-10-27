<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.adapti.me
 * @since      1.0.1
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/admin
 */

require_once(__DIR__ . '/core/Human.php');
require_once(__DIR__ . '/core/Api.php');
require_once(__DIR__ . '/core/Link.php');
require_once(__DIR__ . '/core/Printer.php');
require_once(__DIR__ . '/core/Version.php');

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Adapti_Link
 * @subpackage Adapti_Link/admin
 * @author     Jonas <jonas@adapti.me>
 */
class Adapti_Link_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/adapti-link-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.1
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/adapti-link-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.1
	 */
	 
	public function add_plugin_admin_menu() {

		ob_start();
		require_once(__DIR__ . '/partials/admin-menu.php');
		$adaptations = ob_get_contents();
		ob_end_clean();
        
        add_menu_page('Adapti Adaptations', 
            'Personalize', 
            'manage_options', 
            $this->plugin_name, 
            [ $this, 'display_plugin_setup_page' ],
            "https://www.adapti.me/ad-views/img/favicon.png"
        );
        
	}
    
     public function create_admin_page()
    {
        require_once(__DIR__ . '/core/Api.php');
		
		$adaptations = json_decode(Adapti_Api::get('rule',[ 'method' => 'GET', 'data' => [ 'nb' => 10 ]]));
        
        $res = Adapti_Api::get('check', [ 'method' => 'POST', 'data' => [ 'token_check' => get_option('adapti_config_token') ] ]);
        $res = json_decode($res);
         
        
        
        include_once( 'partials/admin-tab/adaptations.php' );
        
    }

	 /**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.1
	 */
	 
	public function add_action_links( $links ) {
		/*
		*  Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
		*/
	   $settings_link = array(
		'<a href="' . admin_url( 'admin.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
	   );
	   return array_merge(  $settings_link, $links );

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	 
	public function display_plugin_setup_page() {
		require_once(__DIR__ . '/core/Api.php');
		
        if($_GET['page'] == 'adapti-linkseelist') {
            $adaptationsGet = Adapti_Api::get('rule', [ 'method' => 'GET', 'data' => [ 'nb' => 10 ]]);
        } else {
            $adaptationsGet = Adapti_Api::get('rule');
        }
        
		$adaptations = json_decode($adaptationsGet);
        
        $res = Adapti_Api::get('check', [ 'method' => 'POST', 'data' => [ 'token_check' => get_option('adapti_config_token') ] ]);
        $res = json_decode($res);
       
        include_once( 'partials/admin-tab/layout.php' );
		
	}
	 


	/**
	 * Set the account creation
	 * 
	 * @return void
	 */
	public function set_account_callback() {
		header('Content-Type: application/json');

		if(get_option('adapti_config_account') == false){
			add_option('adapti_config_account', 'ok');
		}

		wp_die();
	}

	public function page_versions(){
		header('Content-Type: application/json');

		$data = [];

		if(isset($_GET['page_id'])){
			require_once(__DIR__ . '/core/Version.php');
			$v = new Adapti_Version(intval($_GET['page_id']));
			foreach($v->get('versions') as $index => $version){
				$match = json_decode(get_post_meta($version['ID'], Adapti_Version::$meta['tag'], true));
				$match = $match != null ? $match : [];
				$data [] = [
					'match' => $match,
					'data' => $version['ID']
				];
			}
		}

		echo json_encode($data);

		wp_die();
	}

	/**
	 * Set the token of the wordpress website
	 * 
	 * @return void
	 */
	public function set_token_callback() {
		header('Content-Type: application/json');

		$data = [];

		if(!isset($_POST['token'])){
			$data['error'] = 'Token required';
		}
		else{
            if(strlen($_POST['token']) > 0 && strlen($_POST['token']) < 255 ) {
                $token_exist = get_option('adapti_config_token');

                $newtoken = sanitize_text_field($_POST['token']);

                if($token_exist != false){
                    update_option('adapti_config_token', $newtoken);

                    $data['old'] = $token_exist;
                }
                else{
                    add_option('adapti_config_token', $newtoken);
                }

                $data['new'] = $newtoken;
            } else {
                $data['error'] = 'Token required';
            }
		}

		echo json_encode($data);

		wp_die();
	}

	/**
	 * Get the token of the wordpress website
	 * 
	 * @return void
	 */
	public function get_token_callback() {
		header('Content-Type: application/json');

		$data = [];

		$token = get_option('adapti_config_token');
		$data['token'] = $token == false ? null : $token;

		echo json_encode($data);

		wp_die();
	}

	public function init(){
		register_post_type( 'adapti_version',
		    array(
		      	'labels' => array(
		        	'name' => __( 'Versions' ),
		        	'singular_name' => __( 'Version' ),
		        	'add_new' => __('Add new'),
					'add_new_item' => __('Add a Version'),
					'edit_item' => __('Edit Version'),
					'new_item' => __('New Version'),
					'view_item' => __('View Version'),
					'view_items' => __('View Versions'),
					'search_items' => __('Search Versions'),
		      	),
		      	'public' => true,
		      	'show_in_nav_menus' => false,
		      	'show_in_menu' => false,
		      	'show_in_admin_bar' => false,
		    )
		);
		flush_rewrite_rules();

		// Handle token update
		if(isset($_POST['token'])){
            $newtoken = sanitize_text_field($_POST['token']);
            if(strlen($newtoken) <= 0 && strlen($newtoken) > 255 ) { 
                return false;
            }
            
			require_once(__DIR__ . '/core/Api.php');
			require_once(__DIR__ . '/core/Printer.php');
			$res = Adapti_Api::get('check', [ 'method' => 'POST', 'data' => [ 'token_check' => $newtoken ] ]);
			$res = json_decode($res);
			if($res->check == true){
				update_option('adapti_config_token', $newtoken);
				add_action( 'admin_notices', function(){
					Adapti_Printer::alert("Successfully updated your token.");
				});
				
			}
			else{
				add_action( 'admin_notices', function() use ($res) {
					Adapti_Printer::alert('There was an error. ' . $res->msg, 'error');
				});
			}
		}
	}

	/**
	 * This function adds a meta box with a callback function of my_metabox_callback()
	 */
	public function add_post_meta_box() {
	    add_meta_box(
	        'adapti-tags',
	        __( 'Adapti tags', 'Adapti tags' ),
	        [ $this, 'tags_metabox_content' ],
	        [ 'post', 'adapti_version' ],
	        'side',
	        'core'
	    );

	    add_meta_box(
	        'adapti-versions',
	        __( 'Adapti versions', 'Adapti versions' ),
	        [ $this, 'versions_metabox_content' ],
	        [ 'page', 'adapti_version' ],
	        'side',
	        'core'
	    );
	}

	public function adjust_wp_list_pages($cols){

		$new = array();

		foreach($cols as $key=>$value) {
			if($key == 'author') { 
			   $new['adapti_versions'] = 'Versions'; 
			}
			$new[$key]=$value;
		}  

		return $new; 
	}

	public function change_actions_in_list($actions, $post){
		require_once(__DIR__ . '/core/Version.php');
	    if ($post->post_type =="page"){
	    	$v = new Adapti_Version;
	    	$actions['edit'] = '<a href="'.get_edit_post_link($v->get('lastEdited')['ID']).'">'.Adapti_Human::msg('edit_last').'</a>';
	    }
	    return $actions;
	}

	public function update_operators($post_id){
		require_once(__DIR__ . '/core/Version.php');
		if(get_post_type($post_id) == 'adapti_version'){
			$v = new Adapti_Version;
			$v->setOperators();
		}
	}

	public function redirect_after_trashing($post_id){
		require_once(__DIR__ . '/core/Version.php');
		if(get_post_type($post_id) == 'adapti_version'){
			$v = new Adapti_Version;
			wp_redirect(get_edit_post_link($v->get('lastEdited')['ID'], ''));
			exit();
		}
	}

	public function adjust_wp_list_pages_content($col, $id){
		require_once(__DIR__ . '/core/Human.php');
		require_once(__DIR__ . '/core/Version.php');

		if($col == 'adapti_versions'){
			$v = new Adapti_Version;

			$versions = $v->get('versions');
			$strOperators = Adapti_Human::buildOperators(json_decode($v->get('operators')));

	    	include( 'partials/pages_list/version-component.php' );
	    }
	}

	public function notice_config(){
		require_once(__DIR__ . '/core/Printer.php');
		Adapti_Printer::alert(Adapti_Human::msg('init_msg', [ 'url' => admin_url( 'admin.php?page=' . $this->plugin_name ) ]), 'update-nag');
	}

	public function content($content){
		require_once(__DIR__ . '/core/Printer.php');
		require_once(__DIR__ . '/core/Version.php');
		global $wpdb;

		if(get_post_type(get_the_ID()) == 'adapti_version'){
			return $content;
		}
		else if(get_post_type(get_the_ID()) == 'page'){
			$v = new Adapti_Version;
			return Adapti_Printer::content($v);
		} else {
            return $content;
        }
	}

	function new_version( $post_id, $post, $update ) {
		if(!$update){
			require_once(__DIR__ . '/core/Version.php');

			$v = new Adapti_Version;

			if(isset($_GET[Adapti_Version::$meta['version']])){
				if(!get_post_meta($v->get('id'), Adapti_Version::$meta['tag'], true)){
					$v->setVersion($_GET[Adapti_Version::$meta['version']]);

					$default = get_post_field('post_content', $_GET[Adapti_Version::$meta['version']]);
					wp_update_post([ 'ID' => $post->ID, 'post_content' => $default ]);
				}
			}
		}
	}

	public function init_content($content, $post){
		if($post->post_type == 'adapti_version'){
			if(isset($_GET[Adapti_Version::$meta['version']])){
				$content = get_post_field('post_content', $_GET[Adapti_Version::$meta['version']]);
			}
		}

		return $content;
	}

	public function versions_metabox_content( $post ) {
		require_once(__DIR__ . '/core/Version.php');
		require_once(__DIR__ . '/core/Human.php');
		global $wpdb;

		$v = new Adapti_Version;

		if(isset($_GET[Adapti_Version::$meta['version']])){
			$v->setVersion($_GET[Adapti_Version::$meta['version']]);
		}

		$versions = $v->get('versions');
		$dad = $v->get('dad');
		$strOperators = Adapti_Human::buildOperators(json_decode($v->get('operators')));

		$imdad = $dad == $v->get('id');

	    include_once( 'partials/metabox/versions.php' );
	}

	/**
	 * Get post meta in a callback
	 *
	 * @param WP_Post $post    The current post.
	 */
	 
	public function tags_metabox_content( $post ) {
	    include_once( 'partials/metabox/tags.php' );
	}


	/* Save the meta box's post metadata. */
	public function save_post_meta_box( $post_id, $post ) {
		require_once(__DIR__ . '/core/Version.php');

		$v = new Adapti_Version;
        $newTag = sanitize_text_field($_POST[Adapti_Version::$meta['tag']]);
		$v->setTag($newTag);

		if($post->post_type == 'page' || $post->post_type == 'adapti_version'){
			$v->setOperators();

			if($post->post_type == 'adapti_version' && $post->post_status == 'publish'){
                $v->saveVersion($newTag);
			}

			// Create a rule only if there is versions to a page
			if($post->post_status == 'publish'){
				$v->apiSave();
			}
		} 
	}

	public function js_init(){
		require_once('partials/init_js.php');
	}
}
