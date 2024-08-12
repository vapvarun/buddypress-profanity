<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    BuddyPress_Profanity
 * @subpackage BuddyPress_Profanity/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples of hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    BuddyPress_Profanity
 * @subpackage BuddyPress_Profanity/public
 * @author     Wbcom Designs <admin@wbcomdesigns.com>
 */
class Buddypress_Profanity_Public
{

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
	 * Plugin general settings.
	 *
	 * @var array $wbbprof_settings
	 */
	private $wbbprof_settings;

	/**
	 * List of keywords to filter.
	 *
	 * @var array $keywords
	 */
	private $keywords;

	/**
	 * Character used to replace the censored words.
	 *
	 * @var string $character
	 */
	private $character;

	/**
	 * Word rendering method.
	 *
	 * @var string $word_rendering
	 */
	private $word_rendering;

	/**
	 * Case sensitivity option.
	 *
	 * @var string $case
	 */
	private $case;

	/**
	 * Strict word filtering option.
	 *
	 * @var bool $whole_word
	 */
	private $whole_word;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param    string $plugin_name The name of the plugin.
	 * @param    string $version The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name      = sanitize_key($plugin_name);
		$this->version          = sanitize_text_field($version);
		$this->wbbprof_settings = bp_get_option('wbbprof_settings');

