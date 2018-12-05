<?php
/**
 *
 * This file is used for rendering and saving plugin general settings.
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wbbprof_settings = bp_get_option( 'wbbprof_settings' );
$content_to_filter = content_to_filter_array();
$rendering_symbols = word_rendering_symbols();
?>
<div class="wbcom-tab-content">
<form method="post" action="admin.php?action=update_network_options">
	<?php
	settings_fields( 'buddypress_profanity_general' );
	do_settings_sections( 'buddypress_profanity_general' );
	?>
	<table class="form-table buddypress-profanity-admin-table">
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Keywords to remove', 'buddypress-profanity' ); ?></label></th>
			<td><input name='wbbprof_settings[keywords]' type='text' class="regular-text wbbprof-keywords-text" value='<?php echo isset( $wbbprof_settings['keywords'] ) ? $wbbprof_settings['keywords'] : ''; ?>' placeholder="<?php esc_html_e( 'Keywords to remove', 'buddypress-profanity' ); ?>" /><p class="description" id="tagline-description"><?php esc_html_e( 'Enter keywords here which you want to remove from community.', 'buddypress-profanity' ); ?>
			</p>
		    </td>
	    </tr>
	    <tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Content to be fitered', 'buddypress-profanity' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Content to be fitered', 'buddypress-profanity' ); ?></span></legend>
					<?php foreach ($content_to_filter as $key => $value) {
						if ( isset( $wbbprof_settings['filter_contents'] ) && in_array( $key, $wbbprof_settings['filter_contents'] )) {
							$checked = 'checked';
						}else{
							$checked = '';
						}
						echo '<label class="bpprof-switch">
						<input name="wbbprof_settings[filter_contents][]" value="'.$key.'" type="checkbox" '.$checked.'>
						<div class="bpprof-slider bpprof-round"></div>
						</label><span class="wbbprof-span-text">'.$value.'</span><br>';
						//echo '<label><input name="wbbprof_settings[filter_contents][]" value="'.$key.'" type="checkbox" '.$checked.'> <span class="wbbprof-span-text">'.$value.'</span></label><br>';
					} ?>
				</fieldset>
		    </td>
	    </tr>
	    <tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Word rendering', 'buddypress-profanity' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Word rendering', 'buddypress-profanity' ); ?></span></legend>
					<label>
						<input name="wbbprof_settings[word_render]" value="first" type="radio" <?php ( isset( $wbbprof_settings['word_render'] ) )? checked($wbbprof_settings['word_render'],'first'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'First letter retainded', 'buddypress-profanity' ); ?></span>
						<code>[blog => b***]</code>
					</label>
					<br>
					<label>
						<input name="wbbprof_settings[word_render]" value="last" type="radio" <?php ( isset( $wbbprof_settings['word_render'] ) )? checked($wbbprof_settings['word_render'],'last'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'Last letter retained', 'buddypress-profanity' ); ?></span>
						<code>[blog => ***g]</code>
					</label>
					<br>
					<label>
						<input name="wbbprof_settings[word_render]" value="fisrt_last" type="radio" <?php ( isset( $wbbprof_settings['word_render'] ) )? checked($wbbprof_settings['word_render'], 'fisrt_last'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'Fisrt and Last letter retained', 'buddypress-profanity' ); ?></span>
						<code>[blog => b**g]</code>
					</label>
					<br>
					<label>
						<input name="wbbprof_settings[word_render]" value="all" type="radio" <?php ( isset( $wbbprof_settings['word_render'] ) )? checked($wbbprof_settings['word_render'],'all'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'All letter removed', 'buddypress-profanity' ); ?></span>
						<code>[blog => b**g]</code>
					</label>
					<br>
				</fieldset>
		    </td>
	    </tr>
	    <tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Filter character', 'buddypress-profanity' ); ?></label></th>
			<td>
				<select name="wbbprof_settings[character]">
					<?php
					foreach ($rendering_symbols as $key => $value) {

						if ( isset( $wbbprof_settings['character'] ) && $wbbprof_settings['character'] == $key) {
							$selected = 'selected';
						}else{
							$selected = '';
						}
						echo "<option value='".$key."' ".$selected.">".$value."</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Case matching', 'buddypress-profanity' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Case matching', 'buddypress-profanity' ); ?></span></legend>
					<label>
						<input name="wbbprof_settings[case]" value="case" type="radio" <?php ( isset( $wbbprof_settings['case'] ) )? checked($wbbprof_settings['case'], 'case'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'Case Sensitive', 'buddypress-profanity' ); ?></span>
					</label>
					<br>
					<label>
						<input name="wbbprof_settings[case]" value="incase" type="radio" <?php ( isset( $wbbprof_settings['case'] ) )? checked($wbbprof_settings['case'], 'incase'):''; ?>>
						<span class="wbbprof-span-text"><?php esc_html_e( 'Case Insensitive (recommended)', 'buddypress-profanity' ); ?></span>
					</label>
					<br>
				</fieldset>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Note: Case Insensitive matching type is better as it captures more words!', 'buddypress-profanity' ); ?>
			</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="blogname"><?php esc_html_e( 'Strict filtering', 'buddypress-profanity' ); ?></label></th>
			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e( 'Strict filtering', 'buddypress-profanity' ); ?></span></legend>
					<label>
						<input name="wbbprof_settings[strict_filter]" value="off" type="radio" <?php ( isset( $wbbprof_settings['strict_filter'] ) )? checked($wbbprof_settings['strict_filter'], 'off'):''; ?>>
						<span class="wbbprof-case-span-text"><?php esc_html_e( 'Strict Filtering OFF', 'buddypress-profanity' ); ?></span>
						<code>[eg. ass becomes p***able]</code>
					</label>
					<br>
					<label>
						<input name="wbbprof_settings[strict_filter]" value="on" type="radio" <?php ( isset( $wbbprof_settings['strict_filter'] ) )? checked($wbbprof_settings['strict_filter'], 'on'):''; ?>>
						<span class="wbbprof-case-span-text"><?php esc_html_e( 'Strict Filtering ON (recommended)', 'buddypress-profanity' ); ?></span>
						<code>[eg. ass becomes passable]</code>
					</label>
					<br>
				</fieldset>
				<p class="description" id="tagline-description"><?php esc_html_e( 'Note: When strict filtering is ON, embedded keywords are no longer filtered.', 'buddypress-profanity' ); ?>
			</p>
			</td>
		</tr>
	</table>
	<?php submit_button(); ?>
</form>
</div>