<?php
// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define('EDD_WBBPROF_STORE_URL', 'https://wbcomdesigns.com/'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of your product. This should match the download name in EDD exactly
//define('EDD_WBBPROF_ITEM_NAME', 'PeepSo bbPress Integration'); // you should use your own CONSTANT name, and be sure to replace it throughout this file
define('EDD_WBBPROF_ITEM_NAME', 'buddypress-profanity'); // you should use your own CONSTANT name, and be sure to replace it throughout this file

// the name of the settings page for the license input to be displayed
define('EDD_WBBPROF_PLUGIN_LICENSE_PAGE', 'edd_WBBPROF_license_page');

if (! class_exists('EDD_WBBPROF_Plugin_Updater')) {
    // load our custom updater.
    include dirname(__FILE__) . '/EDD_WBBPROF_Plugin_Updater.php';
}

function edd_WBBPROF_plugin_updater()
{

    // retrieve our license key from the DB.
    $license_key = trim(get_option('edd_wbcom_WBBPROF_license_key'));

    // setup the updater
    $edd_updater = new EDD_WBBPROF_Plugin_Updater(
        EDD_WBBPROF_STORE_URL,
        __FILE__,
        array(
            'version'   => '1.0.0',             // current version number.
            'license'   => $license_key,        // license key (used get_option above to retrieve from DB).
            'item_name' => EDD_WBBPROF_ITEM_NAME,  // name of this plugin.
            'author'    => 'wbcomdesigns',  // author of this plugin.
            'beta'      => false,
        )
    );
}
add_action('admin_init', 'edd_WBBPROF_plugin_updater', 0);


/************************************
 * the code below is just a standard
 * options page. Substitute with
 * your own.
 *************************************/

function edd_wbcom_WBBPROF_license_menu()
{
    add_submenu_page('buddypress_profanity', __('License', 'buddypress-profanity'), __('License', 'buddypress-profanity'), 'manage_options', 'edd_WBBPROF_license_page', 'edd_wbcom_WBBPROF_license_page');
}
add_action('admin_menu', 'edd_wbcom_WBBPROF_license_menu', 50);

function edd_wbcom_WBBPROF_license_page()
{
    $license = get_option('edd_wbcom_WBBPROF_license_key', true);
    $status  = get_option('edd_wbcom_WBBPROF_license_status');
    ?>
    <div class="wrap">
        <h1><?php _e('Plugin License Options', 'buddypress-profanity'); ?></h1>
        <form method="post" action="options.php">

            <?php settings_fields('edd_wbcom_WBBPROF_license'); ?>

            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th scope="row" valign="top">
                            <?php _e('License Key', 'buddypress-profanity'); ?>
                        </th>
                        <td>
                            <input id="edd_wbcom_WBBPROF_license_key" name="edd_wbcom_WBBPROF_license_key" type="text" class="regular-text" value="<?php esc_attr_e($license, 'buddypress-profanity'); ?>" />
                            <label class="description" for="edd_wbcom_WBBPROF_license_key"><?php _e('Enter your license key', 'buddypress-profanity'); ?></label>
                        </td>
                    </tr>
                    <?php  if (false !== $license) { ?>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('License Status', 'buddypress-profanity'); ?>
                            </th>
                            <td>
                                <?php if ($status !== false && $status == 'valid') { ?>
                                    <span style="color:green;"><?php _e('active', 'buddypress-profanity'); ?></span>
                                    <?php wp_nonce_field('edd_wbcom_WBBPROF_nonce', 'edd_wbcom_WBBPROF_nonce'); ?>
                                <?php
} else {
    wp_nonce_field('edd_wbcom_WBBPROF_nonce', 'edd_wbcom_WBBPROF_nonce'); ?>
                                <span style="color:red;"><?php _e('Inactive', 'buddypress-profanity'); ?></span>
                                <?php  } ?>
                            </td>
                        </tr>
                        <?php if ($status !== false && $status == 'valid') { ?>
                        <tr valign="top">
                            <th scope="row" valign="top">
                                <?php _e('Deactivate License', 'buddypress-profanity'); ?>
                            </th>
                            <td>
                                <input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License', 'buddypress-profanity'); ?>"/>
                                <p class="description"><?php _e('Click for deactivate license.', 'buddypress-profanity'); ?></p>
                            </td>
                        </tr>
                        <?php }
                    } ?>
                </tbody>
            </table>
                <?php
                submit_button(__('Save Settings', 'buddypress-profanity'), 'primary', 'edd_WBBPROF_license_activate', true); ?>

        </form>
    <?php
}

