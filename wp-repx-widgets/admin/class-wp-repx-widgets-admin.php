<?php
/**
 * Plugin Name.
 *
 * @package   WP_Repx_Widgets_Admin
 * @author    Steven Nance <steven@devtrw.com>
 * @license   MIT
 * @link      http://example.com
 * @copyright 2014 Steven Nance <steven@devtrw.com>
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * administrative side of the WordPress site.
 *
 * If you're interested in introducing public-facing
 * functionality, then refer to `class-wp-repx-widgets.php`
 *
 * @package WP_Repx_Widgets_Admin
 * @author  Steven Nance <steven@devtrw.com>
 */
class WP_Repx_Widgets_Admin {

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by loading admin scripts & styles and adding a
	 * settings page and menu.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		$plugin = WP_Repx_Widgets::get_instance();
		$this->plugin_slug = $plugin->get_plugin_slug();

		// Load admin style sheet and JavaScript.
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Add settings for the widgets
		add_action( 'admin_init', array( $this, 'add_plugin_settings' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Add an action link pointing to the options page.
		$plugin_basename = plugin_basename( plugin_dir_path( realpath( dirname( __FILE__ ) ) ) . $this->plugin_slug . '.php' );
		add_filter( 'plugin_action_links_' . $plugin_basename, array( $this, 'add_action_links' ) );

		/*
		 * Define custom functionality.
		 *
		 * Read more about actions and filters:
		 * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		 */
		//add_action( '@TODO', array( $this, 'action_method_name' ) );
		//add_filter( '@TODO', array( $this, 'filter_method_name' ) );

	}

	public function add_plugin_settings()
	{
		;
		// Create a configuration section for the API settings
		add_settings_section(
			'repx_widgets_api_section',
			'API Configuration',
			array( $this, 'api_setting_section_callback' ),
			$this->plugin_slug
		);

		// Add the ClientID option to the new API Configuration section
		add_settings_field(
			'repx_widgets_api_client_id',
			'Client ID',
			array( $this, 'api_client_id_setting_callback' ),
			$this->plugin_slug,
			'repx_widgets_api_section'
		);

		// Register our setting so that $_POST handling is done for us and
		// our callback function just has to echo the <input>
		register_setting( $this->plugin_slug, 'repx_widgets_api_client_id' );
	}

	/**
	 * This function is run at the start of the api section.
	 */
	public function api_setting_section_callback()
	{
		printf(
			'<p>%s</p>',
			__( 'Api settings be below!', $this->plugin_slug )
		);
	}

	public function api_client_id_setting_callback()
	{
		$option_name = 'repx_widgets_api_client_id';
		$attributes = array(
			'class'       => 'small-text',
			'id'          => $option_name,
			'name'        => $option_name,
			'placeholder' => 'Subdomain',
			'type'        => 'text',
			'value'       => get_option( $option_name ),
		);

		$attributeString = '';
		foreach($attributes as $property => $value) {
			$attributeString .= sprintf(' %s="%s"', $property, $value);
		}

		printf('https://<input%s>.repx.me/widgets.js', $attributeString);
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'assets/css/admin.css', __FILE__ ), array(), WP_Repx_Widgets::VERSION );
		}

	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $this->plugin_screen_hook_suffix == $screen->id ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'assets/js/admin.js', __FILE__ ), array( 'jquery' ), WP_Repx_Widgets::VERSION );
		}

	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
		 * Add a settings page for this plugin to the Settings menu.
		 *
		 * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
		 *
		 *        Administration Menus: http://codex.wordpress.org/Administration_Menus
		 */
		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'RepX Widget Settings', $this->plugin_slug ),
			__( 'RepX Widgets', $this->plugin_slug ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);

	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin.php' );
	}

	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    1.0.0
	 */
	public function add_action_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_slug ) . '">' . __( 'Settings', $this->plugin_slug ) . '</a>'
			),
			$links
		);

	}

	/**
	 * NOTE:     Actions are points in the execution of a page or process
	 *           lifecycle that WordPress fires.
	 *
	 *           Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:     Filters are points of execution in which WordPress modifies data
	 *           before saving it or sending it to the browser.
	 *
	 *           Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *           Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

}
