<?php
/**
 *
 * This file is used for defining functions for use in admin and public.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Çontent to filter array (admin end use).
 *
 */
function content_to_filter_array(){
	$content_filter = array(
		'status_updates'    => __( 'Status Updates', 'buddypress-profanity' ),
		'activity_comments' => __( 'Activity Commments', 'buddypress-profanity' ),
		'messages'			=> __( 'Messages', 'buddypress-profanity' )
	);
	return $content_filter = apply_filters( 'wbbprof_content_to_filter_array', $content_filter );
}

/**
 *
 * Filter character symbol array (admin end use).
 *
 */
function word_rendering_symbols(){
	$rendering_symbols = array(
		'asterisk'    => __( '[*] Asterisk', 'buddypress-profanity' ),
		'dollar'      => __( '[$] Dollar', 'buddypress-profanity' ),
		'question'    => __( '[?] Question', 'buddypress-profanity' ),
		'exclamation' => __( '[!] Exclamation', 'buddypress-profanity' ),
		'hyphen'      => __( '[-] Hyphen', 'buddypress-profanity' ),
		'hash'        => __( '[#] Hash', 'buddypress-profanity' ),
		'tilde'       => __( '[~] Tilde', 'buddypress-profanity' ),
		'blank'       => __( '[ ] Blank', 'buddypress-profanity' ),
	);
	return $rendering_symbols = apply_filters( 'wbbprof_word_rendering_symbols', $rendering_symbols );
}