		$this->initialize_settings();
	}

	/**
	 * Initialize plugin settings.
	 */
	private function initialize_settings()
	{

		if (isset($this->wbbprof_settings['keywords'])) {
			$this->keywords = array_unique(array_map('trim', explode(',', $this->wbbprof_settings['keywords'])));
		}

		$this->character = $this->determine_character();

		$this->word_rendering = isset($this->wbbprof_settings['word_render']) ? $this->wbbprof_settings['word_render'] : 'first';

		$this->case = isset($this->wbbprof_settings['case']) ? $this->wbbprof_settings['case'] : 'incase';

		$this->whole_word = isset($this->wbbprof_settings['strict_filter']) && 'off' !== $this->wbbprof_settings['strict_filter'];
	}

	/**
	 * Determine the character to replace censored words with.
	 *
	 * @return string
	 */
	private function determine_character()
	{
		if (isset($this->wbbprof_settings['character'])) {
			$character = $this->wbbprof_settings['character'];
			$symbol    = '';

			switch ($character) {
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
					$symbol = apply_filters('wbbprof_custom_character', '*');
					break;
			}
			return $symbol;
		}

		return '*'; // Default character.
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
		if (is_buddypress()) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/buddypress-profanity-public.css', array(), $this->version, 'all');
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		if (is_buddypress()) {
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/buddypress-profanity-public.js', array('jquery'), $this->version, false);
		}
	}

	/**
	 * Filter activity status updates.
	 *
	 * @param string $content Activity status update string.
	 * @return string Filtered content.
	 */
	public function wbbprof_bp_get_activity_content_body($content)
	{
		return $this->filter_content($content, 'status_updates');
	}

	/**
	 * Filter activity comments.
	 *
	 * @param string $content Activity comment string.
	 * @return string Filtered content.
	 */
	public function wbbprof_bp_activity_comment_content($content)
	{
		return $this->filter_content($content, 'activity_comments');
	}

	/**
	 * Filter message content.
	 *
	 * @param string $content Message content.
	 * @return string Filtered content.
	 */
	public function wbbprof_bp_get_the_thread_message_content($content)
	{
		return $this->filter_content($content, 'messages');
	}

	/**
	 * Filter message subject.
	 *
	 * @param string $content Message subject.
	 * @return string Filtered content.
	 */
	public function wbbprof_bp_get_message_thread_subject($content)
	{
		return $this->filter_content($content, 'messages');
	}

	/**
	 * Filter content based on type.
	 *
	 * @param string $content Content to be filtered.
	 * @param string $content_type Type of content to filter (status_updates, activity_comments, messages).
	 * @return string Filtered content.
	 */
	private function filter_content($content, $content_type)
	{
		if (! empty($this->wbbprof_settings) && isset($this->wbbprof_settings['filter_contents'])) {
			if (in_array($content_type, $this->wbbprof_settings['filter_contents'], true) && is_array($this->keywords)) {
				foreach ($this->keywords as $keyword) {
					$keyword = trim($keyword);
					if (strlen($keyword) > 2) {
						$replacement = $this->wbbprof_censor_word($this->word_rendering, $keyword, $this->character);
						$content     = ('incase' === $this->case) ?
							$this->wbbprof_profain_word_i($keyword, $replacement, $content) :
							$this->wbbprof_profain_word($keyword, $replacement, $content);
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Function for word censoring.
	 *
	 * @param string $wbbprof_render_type Word Rendering type.
	 * @param string $keyword Keyword to censor.
	 * @param string $char_symbol Symbol to replace with keywords.
	 * @return string Censored word.
	 */
	public function wbbprof_censor_word($wbbprof_render_type, $keyword, $char_symbol)
	{
		switch ($wbbprof_render_type) {
			case 'first':
				$keyword = mb_substr($keyword, 0, 1, 'UTF-8') . str_repeat($char_symbol, mb_strlen(mb_substr($keyword, 1), 'UTF-8'));
				break;
			case 'all':
				$keyword = str_repeat($char_symbol, mb_strlen($keyword, 'UTF-8'));
				break;
			case 'first_last':
				$first_keyword = mb_substr($keyword, 0, 1, 'UTF-8');
				$last_keyword  = mb_substr($keyword, -1, 1, 'UTF-8');
				$keyword       = $first_keyword . str_repeat($char_symbol, mb_strlen(mb_substr($keyword, 2), 'UTF-8')) . $last_keyword;
				break;
			case 'last':
				$last_keyword = mb_substr($keyword, -1, 1, 'UTF-8');
				$keyword      = str_repeat($char_symbol, mb_strlen(mb_substr($keyword, 0, -1), 'UTF-8')) . $last_keyword;
				break;
			default:
				$first_keyword = mb_substr($keyword, 0, 1, 'UTF-8');
				$last_keyword  = mb_substr($keyword, -1, 1, 'UTF-8');
				$keyword       = $first_keyword . str_repeat($char_symbol, mb_strlen(mb_substr($keyword, 2), 'UTF-8')) . $last_keyword;
				break;
		}
		return $keyword;
	}

	/**
	 * Function to replace words with a character when case-sensitive.
	 *
	 * @param string $fword The keyword to be replaced.
	 * @param string $replacement The keyword to be replaced with.
	 * @param string $wbbprof_content The content to find the keyword.
	 * @param bool   $whole_word Strict filtering or not.
	 * @return string Filtered content.
	 */
	public function wbbprof_profain_word($fword, $replacement, $wbbprof_content, $whole_word = true)
	{
		$fword   = str_replace('/', '\\/', preg_quote($fword, '/'));
		$pattern = $whole_word ? "/\b$fword\b/" : "/$fword/";

		return preg_replace($pattern, $replacement, $wbbprof_content);
	}

	/**
	 * Function to replace words with a character when case-insensitive.
	 *
	 * @param string $fword The keyword to be replaced.
	 * @param string $replacement The keyword to be replaced with.
	 * @param string $wbbprof_content The content to find the keyword.
	 * @return string Filtered content.
	 */
	public function wbbprof_profain_word_i($fword, $replacement, $wbbprof_content)
	{
		$fword   = str_replace('/', '\\/', preg_quote($fword, '/'));
		$pattern = $this->whole_word ? "/\b$fword\b/i" : "/$fword/i";

		return preg_replace_callback(
			$pattern,
			function ($matches) use ($replacement) {
				return $replacement;
			},
			$wbbprof_content
		);
	}

	/**
	 * Filter bbPress topic titles.
	 *
	 * @param string $title The topic title.
	 * @param int    $bbp_id The topic ID.
	 * @return string Filtered title.
	 */
	public function wbbprof_bbp_get_title($title, $bbp_id)
	{
		return $this->filter_content($title, 'bbpress_title');
	}

	/**
	 * Filter bbPress reply content.
	 *
	 * @param string $content The reply content.
	 * @param int    $bbp_id The reply ID.
	 * @return string Filtered content.
	 */
	public function wbbprof_bbp_get_reply_content($content, $bbp_id)
	{
		return $this->filter_content($content, 'bbpress_content');
	}

	/**
	 * Replace tokens in text content.
	 *
	 * @param string $text The text content.
	 * @param array  $tokens The tokens to replace.
	 * @return string Filtered text.
	 */
	public function wbbprof_bp_core_replace_tokens_in_text($text, $tokens)
	{
		$unescaped = array();
		$escaped   = array();

		foreach ($tokens as $token => $value) {
			if (! is_string($value) && is_callable($value)) {
				$value = call_user_func($value);
			}

			if (! is_scalar($value)) {
				continue;
			}

			$unescaped['{{{' . $token . '}}}'] = $this->wbbprof_bp_get_activity_content_body($value);
			$escaped['{{' . $token . '}}']     = esc_html($this->wbbprof_bp_get_activity_content_body($value));
		}

		$text = strtr($text, $unescaped); // Replace unescaped first.
		$text = strtr($text, $escaped);

		return $text;
	}
}
