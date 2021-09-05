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

if ( isset($_GET['msg']) && $_GET['msg'] == 'success') {
	?>
	<div id="setting-error-settings_updated" class="notice notice-success settings-error is-dismissible"> 
		<p><strong><?php esc_html_e( 'CSV File is successfully imported', 'buddypress-profanity' )?></strong></p>
	</div>
	<?php
}
?>
<div class="wbcom-tab-content">
<form method="post" action="admin.php?action=update_network_options" enctype='multipart/form-data'>
	<?php
	settings_fields( 'buddypress_profanity_general' );
	do_settings_sections( 'buddypress_profanity_general' );
	?>
	<table class="form-table buddypress-profanity-admin-table">
		<tr>
			<th scope="row">
				<label for="blogname"><?php esc_html_e( 'Import Keywords', 'buddypress-profanity' ); ?></label>
			</th>
			<td>
				<input type='file' name='wbbprof_import[keywords]' value=''  />
				
				<input type='hidden' name='wbbprof_import[import]' value='import'  />
				<p class="description" id="tagline-description">
					<?php echo sprintf(esc_html__( 'Import csv file for remove keywords from community. %s', 'buddypress-profanity' ), '<a href="' . esc_url(BPPROF_PLUGIN_URL. 'admin/css/sample-keywords.csv'). '" target="_blank"/>Sample CSV</a>' ); ?>
				</p>
		    </td>
	    </tr>	    
	</table>
	<?php submit_button(); ?>
</form>
</div>