function edd_wbcom_WBBPROF_register_option()
{
    // creates our settings in the options table
    register_setting('edd_wbcom_WBBPROF_license', 'edd_wbcom_WBBPROF_license_key', 'edd_WBBPROF_sanitize_license');
}
add_action('admin_init', 'edd_wbcom_WBBPROF_register_option');

function edd_WBBPROF_sanitize_license($new)
{
    $old = get_option('edd_wbcom_WBBPROF_license_key');
    if ($old && $old != $new) {
        delete_option('edd_wbcom_WBBPROF_license_status'); // new license has been entered, so must reactivate
    }
    return $new;
}



/************************************
 * this illustrates how to activate
 * a license key
 *************************************/

function edd_wbcom_WBBPROF_activate_license()
{

    // listen for our activate button to be clicked
    if (isset($_POST['edd_WBBPROF_license_activate'])) {
        // run a quick security check
        if (! check_admin_referer('edd_wbcom_WBBPROF_nonce', 'edd_wbcom_WBBPROF_nonce')) {
            return; // get out if we didn't click the Activate button
        }

        // retrieve the license from the database
        $license =  $_POST['edd_wbcom_WBBPROF_license_key'];

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'activate_license',
            'license'    => $license,
            'item_name'  => urlencode(EDD_WBBPROF_ITEM_NAME), // the name of our product in EDD
            'url'        => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(
            EDD_WBBPROF_STORE_URL,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.', 'buddypress-profanity');
            }
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if (false === $license_data->success) {
                switch ($license_data->error) {
                    case 'expired':
                        $message = sprintf(
                            __('Your license key expired on %s.', 'buddypress-profanity'),
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;

                    case 'revoked':
                        $message = __('Your license key has been disabled.', 'buddypress-profanity');
                        break;

                    case 'missing':
                        $message = __('Invalid license.', 'buddypress-profanity');
                        break;

                    case 'invalid':
                    case 'site_inactive':
                        $message = __('Your license is not active for this URL.', 'buddypress-profanity');
                        break;

                    case 'item_name_mismatch':
                        $message = sprintf(__('This appears to be an invalid license key for %s.', 'buddypress-profanity'), EDD_WBBPROF_ITEM_NAME);
                        break;

                    case 'no_activations_left':
                        $message = __('Your license key has reached its activation limit.', 'buddypress-profanity');
                        break;

                    default:
                        $message = __('An error occurred, please try again.', 'buddypress-profanity');
                        break;
                }
            }
        }

        // Check if anything passed on a message constituting a failure
        if (! empty($message)) {
            $base_url = admin_url('admin.php?page=' . EDD_WBBPROF_PLUGIN_LICENSE_PAGE);
            $redirect = add_query_arg(
                array(
                    'WBBPROF_activation' => 'false',
                    'message'       => urlencode($message),
                ),
                $base_url
            );
            $license = trim($license);
            update_option('edd_wbcom_WBBPROF_license_key', $license);
            update_option('edd_wbcom_WBBPROF_license_status', $license_data->license);
            wp_redirect($redirect);
            exit();
        }

        // $license_data->license will be either "valid" or "invalid"
        $license = trim($license);
        update_option('edd_wbcom_WBBPROF_license_key', $license);
        update_option('edd_wbcom_WBBPROF_license_status', $license_data->license);
        wp_redirect(admin_url('admin.php?page=' . EDD_WBBPROF_PLUGIN_LICENSE_PAGE));
        exit();
    }
}
add_action('admin_init', 'edd_wbcom_WBBPROF_activate_license');


