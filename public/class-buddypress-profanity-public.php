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
	 * Plugin general setting.
	 *
	 * @var array $wbbprof_settings
	 */
	private $wbbprof_settings;

	/**
	 * Remove keyword from the community.
	 *
	 * @var array $keywords
	 */
	private $keywords;

	/**
	 * Remove character from the community.
	 *
	 * @var array $character
	 */
	private $character;

	/**
	 * Remove character from the words.
	 *
	 * @var array $word_rendering
	 */
	private $word_rendering;

	/**
	 * Case Insensitive matching type is better as it captures more words.
	 *
	 * @var array $case
	 */
	private $case;

	/**
	 * When strict filtering is ON, embedded keywords are filtered.
	 *
	 * @var array $whole_word
	 */
	private $whole_word;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name      = $plugin_name;
		$this->version          = $version;
		$wbbprof_settings       = bp_get_option( 'wbbprof_settings' );
		$this->wbbprof_settings = &$wbbprof_settings;

		if ( isset( $this->wbbprof_settings['keywords'] ) ) {
			$keywords       = array_map( 'trim', explode( ',', $this->wbbprof_settings['keywords'] ) );
			$keywords       = array_unique( $keywords );
			$this->keywords = &$keywords;
		}

		if ( isset( $this->wbbprof_settings['character'] ) ) {
			$character = $this->wbbprof_settings['character'];
			$symbol    = '';
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
					if ( apply_filters( 'wbbprof_custom_character', $symbol ) ) {
						$symbol = apply_filters( 'wbbprof_custom_character', $symbol );
					} else {
						$symbol = '*';
					}
					break;
			}
			$this->character = &$symbol;
		}

		if ( isset( $this->wbbprof_settings['word_render'] ) ) {
			$word_rendering       = $this->wbbprof_settings['word_render'];
			$this->word_rendering = &$word_rendering;
		} else {
			$this->word_rendering = 'first';
		}

		if ( isset( $this->wbbprof_settings['case'] ) ) {
			$case       = $this->wbbprof_settings['case'];
			$this->case = &$case;
		}

		if ( isset( $this->wbbprof_settings['strict_filter'] ) ) {
			$whole_word       = 'off' == $this->wbbprof_settings['strict_filter'] ? false : true;
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
		if ( is_buddypress() ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/buddypress-profanity-public.css', array(), $this->version, 'all' );
		}

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
		if ( is_buddypress() ) {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/buddypress-profanity-public.js', array( 'jquery' ), $this->version, false );
		}

	}

	/**
	 * Function for filtering activity status updates.
	 *
	 * @param string $content Activity status update string.
	 */
	public function wbbprof_bp_get_activity_content_body($content) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('status_updates', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ('incase' == $this->case) {
								$content = $this->wbbprof_profain_word_i($keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$content = $this->wbbprof_profain_word($keyword, $replacement, $content, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$content = $this->wbbprof_mask_emails($content);
				$content = $this->wbbprof_mask_phone_numbers($content);
			}
		}
		return $content;
	}

	/**
	 * Function for filtering activity comment.
	 *
	 * @param string $content Activity comment string.
	 */
	public function wbbprof_bp_activity_comment_content($content) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('activity_comments', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ('incase' == $this->case) {
								$content = $this->wbbprof_profain_word_i($keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$content = $this->wbbprof_profain_word($keyword, $replacement, $content, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$content = $this->wbbprof_mask_emails($content);
				$content = $this->wbbprof_mask_phone_numbers($content);
			}
		}
		return $content;
	}

	/**
	 * Function for filtering message content.
	 *
	 * @param string $content Message string.
	 */
	public function wbbprof_bp_get_the_thread_message_content($content) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('messages', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ('incase' == $this->case) {
								$content = $this->wbbprof_profain_word_i($keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$content = $this->wbbprof_profain_word($keyword, $replacement, $content, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$content = $this->wbbprof_mask_emails($content);
				$content = $this->wbbprof_mask_phone_numbers($content);
			}
		}
		return $content;
	}

	/**
	 * Function for filtering message subject.
	 *
	 * @param string $content Message string.
	 */
	public function wbbprof_bp_get_message_thread_subject($content) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('messages', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ('incase' == $this->case) {
								$content = $this->wbbprof_profain_word_i($keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$content = $this->wbbprof_profain_word($keyword, $replacement, $content, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$content = $this->wbbprof_mask_emails($content);
				$content = $this->wbbprof_mask_phone_numbers($content);
			}
		}
		return $content;
	}

	/**
	 *
	 * Function for word sensoring.
	 *
	 * @param string $wbbprof_render_type Word Rendering type.
	 * @param string $keyword             Keyword to remove.
	 * @param string $char_symbol         Symbol to replace with keywords.
	 */
	public function wbbprof_censor_word( $wbbprof_render_type, $keyword, $char_symbol ) {

		$keyword_length = mb_strlen( $keyword, 'UTF-8' );

		switch ( $wbbprof_render_type ) {
			case 'first':
				$first_keyword = mb_substr( $keyword, 0, 1, 'UTF-8' );
				$keyword       = $first_keyword . str_repeat( $char_symbol, mb_strlen( mb_substr( $keyword, 1 ), 'UTF-8' ) );
				break;
			case 'all':
				$keyword = str_repeat( $char_symbol, mb_strlen( substr( $keyword, 0 ), 'UTF-8' ) );
				break;
			case 'fisrt_last':
			case 'first_last':
				$first_keyword = mb_substr( $keyword, 0, 1, 'UTF-8' );
				$last_keyword  = mb_substr( $keyword, -1, 1, 'UTF-8' );
				$keyword       = $first_keyword . str_repeat( $char_symbol, mb_strlen( mb_substr( $keyword, 2 ), 'UTF-8' ) ) . $last_keyword;
				break;
			case 'last':
				$last_keyword = mb_substr( $keyword, -1, 1, 'UTF-8' );
				$keyword      = str_repeat( $char_symbol, mb_strlen( mb_substr( $keyword, 0, -1 ), 'UTF-8' ) ) . $last_keyword;
				break;
			default:
				$first_keyword = mb_substr( $keyword, 0, 1, 'UTF-8' );
				$last_keyword  = mb_substr( $keyword, -1, 1, 'UTF-8' );
				$keyword       = $first_keyword . str_repeat( $char_symbol, mb_strlen( mb_substr( $keyword, 2 ), 'UTF-8' ) ) . $last_keyword;
				break;
		}
		return $keyword;
	}

	/**
	 *
	 * Function to replace words with character when case sensitive.
	 *
	 * @param string  $fword           The keyword to be replaced.
	 * @param string  $replacement     The keyword to be replaced with.
	 * @param string  $wbbprof_content The content to find the keyword.
	 * @param boolean $whole_word      Strict filtering or not.
	 */
	public function wbbprof_profain_word( $fword, $replacement, $wbbprof_content, $whole_word = true ) {
		$fword   = str_replace( '/', '\\/', preg_quote( $fword ) ); // allow '/' in keywords.
		$pattern = $whole_word ? "/\b$fword\b/" : "/$fword/";

		$wbbprof_content = preg_replace( $pattern, $replacement, $wbbprof_content );

		return $wbbprof_content;
	}

	/**
	 *
	 * Function to replace words with character when case insensitive.
	 *
	 * @param string  $fword               The keyword to be replaced.
	 * @param string  $replacement         The keyword to be replaced with.
	 * @param string  $wbbprof_content     The content to find the keyword.
	 * @param string  $wbbprof_render_type Word Rendering type.
	 * @param string  $keyword             Keyword to remove.
	 * @param string  $char_symbol         Symbol to replace with keywords.
	 * @param boolean $whole_word          Strict filtering or not.
	 */
	public function wbbprof_profain_word_i( $fword, $replacement, $wbbprof_content, $wbbprof_render_type, $keyword, $char_symbol, $whole_word = true ) {

		$fword   = str_replace( '/', '\\/', preg_quote( $fword ) ); // allow '/' in keywords.
		$pattern = $whole_word ? "/\b$fword\b/i" : "/$fword/i";

		$wbbprof_content = preg_replace_callback(
			$pattern,
			function( $m ) use ( $wbbprof_render_type, $keyword, $char_symbol ) {
				return $this->wbbprof_censor_word( $wbbprof_render_type, $m[0], $char_symbol );
			},
			$wbbprof_content
		);
		return $wbbprof_content;
	}

	/**
	 * Function for filtering bbPress title.
	 *
	 * @param string $title BBPress title content.
	 * @param int    $bbp_id BBPress post ID.
	 */
	public function wbbprof_bbp_get_title($title, $bbp_id) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('bbpress_title', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ($this->case == 'incase') {
								$title = $this->wbbprof_profain_word_i($keyword, $replacement, $title, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$title = $this->wbbprof_profain_word($keyword, $replacement, $title, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$title = $this->wbbprof_mask_emails($title);
				$title = $this->wbbprof_mask_phone_numbers($title);
			}
		}
		return $title;
	}

	/**
	 * Function for filtering bbPress content.
	 *
	 * @param string $content BBPress content.
	 * @param int    $bbp_id BBPress post ID.
	 */
	public function wbbprof_bbp_get_reply_content($content, $bbp_id) {
		if (!empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array('bbpress_content', $this->wbbprof_settings['filter_contents'])) {
				if (is_array($this->keywords)) {
					foreach ($this->keywords as $key => $keyword) {
						$keyword = trim($keyword);
						if (strlen($keyword) > 2) {
							$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
							if ($this->case == 'incase') {
								$content = $this->wbbprof_profain_word_i($keyword, $replacement, $content, $this->word_rendering, $keyword, $this->character, $this->whole_word);
							} else {
								$content = $this->wbbprof_profain_word($keyword, $replacement, $content, $this->whole_word);
							}
						}
					}
				}
				
				// Apply email and phone masking
				$content = $this->wbbprof_mask_emails($content);
				$content = $this->wbbprof_mask_phone_numbers($content);
			}
		}
		return $content;
	}


	public function wbbprof_bp_core_replace_tokens_in_text( $text, $tokens ) {

		$unescaped = array();
		$escaped   = array();

		foreach ( $tokens as $token => $value ) {
			if ( ! is_string( $value ) && is_callable( $value ) ) {
				$value = call_user_func( $value );
			}

			// Tokens could be objects or arrays.
			if ( ! is_scalar( $value ) ) {
				continue;
			}

			$unescaped[ '{{{' . $token . '}}}' ] = $this->wbbprof_bp_get_activity_content_body( $value );
			$escaped[ '{{' . $token . '}}' ]     = esc_html( $this->wbbprof_bp_get_activity_content_body( $value ) );
		}

		$text = strtr( $text, $unescaped );  // Do first.
		$text = strtr( $text, $escaped );

		return $text;
	}
	
	/**
	 * Mask email addresses in content
	 *
	 * @param string $content Content to filter
	 * @return string Filtered content with masked emails
	 */
	public function wbbprof_mask_emails($content) {
		if (!isset($this->wbbprof_settings['mask_emails']) || $this->wbbprof_settings['mask_emails'] !== 'on') {
			return $content;
		}
		
		// Regex pattern to catch email addresses
		$email_pattern = '/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}\b/';
		
		// Replace email with masked version
		return preg_replace_callback($email_pattern, function($matches) {
			$email = $matches[0];
			$parts = explode('@', $email);
			
			if (count($parts) !== 2) {
				return $email; // Not a valid email
			}
			
			$username = $parts[0];
			$domain = $parts[1];
			
			// Keep first and last character of username, mask the rest
			if (strlen($username) > 2) {
				$first_char = substr($username, 0, 1);
				$last_char = substr($username, -1);
				$masked_username = $first_char . str_repeat($this->character, strlen($username) - 2) . $last_char;
			} else {
				$masked_username = str_repeat($this->character, strlen($username));
			}
			
			// Keep domain extension but mask the domain name
			$domain_parts = explode('.', $domain);
			$tld = array_pop($domain_parts);
			$domain_name = implode('.', $domain_parts);
			
			if (strlen($domain_name) > 2) {
				$first_char = substr($domain_name, 0, 1);
				$masked_domain = $first_char . str_repeat($this->character, strlen($domain_name) - 1);
			} else {
				$masked_domain = str_repeat($this->character, strlen($domain_name));
			}
			
			return $masked_username . '@' . $masked_domain . '.' . $tld;
		}, $content);
	}

	/**
	 * Mask phone numbers in content
	 *
	 * @param string $content Content to filter
	 * @return string Filtered content with masked phone numbers
	 */
	public function wbbprof_mask_phone_numbers($content) {
		if (!isset($this->wbbprof_settings['mask_phones']) || $this->wbbprof_settings['mask_phones'] !== 'on') {
			return $content;
		}
		
		// Array of regex patterns to catch different phone number formats
		$phone_patterns = array(
			// International format with + (e.g., +1-123-456-7890)
			'/\+\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1,4}[-\s]?\d{1,4}/',
			
			// US format with parentheses (e.g., (123) 456-7890)
			'/\(\d{3}\)[-\s]?\d{3}[-\s]?\d{4}/',
			
			// Simple 10-digit format with or without separators (e.g., 123-456-7890, 1234567890)
			'/\b\d{3}[-\s]?\d{3}[-\s]?\d{4}\b/',
			
			// 11-digit format starting with 1 (e.g., 1-123-456-7890)
			'/\b1[-\s]?\d{3}[-\s]?\d{3}[-\s]?\d{4}\b/',
			
			// International format with 00 (e.g., 00123456789)
			'/\b00\d{1,14}\b/',
		);
		
		// Process each pattern
		foreach ($phone_patterns as $pattern) {
			$content = preg_replace_callback($pattern, function($matches) {
				$phone = $matches[0];
				
				// Extract all digits
				$digits_only = preg_replace('/\D/', '', $phone);
				$length = strlen($digits_only);
				
				// Get non-digit characters to preserve format
				$format_chars = array();
				preg_match_all('/\D/', $phone, $format_chars);
				$format_chars = $format_chars[0];
				
				// Create masked version - keep first digit and last 2 digits
				$masked = '';
				if ($length > 3) {
					$masked .= substr($digits_only, 0, 1); // Keep first digit
					$masked .= str_repeat($this->character, $length - 3); // Mask middle
					$masked .= substr($digits_only, -2); // Keep last 2 digits
				} else {
					$masked = str_repeat($this->character, $length);
				}
				
				// Reconstruct the phone number with original format
				$result = '';
				$digit_idx = 0; // Initialize counter for each phone number
				
				for ($i = 0; $i < strlen($phone); $i++) {
					if (ctype_digit($phone[$i])) {
						$result .= $masked[$digit_idx];
						$digit_idx++;
					} else {
						$result .= $phone[$i]; // Preserve formatting character
					}
				}
				
				return $result;
			}, $content);
		}
		
		return $content;
	}

}
