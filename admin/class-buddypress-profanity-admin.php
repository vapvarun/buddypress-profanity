<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Profanity
 * @subpackage Buddypress_Profanity/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buddypress_Profanity
 * @subpackage Buddypress_Profanity/admin
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */
class Buddypress_Profanity_Admin {

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

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
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
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Profanity_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Profanity_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if ( isset($_GET['page']) && $_GET['page'] == 'buddypress_profanity' ) {
			global $wp_styles;
			$srcs = array_map( 'basename', (array) wp_list_pluck( $wp_styles->registered, 'src' ) );

			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-profanity-admin.css', array(), $this->version, 'all' );

			if ( in_array( 'selectize.css', $srcs, true ) || in_array( 'selectize.min.css', $srcs, true ) ) {
				/* echo 'font-awesome.css registered'; */
			} else {
				wp_enqueue_style( 'wbbprof-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css', array(), '1.0.0', 'all' );
			}
		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Buddypress_Profanity_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Buddypress_Profanity_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		
		if ( isset($_GET['page']) && $_GET['page'] == 'buddypress_profanity' ) {
			wp_enqueue_script( $this->plugin_name.'selectize', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), '1.0.0', false );

			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-profanity-admin.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Register admin menu for plugin.
	 *
	 * @since    1.0.0
	 */
	public function wbbprof_add_admin_menu() {

		if ( empty ( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) ) {
			
			// add_menu_page( esc_html__( 'WBCOM', 'buddypress-profanity' ), __( 'WBCOM', 'buddypress-profanity' ), 'manage_options', 'wbcomplugins', array( $this, 'wbbprof_buddypress_profanity_settings_page' ), BPPROF_PLUGIN_URL . 'admin/wbcom/assets/imgs/bulb.png', 59 );

			add_menu_page( esc_html__( 'WB Plugins', 'buddypress-profanity' ), esc_html__( 'WB Plugins', 'buddypress-profanity' ), 'manage_options', 'wbcomplugins', array( $this, 'wbbprof_buddypress_profanity_settings_page' ), 'dashicons-lightbulb', 59 );
		 	add_submenu_page( 'wbcomplugins', esc_html__( 'General', 'buddypress-profanity' ), esc_html__( 'General', 'buddypress-profanity' ), 'manage_options', 'wbcomplugins' );
			}
		add_submenu_page( 'wbcomplugins', esc_html__( 'Buddypress Profanity Settings Page', 'buddypress-profanity' ), esc_html__( 'BuddyPress Profanity', 'buddypress-profanity' ), 'manage_options', 'buddypress_profanity', array( $this, 'wbbprof_buddypress_profanity_settings_page' ) );

		// add_menu_page( __( 'Buddypress Profanity Settings Page', 'buddypress-profanity' ), __( 'BuddyPress Profanity', 'buddypress-profanity' ), 'manage_options', 'buddypress_profanity', array( $this, 'wbbprof_buddypress_profanity_settings_page' ), 'dashicons-filter', 60 );

	}

	/**
	 * Callable function for admin menu setting page.
	 *
	 * @since    1.0.0
	 */
	public function wbbprof_buddypress_profanity_settings_page() {

		$current = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'general';
		?>
		<div class="wrap">
			<div class="bpprof-header">
				<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
				<h1 class="wbcom-plugin-heading">
					<?php esc_html_e( 'BuddyPress Profanity Settings', 'buddypress-profanity' ); ?>
				</h1>
			</div>
		<div class="wbcom-admin-settings-page">
		<?php
		$wbbprof_tabs = array(
			'general'        => __( 'General', 'buddypress-profanity' ),
			'support'        => __( 'Support', 'buddypress-profanity' ),
		);

    	$tab_html = '<div class="wbcom-tabs-section"><h2 class="nav-tab-wrapper">';
		foreach ( $wbbprof_tabs as $wbbprof_tab => $wbbpro_name ) {
			$class     = ( $wbbprof_tab == $current ) ? 'nav-tab-active' : '';
			$tab_html .= '<a class="nav-tab ' . $class . '" href="admin.php?page=buddypress_profanity&tab=' . $wbbprof_tab . '">' . $wbbpro_name . '</a>';
		}
		$tab_html .= '</h2></div>';
		echo $tab_html;
		include 'inc/wbbprof-tabs-options.php';
		echo '</div>'; /* end of .wbcom-admin-settings-page */
		echo '</div>'; /* end of .wrap div. */

	}

	/**
	 * Function to register admin settings.
	 *
	 * @since    1.0.0
	 */
	public function wbbprof_admin_register_settings() {
		if(isset($_POST['wbbprof_settings'])){
			bp_update_option('wbbprof_settings',$_POST['wbbprof_settings']);
			wp_redirect($_POST['_wp_http_referer']);
			exit();
		}
	}

}