/***********************************************
 * Illustrates how to deactivate a license key.
 * This will decrease the site count
 ***********************************************/

function edd_wbcom_WBBPROF_deactivate_license()
{

    // listen for our activate button to be clicked
    if (isset($_POST['edd_license_deactivate'])) {
        // run a quick security check
        if (! check_admin_referer('edd_wbcom_WBBPROF_nonce', 'edd_wbcom_WBBPROF_nonce')) {
            return; // get out if we didn't click the Activate button
        }

        // retrieve the license from the database
        $license = trim(get_option('edd_wbcom_WBBPROF_license_key'));

        // data to send in our API request
        $api_params = array(
            'edd_action' => 'deactivate_license',
            'license'    => $license,
            'item_name'  => urlencode(EDD_WBBPROF_ITEM_NAME), // the name of our product in EDD
            'url'        => home_url(),
        );

        // Call the custom API.
        $response = wp_remote_post(
            EDD_WBBPROF_STORE_URL,
            array(
                'timeout'   => 15,
                'sslverify' => false,
                'body'      => $api_params,
            )
        );

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            if (is_wp_error($response)) {
                $message = $response->get_error_message();
            } else {
                $message = __('An error occurred, please try again.', 'buddypress-profanity');
            }

            $base_url = admin_url('admin.php?page=' . EDD_WBBPROF_PLUGIN_LICENSE_PAGE);
            $redirect = add_query_arg(
                array(
                    'WBBPROF_activation' => 'false',
                    'message'       => urlencode($message),
                ),
                $base_url
            );

            wp_redirect($redirect);
            exit();
        }

        // decode the license data
        $license_data = json_decode(wp_remote_retrieve_body($response));

        // $license_data->license will be either "deactivated" or "failed"
        if ($license_data->license == 'deactivated') {
            delete_option('edd_wbcom_WBBPROF_license_status');
        }

        wp_redirect(admin_url('admin.php?page=' . EDD_WBBPROF_PLUGIN_LICENSE_PAGE));
        exit();
    }
}
add_action('admin_init', 'edd_wbcom_WBBPROF_deactivate_license');


/************************************
 * this illustrates how to check if
 * a license key is still valid
 * the updater does this for you,
 * so this is only needed if you
 * want to do something custom
 *************************************/

function edd_wbcom_WBBPROF_check_license()
{

    global $wp_version;

    $license = trim(get_option('edd_wbcom_WBBPROF_license_key'));

    $api_params = array(
        'edd_action' => 'check_license',
        'license'    => $license,
        'item_name'  => urlencode(EDD_WBBPROF_ITEM_NAME),
        'url'        => home_url(),
    );

    // Call the custom API.
    $response = wp_remote_post(
        EDD_WBBPROF_STORE_URL,
        array(
            'timeout'   => 15,
            'sslverify' => false,
            'body'      => $api_params,
        )
    );

    if (is_wp_error($response)) {
        return false;
    }

    $license_data = json_decode(wp_remote_retrieve_body($response));

    if ($license_data->license == 'valid') {
        echo 'valid';
        exit;
        // this license is still valid
    } else {
        echo 'invalid';
        exit;
        // this license is no longer valid
    }
}

/**
 * This is a means of catching errors from the activation method above and displaying it to the customer
 */
function edd_wbcom_WBBPROF_admin_notices()
{
    if (isset($_GET['WBBPROF_activation']) && ! empty($_GET['message'])) {
        switch ($_GET['WBBPROF_activation']) {
            case 'false':
                $message = urldecode($_GET['message']);
                ?>
                <div class="error">
                    <p><?php echo $message; ?></p>
                </div>
                <?php
                break;

            case 'true':
            default:
                // Developers can put a custom success message here for when activation is successful if they way.
                break;
        }
    }
}
add_action('admin_notices', 'edd_wbcom_WBBPROF_admin_notices');
