<?php
/**
 * Faqs support template file.
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbbprof-support-setting">
	<div class="wbbprof-tab-header">
		<h3><?php esc_html_e( 'FAQ(s) ', 'buddypress-profanity' ); ?></h3>
	</div>
	<div class="wbbprof-faqs-block-parent-contain">
		<div class="wbbprof-faqs-block-contain">
			<div class="wbbprof-faq-row border">
				<div class="wbbprof-admin-col-12">
					<button class="wbbprof-accordion">
						<?php esc_html_e( 'Does This plugin requires BuddyPress?', 'buddypress-profanity' ); ?>
					</button>
					<div class="wbbprof-panel">
						<p> 
							<?php esc_html_e( 'Yes, It needs you to have BuddyPress installed and activated.', 'buddypress-profanity' ); ?>
						</p>
					</div>
				</div>
				<div class="wbbprof-admin-col-12">
					<button class="wbbprof-accordion">
						<?php esc_html_e( 'Does this plugin filter multiple keywords?', 'buddypress-profanity' ); ?>
					</button>
					<div class="wbbprof-panel">
						<p> 
							<?php esc_html_e( 'Yes, multiple keywords can be set to filter with the setting [Keywords to remove] provied under general tab.', 'buddypress-profanity' ); ?>
						</p>
					</div>
				</div>
				<div class="wbbprof-admin-col-12">
					<button class="wbbprof-accordion">
						<?php esc_html_e( 'How do I specify a character other than defined characers to replace out keywords?' ); ?>
					</button>
					<div class="wbbprof-panel">
						<p> 
							<?php esc_html_e( 'This can be achieved with the help of filters provided in the plugin. To replace keywords with cutsom character write below coed in your functions.php file of active theme or wherever you want.' ); ?>
						</p>
						<pre>
							add_filter( 'wbbprof_word_rendering_symbols', 'custom_wbbprof_word_rendering_symbols', 10, 1 );
							function custom_wbbprof_word_rendering_symbols($rendering_symbols) {

								$rendering_symbols['at_the_rate'] = '[ @] At the rate';
								return $rendering_symbols;
							}
						</pre>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>