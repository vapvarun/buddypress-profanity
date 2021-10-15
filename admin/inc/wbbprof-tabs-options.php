<?php
/**
 * This template file is used for fetching desired options page file at admin settings.
 *
 * @link       http://www.wbcomdesigns.com
 * @since      1.0.0
 *
 * @package    Buddypress_Profanity
 * @subpackage Buddypress_Profanity/inc
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( isset( $_GET['tab'] ) ) {
	$wbbprof_tab = sanitize_text_field( $_GET['tab'] );
} else {
	$wbbprof_tab = 'welcome';
}

wbbprof_include_setting_tabs( $wbbprof_tab );

/**
 *
 * Function to select desired file for tab option.
 *
 * @param string $wbbprof_tab The current tab string.
 */
function wbbprof_include_setting_tabs( $wbbprof_tab ) {

	switch ( $wbbprof_tab ) {
		case 'welcome':
			include 'wbbprof-welcome-page.php';
			break;
		case 'general':
			include 'wbbprof-general-setting-tab.php';
			break;
		case 'import':
			include 'wbbprof-import-setting-tab.php';
			break;
		case 'support':
			include 'wbbprof-support-setting-tab.php';
			break;
		default:
			include 'wbbprof-welcome-page.php';
			break;
	}

}

