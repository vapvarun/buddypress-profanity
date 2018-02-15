<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Profanity
 * @subpackage Buddypress_Profanity/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Buddypress_Profanity
 * @subpackage Buddypress_Profanity/public
 * @author     wbcomdesigns <admin@wbcomdesigns.com>
 */

class Buddypress_Profanity_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		if ( is_multisite() && is_plugin_active_for_network( plugin_basename( __FILE__ ) ) ) {
			$wbbprof_settings = get_site_option( 'wbbprof_settings' );
		} else {
			$wbbprof_settings = get_option( 'wbbprof_settings' );
		}

		$this->wbbprof_settings = &$wbbprof_settings;

		if( isset( $this->wbbprof_settings['keywords'] ) ){
			$keywords = array_map( 'trim', explode( ',', $this->wbbprof_settings['keywords'] ) );
			$keywords = array_unique( $keywords );
			$this->keywords = &$keywords;
		}

		if( isset( $this->wbbprof_settings['character'] ) ){
			$character = $this->wbbprof_settings['character'];
			$symbol = '';
			switch ( $character ) {
				case 'asterisk':
					$symbol = '*';
					break;
				case 'dollar':
					$symbol = '$';
					break;
				case 'question':
					$symbol = '?';
					break;
				case 'exclamation':
					$symbol = '!';
					break;
				case 'hyphen':
					$symbol = '-';
					break;
				case 'hash':
					$symbol = '#';
					break;
				case 'tilde':
					$symbol = '~';
					break;
				case 'blank':
					$symbol = ' ';
					break;
				default:
					if( apply_filters('wbbprof_custom_character',$symbol) ){
						$symbol = apply_filters('wbbprof_custom_character',$symbol);
					} else {
						$symbol = '*';
					}
					break;
			}
			$this->character = &$symbol;
		}

		if( isset( $this->wbbprof_settings['word_render'] ) ){
			$word_rendering = $this->wbbprof_settings['word_render'];
			$this->word_rendering = &$word_rendering;
		}

		if( isset( $this->wbbprof_settings['case'] ) ) {
			$case = $this->wbbprof_settings['case'];
			$this->case = &$case;
		}

		if( isset( $this->wbbprof_settings['strict_filter'] ) ) {
			$whole_word = $this->wbbprof_settings['strict_filter'] == 'off' ? false : true;
			$this->whole_word = &$whole_word;
		}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-profanity-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-profanity-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 *
	 * Function for filtering activity staus updates.
	 *
	 * @param string $content Activity status update string.
	 */
	public function wbbprof_bp_get_activity_content_body($content) {
		if ( !empty( $this->wbbprof_settings ) && isset( $this->wbbprof_settings['filter_contents'] ) ) {
			if ( in_array( 'status_updates', $this->wbbprof_settings['filter_contents'] ) ) {
				if ( is_array( $this->keywords ) ) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim( $keyword );
						if ( strlen( $keyword ) > 2 ) {
							$replacement = $this->wbbprof_censor_word( $this->word_rendering, $keyword, $this->character );
							if( $this->case == 'incase' ) {
								$content = $this->wbbprof_str_replace_word_i( $keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word ); 
							} else {
								$content = $this->wbbprof_str_replace_word( $keyword, $replacement, $content, $this->whole_word );
							}
							
						}
						
					}
				}
			}
		}
		return $content;
	}

	/**
	 *
	 * Function for filtering activity comment.
	 *
	 * @param string $content Activity comment string.
	 */
	public function wbbprof_bp_activity_comment_content( $content ) {
		if ( !empty( $this->wbbprof_settings ) && isset( $this->wbbprof_settings['filter_contents'] ) ) {
			if ( in_array( 'activity_comments', $this->wbbprof_settings['filter_contents'] ) ) {
				if ( is_array( $this->keywords ) ) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim( $keyword );
						if ( strlen( $keyword ) > 2 ) {
							$replacement = $this->wbbprof_censor_word( $this->word_rendering, $keyword, $this->character );
							if( $this->case == 'incase' ) {
								$content = $this->wbbprof_str_replace_word_i( $keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word ); 
							} else {
								$content = $this->wbbprof_str_replace_word( $keyword, $replacement, $content, $this->whole_word );
							}
							
						}
						
					}
				}
			}
		}
		return $content;
	}

	/**
	 *
	 * Function for filtering message content.
	 *
	 * @param string $content Message string.
	 */
	public function wbbprof_bp_get_the_thread_message_content( $content ) {
		if ( !empty( $this->wbbprof_settings ) && isset( $this->wbbprof_settings['filter_contents'] ) ) {
			if ( in_array( 'messages', $this->wbbprof_settings['filter_contents'] ) ) {
				if ( is_array( $this->keywords ) ) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim( $keyword );
						if ( strlen( $keyword ) > 2 ) {
							$replacement = $this->wbbprof_censor_word( $this->word_rendering, $keyword, $this->character );
							if( $this->case == 'incase' ) {
								$content = $this->wbbprof_str_replace_word_i( $keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word ); 
							} else {
								$content = $this->wbbprof_str_replace_word( $keyword, $replacement, $content, $this->whole_word );
							}
							
						}
						
					}
				}
			}
		}
		return $content;
	}

	/**
	 *
	 * Function for word sensoring.
	 *
	 */
	function wbbprof_censor_word( $wildcard_filter_type, $keyword, $wildcard ) {
		switch ( $wildcard_filter_type ) {
			case 'first':
				$keyword = substr( $keyword, 0, 1 ) . str_repeat( $wildcard, strlen( substr( $keyword, 1 ) ) );
				break;
			case 'all':
				$keyword = str_repeat( $wildcard, strlen( substr( $keyword, 0 ) ) );
				break;
			case 'first_last':
				$keyword = substr( $keyword, 0, 1 ) . str_repeat( $wildcard, strlen( substr( $keyword, 2 ) ) ) . substr( $keyword, - 1, 1 );
				break;
			case 'last':
				$keyword = str_repeat( $wildcard, strlen( substr( $keyword, 0, -1 ) ) ) . substr( $keyword, - 1, 1 );
				break;
			default:
				$keyword = substr( $keyword, 0, 1 ) . str_repeat( $wildcard, strlen( substr( $keyword, 2 ) ) ) . substr( $keyword, - 1, 1 );
				break;
		}
		return $keyword;
	}

	/**
	 *
	 * Function to replace words with character when case sensitive.
	 *
	 */
	function wbbprof_str_replace_word( $needle, $replacement, $haystack, $whole_word = true ) {
		$needle   = str_replace( '/', '\\/', preg_quote( $needle ) ); // allow '/' in keywords
		$pattern  = $whole_word ? "/\b$needle\b/" : "/$needle/";
		$haystack = preg_replace( $pattern, $replacement, $haystack );

		return $haystack;
	}

	/**
	 *
	 * Function to replace words with character when case insensitive.
	 *
	 */
	function wbbprof_str_replace_word_i( $needle, $replacement, $haystack, $wildcard_filter_type, $keyword, $wildcard, $whole_word = true ) {

		$needle   = str_replace( '/', '\\/', preg_quote( $needle ) ); // allow '/' in keywords
		$pattern  = $whole_word ? "/\b$needle\b/i" : "/$needle/i";
		$haystack = preg_replace_callback(
			$pattern,
			function($m) use($wildcard_filter_type, $keyword, $wildcard) {
				return $this->wbbprof_censor_word( $wildcard_filter_type, $m[0], $wildcard );
			},
			$haystack);
		return $haystack;
    }

}
