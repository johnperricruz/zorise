<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.primeview.com
 * @since      1.0.0
 *
 * @package    Zorise
 * @subpackage Zorise/includes
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
 * @package    Zorise
 * @subpackage Zorise/includes
 * @author     Primeview <john@primeview.com>
 */
class Zorise {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Zorise_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'zorise';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Zorise_Loader. Orchestrates the hooks of the plugin.
	 * - Zorise_i18n. Defines internationalization functionality.
	 * - Zorise_Admin. Defines all hooks for the admin area.
	 * - Zorise_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zorise-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-zorise-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-zorise-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-zorise-public.php';

		$this->loader = new Zorise_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Zorise_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Zorise_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Zorise_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Zorise_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

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
	 * @return    Zorise_Loader    Orchestrates the hooks of the plugin.
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
	/**
	 * Custom Functions
	 */
	public function register_hooks() {
		add_action('admin_menu',array(&$this, 'create_main_page'));
		add_action('init',array(&$this, 'register_shortcodes'));
		add_action( 'admin_init',array(&$this, 'zorise_save_settings_post')); 
	}

	public function create_main_page(){
		add_menu_page( 
			'Zoho-Highrise', 							 // Page Title
			'Zoho-Highrise',           				 // Navbar Title
			'manage_options',      					 // Permission 
			'zorise',      					 // Page ID
			//array(&$this, 'zorise_leads_page'),           			 	  	 // Function call
			array(&$this, 'zorise_settings_page'),           			 	  	 // Function call
			'dashicons-star-filled',   					 // Favicon
			2                				 	// Order
		);	
		// add_submenu_page( 
			// 'zorise',      			 		 // Parent Page ID
			// 'Leads',     		 		 // Page Title
			// 'Leads', 						 // Navbar Title
			// 'manage_options', 						 // Permission 	
			// 'zorise', 							 // Submenu Page ID
			// array(&$this, 'zorise_leads_page')	 		 // Function  call	 
		// ); 
		// add_submenu_page( 
			// 'zorise',      			 		 // Parent Page ID
			// 'Settings',     		 				 // Page Title
			// 'Settings', 						 // Navbar Title
			// 'manage_options', 						 // Permission 	
			// 'zorise-settings', 							 // Submenu Page ID
			// array(&$this, 'zorise_settings_page')								 // Function  call	 
		// ); 			
	}
	public function zorise_leads_page(){
		echo 'Coming Soon...';
	}
	public function zorise_settings_page(){
		?>
			<h2>Primeview Zoho-Highrise Integration</h2>
			<p>Shortcode : [zorise mode="zorise-frontend-form"]</p>
			<p>For other forms, contact developer.</p>
			<form method="post" action="options.php">
				<?=settings_fields( 'zorise-option-group' );?>
				<?=do_settings_sections( 'zorise-option-group' );?>
				<?php
					echo '<h3>Highrise</h3>';
					echo '<table width="50%">
							<tr>
								<td>Highrise Email</td>
								<td><input style="width:100%;" placeholder="Highrise Email..." type="text" name="highrise_email" value="'. esc_attr( get_option('highrise_email') ).'" /></td>
							</tr>
							<tr>
								<td>Highrise Auth Token</td>
								<td><input style="width:100%;" placeholder="Highrise Token" type="text" name="highrise_token" value="'. esc_attr( get_option('highrise_token') ).'" /></td>
							</tr>
						</table>
						<h3>Zoho</h3>
						<table width="50%">
							<tr>
								<td>Zoho Auth Token</td>
								<td><input style="width:100%;" placeholder="Zoho Token" type="text" name="zoho_token" value="'. esc_attr( get_option('zoho_token') ).'" /></td>
							</tr>
					</table>
					<h3>Email</h3>
						<table width="50%">
							<tr>
								<td>Recipient</td>
								<td><input style="width:100%;" placeholder="Email Recipient" type="text" name="zorise_email_recipient" value="'. esc_attr( get_option('zorise_email_recipient') ).'" /></td>
							</tr>
							<tr>
								<td>Subject</td>
								<td><input style="width:100%;" placeholder="Subject" type="text" name="zorise_email_subject" value="'. esc_attr( get_option('zorise_email_subject') ).'" /></td>
							</tr>
							<tr>
								<td>From</td>
								<td><input style="width:100%;" placeholder="From" type="text" name="zorise_email_from" value="'. esc_attr( get_option('zorise_email_from') ).'" /></td>
							</tr>
							<tr>
								<td>Redirection</td>
								<td><input style="width:100%;" placeholder="Redirection" type="text" name="zorise_redirect" value="'. esc_attr( get_option('zorise_redirect') ).'" /></td>
							</tr>
					</table>
					';
				?>
				
				<?php submit_button(); ?>
			</form>
		<?php
	} 
	function zorise_save_settings_post() {
		register_setting( 'zorise-option-group', 'highrise_email' );
		register_setting( 'zorise-option-group', 'highrise_token' );
		register_setting( 'zorise-option-group', 'zoho_token' );
		register_setting( 'zorise-option-group', 'zorise_email_recipient' );
		register_setting( 'zorise-option-group', 'zorise_email_subject' );
		register_setting( 'zorise-option-group', 'zorise_email_from' );
		register_setting( 'zorise-option-group', 'zorise_redirect' );
	}
	
	public function zorise_frontend_form( $atts ){
		
		$mode   = "";
		$return = "Invalid value!";
		
		extract( shortcode_atts( array(
			'mode' =>  $mode
		), $atts ) );
		
		if($mode == 'zorise-frontend-form'){
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'api/templates/zorise_frontend_form.php';
		}
		
	}
	
	/**
	 * Shortcodes
	 */
	 public function register_shortcodes(){
		add_shortcode('zorise', array(&$this, 'zorise_frontend_form')); //[zorise mode="zorise-frontend-form"]

	 }
}
