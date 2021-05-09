<?php
/**
 * Plugin Name: Disciple Tools PublicSite - P4M
 * Plugin URI: https://github.com/DiscipleTools/disciple-tools-publicsite-p4m
 * Description: Disciple Tools - Public Skin P4E is intended to help developers and integrator jumpstart their extension of the Disciple Tools system.
 * Text Domain: disciple-tools-publicsite-p4m
 * Domain Path: /languages
 * Version:  0.1
 * Author URI: https://github.com/DiscipleTools
 * GitHub Plugin URI: https://github.com/DiscipleTools/disciple-tools-publicsite-p4m
 * Requires at least: 4.7.0
 * (Requires 4.7+ because of the integration of the REST API at 4.7 and the security requirements of this milestone version.)
 * Tested up to: 5.6
 *
 * @package Disciple_Tools
 * @link    https://github.com/DiscipleTools
 * @license GPL-2.0 or later
 *          https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Gets the instance of the `DT_Publicsite_P4M` class.
 *
 * @since  0.1
 * @access public
 * @return object|bool
 */
function disciple_tools_publicsite_p4m() {
    $disciple_tools_publicsite_p4m_required_dt_theme_version = '1.0';
    $wp_theme = wp_get_theme();
    $version = $wp_theme->version;

    /*
     * Check if the Disciple.Tools theme is loaded and is the latest required version
     */
    $is_theme_dt = strpos( $wp_theme->get_template(), "disciple-tools-theme" ) !== false || $wp_theme->name === "Disciple Tools";
    if ( $is_theme_dt && version_compare( $version, $disciple_tools_publicsite_p4m_required_dt_theme_version, "<" ) ) {
        add_action( 'admin_notices', 'disciple_tools_publicsite_p4m_hook_admin_notice' );
        add_action( 'wp_ajax_dismissed_notice_handler', 'dt_hook_ajax_notice_handler' );
        return false;
    }
    if ( !$is_theme_dt ){
        return false;
    }
    /**
     * Load useful function from the theme
     */
    if ( !defined( 'DT_FUNCTIONS_READY' ) ){
        require_once get_template_directory() . '/dt-core/global-functions.php';
    }

    return DT_Publicsite_P4M::instance();

}
add_action( 'after_setup_theme', 'disciple_tools_publicsite_p4m', 20 );

/**
 * Singleton class for setting up the plugin.
 *
 * @since  0.1
 * @access public
 */
class DT_Publicsite_P4M {

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        require_once( 'frontpage/frontpage.php');

        $is_rest = dt_is_rest();
        if ( $is_rest && strpos( dt_get_url_path(), 'publicsite_p4m' ) !== false ) {
            require_once( 'rest-api/rest-api.php' );
        }

        $this->i18n();

        if ( is_admin() ) { // adds links to the plugin description area in the plugin admin list.
            add_filter( 'plugin_row_meta', [ $this, 'plugin_description_links' ], 10, 4 );
        }

        if ( is_admin() ){
            add_action( 'admin_menu', 'p4m_admin_menu' );

            function p4m_admin_menu() {
                add_menu_page( 'Landing Page', 'Landing Page', 'read', 'landing_page', 'p4m_landing_admin_page', 'dashicons-admin-generic', 59 );
            }

            function p4m_landing_admin_page(){
                $slug = 'landing_page';

                if ( !current_user_can( 'manage_options' ) ) { // manage dt is a permission that is specific to Disciple Tools and allows admins, strategists and dispatchers into the wp-admin
                    wp_die( esc_attr__( 'You do not have sufficient permissions to access this page.' ) );
                }

                if ( isset( $_GET["tab"] ) ) {
                    $tab = sanitize_key( wp_unslash( $_GET["tab"] ) );
                } else {
                    $tab = 'settings';
                }

                $link = 'admin.php?page='.$slug.'&tab=';

                ?>
                <div class="wrap">
                    <h2>Pray4Movement Landing Page</h2>
                    <h2 class="nav-tab-wrapper">
                        <a href="<?php echo esc_attr( $link ) . 'settings' ?>"
                           class="nav-tab <?php echo esc_html( ( $tab == 'settings' || !isset( $tab ) ) ? 'nav-tab-active' : '' ); ?>">Settings
                        </a>
                        <a href="<?php echo esc_attr( $link ) . 'notes' ?>" class="nav-tab <?php echo esc_html( ( $tab == 'notes' ) ? 'nav-tab-active' : '' ); ?>">
                            Notes
                        </a>
                    </h2>

                    <?php
                    switch ($tab) {
                        case "settings":
                            p4m_settings();
                            break;
                        case "notes":
                            p4m_notes();
                            break;
                        default:
                            break;
                    }
                    ?>

                </div><!-- End wrap -->
                <?php
            }

            function p4m_settings(){

                $content = get_option('landing_content', [] );
                if ( isset( $_POST['landing_page'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['landing_page'] ) ), 'landing_page'.get_current_user_id() ) ) {

                    dt_write_log($_POST);

                    if ( isset( $_POST['title'] ) )  {
                        $content['title'] = sanitize_text_field( wp_unslash( $_POST['title'] ) );
                    }
                    if ( isset( $_POST['description'] ) )  {
                        $content['description'] = sanitize_text_field( wp_unslash( $_POST['description'] ) );
                    }
                    if ( isset( $_POST['location'] ) )  {
                        $content['location'] = sanitize_text_field( wp_unslash( $_POST['location'] ) );
                    }
                    if ( isset( $_POST['logo_url'] ) )  {
                        $content['logo_url'] = sanitize_text_field( wp_unslash( $_POST['logo_url'] ) );
                    }
                    if ( isset( $_POST['background_image_url'] ) )  {
                        $content['background_image_url'] = sanitize_text_field( wp_unslash( $_POST['background_image_url'] ) );
                    }
                    if ( isset( $_POST['facebook_url'] ) )  {
                        $content['facebook_url'] = sanitize_text_field( wp_unslash( $_POST['facebook_url'] ) );
                    }
                    if ( isset( $_POST['facebook_events_url'] ) )  {
                        $content['facebook_events_url'] = sanitize_text_field( wp_unslash( $_POST['facebook_events_url'] ) );
                    }
                    if ( isset( $_POST['instagram_url'] ) )  {
                        $content['instagram_url'] = sanitize_text_field( wp_unslash( $_POST['instagram_url'] ) );
                    }
                    if ( isset( $_POST['twitter_url'] ) )  {
                        $content['twitter_url'] = sanitize_text_field( wp_unslash( $_POST['twitter_url'] ) );
                    }
                    if ( isset( $_POST['mailchimp_form_url'] ) )  {
                        $content['mailchimp_form_url'] = sanitize_text_field( wp_unslash( $_POST['mailchimp_form_url'] ) );
                    }
                    if ( isset( $_POST['mailchimp_form_hidden_id'] ) )  {
                        $content['mailchimp_form_hidden_id'] = sanitize_text_field( wp_unslash( $_POST['mailchimp_form_hidden_id'] ) );
                    }
                    if ( isset( $_POST['contact_form'] ) )  {
                        $content['contact_form'] = wp_unslash( $_POST['contact_form'] );
                    }
                    if ( isset( $_POST['samples_section'] ) )  {
                        $content['samples_section'] = sanitize_text_field( wp_unslash( $_POST['samples_section'] ) );
                    }
                    if ( isset( $_POST['stats_population'] ) )  {
                        $content['stats_population'] = sanitize_text_field( wp_unslash( $_POST['stats_population'] ) );
                    }
                    if ( isset( $_POST['stats_cities'] ) )  {
                        $content['stats_cities'] = sanitize_text_field( wp_unslash( $_POST['stats_cities'] ) );
                    }
                    if ( isset( $_POST['stats_trainings'] ) )  {
                        $content['stats_trainings'] = sanitize_text_field( wp_unslash( $_POST['stats_trainings'] ) );
                    }
                    if ( isset( $_POST['stats_churches'] ) )  {
                        $content['stats_churches'] = sanitize_text_field( wp_unslash( $_POST['stats_churches'] ) );
                    }

                    update_option( 'landing_content', $content, true );
                    $content = get_option('landing_content');
                }
                ?>
                <div class="wrap">
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <!-- Main Column -->

                                <!-- Box -->
                                <form method="post">
                                    <?php wp_nonce_field('landing_page'.get_current_user_id(), 'landing_page' ) ?>
                                    <table class="widefat striped">
                                        <thead>
                                        <tr>
                                            <th colspan="2">Configuration</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td style="width:150px;">
                                                Title
                                            </td>
                                            <td>
                                                <input type="text" name="title" class="regular-text" value="<?php echo esc_html( $content['title'] ?? '' ) ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Description
                                            </td>
                                            <td>
                                                <input type="text" name="description" class="regular-text" value="<?php echo $content['description'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Location
                                            </td>
                                            <td>
                                                <input type="text" name="location" class="regular-text" value="<?php echo esc_html( $content['location'] ?? '' ) ?>" /> (i.e. state)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Logo URL
                                            </td>
                                            <td>
                                                <input type="text" name="logo_url" class="regular-text" value="<?php echo $content['logo_url'] ?? '' ?>" /> (Leave empty to use default logo)
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Background Image URL
                                            </td>
                                            <td>
                                                <input type="text" name="background_image_url" class="regular-text" value="<?php echo $content['background_image_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Facebook URL
                                            </td>
                                            <td>
                                                <input type="text" name="facebook_url" class="regular-text" value="<?php echo $content['facebook_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Facebook Events URL
                                            </td>
                                            <td>
                                                <input type="text" name="facebook_events_url" class="regular-text" value="<?php echo $content['facebook_events_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Instagram URL
                                            </td>
                                            <td>
                                                <input type="text" name="instagram_url" class="regular-text" value="<?php echo $content['instagram_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Twitter URL
                                            </td>
                                            <td>
                                                <input type="text" name="twitter_url" class="regular-text" value="<?php echo $content['twitter_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Mailchimp Form URL
                                            </td>
                                            <td>
                                                <input type="text" name="mailchimp_form_url" class="regular-text" value="<?php echo $content['mailchimp_form_url'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Mailchimp Form Hidden Field ID
                                            </td>
                                            <td>
                                                <input type="text" name="mailchimp_form_hidden_id" class="regular-text" value="<?php echo $content['mailchimp_form_hidden_id'] ?? '' ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="width:150px;">
                                                "Contact Us" Lead Form
                                            </td>
                                            <td>
                                                <textarea type="text" name="contact_form" class="regular-text" ><?php echo $content['contact_form'] ?? '' ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Population
                                            </td>
                                            <td>
                                                <input type="text" name="stats_population" class="regular-text" value="<?php echo $content['stats_population'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Cities
                                            </td>
                                            <td>
                                                <input type="text" name="stats_cities" class="regular-text" value="<?php echo $content['stats_cities'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Trainings Needed
                                            </td>
                                            <td>
                                                <input type="text" name="stats_trainings" class="regular-text" value="<?php echo $content['stats_trainings'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                New Churches Needed
                                            </td>
                                            <td>
                                                <input type="text" name="stats_churches" class="regular-text" value="<?php echo $content['stats_churches'] ?? '' ?>" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:150px;">
                                                Samples Section
                                            </td>
                                            <td>
                                                <select name="samples_section">
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <button type="submit" class="button">Update</button>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <br>

                                </form>
                                <!-- End Box -->



                                <!-- End Main Column -->
                            </div><!-- end post-body-content -->
                            <div id="postbox-container-1" class="postbox-container">
                                <!-- Right Column -->



                                <!-- End Right Column -->
                            </div><!-- postbox-container 1 -->
                            <div id="postbox-container-2" class="postbox-container">
                            </div><!-- postbox-container 2 -->
                        </div><!-- post-body meta box container -->
                    </div><!--poststuff end -->
                </div><!-- wrap end -->
                <?php
            }
            function p4m_notes(){

            }

        }

    }

    /**
     * Filters the array of row meta for each/specific plugin in the Plugins list table.
     * Appends additional links below each/specific plugin on the plugins page.
     */
    public function plugin_description_links( $links_array, $plugin_file_name, $plugin_data, $status ) {
        if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
            // You can still use `array_unshift()` to add links at the beginning.

            $links_array[] = '<a href="https://disciple.tools">Disciple.Tools Community</a>';

        }

        return $links_array;
    }

    /**
     * Method that runs only when the plugin is activated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function activation() {
        // add elements here that need to fire on activation
    }

    /**
     * Method that runs only when the plugin is deactivated.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public static function deactivation() {
        // add functions here that need to happen on deactivation
        delete_option( 'dismissed-disciple-tools-publicsite-p4m' );
    }

    /**
     * Loads the translation files.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function i18n() {
        $domain = 'disciple-tools-publicsite-p4m';
        load_plugin_textdomain( $domain, false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ). 'languages' );
    }

    /**
     * Magic method to output a string if trying to use the object as a string.
     *
     * @since  0.1
     * @access public
     * @return string
     */
    public function __toString() {
        return 'disciple-tools-publicsite-p4m';
    }

    /**
     * Magic method to keep the object from being cloned.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, 'Whoah, partner!', '0.1' );
    }

    /**
     * Magic method to keep the object from being unserialized.
     *
     * @since  0.1
     * @access public
     * @return void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, 'Whoah, partner!', '0.1' );
    }

    /**
     * Magic method to prevent a fatal error when calling a method that doesn't exist.
     *
     * @param string $method
     * @param array $args
     * @return null
     * @since  0.1
     * @access public
     */
    public function __call( $method = '', $args = array() ) {
        _doing_it_wrong( "disciple_tools_publicsite_p4m::" . esc_html( $method ), 'Method does not exist.', '0.1' );
        unset( $method, $args );
        return null;
    }
}


// Register activation hook.
register_activation_hook( __FILE__, [ 'DT_Publicsite_P4M', 'activation' ] );
register_deactivation_hook( __FILE__, [ 'DT_Publicsite_P4M', 'deactivation' ] );


if ( ! function_exists( 'disciple_tools_publicsite_p4m_hook_admin_notice' ) ) {
    function disciple_tools_publicsite_p4m_hook_admin_notice() {
        global $disciple_tools_publicsite_p4m_required_dt_theme_version;
        $wp_theme = wp_get_theme();
        $current_version = $wp_theme->version;
        $message = "'Disciple Tools - Public Skin P4E' plugin requires 'Disciple Tools' theme to work. Please activate 'Disciple Tools' theme or make sure it is latest version.";
        if ( $wp_theme->get_template() === "disciple-tools-theme" ){
            $message .= ' ' . sprintf( esc_html( 'Current Disciple Tools version: %1$s, required version: %2$s' ), esc_html( $current_version ), esc_html( $disciple_tools_publicsite_p4m_required_dt_theme_version ) );
        }
        // Check if it's been dismissed...
        if ( ! get_option( 'dismissed-disciple-tools-publicsite-p4m', false ) ) { ?>
            <div class="notice notice-error notice-disciple-tools-publicsite-p4m is-dismissible" data-notice="disciple-tools-publicsite-p4m">
                <p><?php echo esc_html( $message );?></p>
            </div>
            <script>
                jQuery(function($) {
                    $( document ).on( 'click', '.notice-disciple-tools-publicsite-p4m .notice-dismiss', function () {
                        $.ajax( ajaxurl, {
                            type: 'POST',
                            data: {
                                action: 'dismissed_notice_handler',
                                type: 'disciple-tools-publicsite-p4m',
                                security: '<?php echo esc_html( wp_create_nonce( 'wp_rest_dismiss' ) ) ?>'
                            }
                        })
                    });
                });
            </script>
        <?php }
    }
}

/**
 * AJAX handler to store the state of dismissible notices.
 */
if ( ! function_exists( "dt_hook_ajax_notice_handler" )){
    function dt_hook_ajax_notice_handler(){
        check_ajax_referer( 'wp_rest_dismiss', 'security' );
        if ( isset( $_POST["type"] ) ){
            $type = sanitize_text_field( wp_unslash( $_POST["type"] ) );
            update_option( 'dismissed-' . $type, true );
        }
    }
}

/**
 * Plugin Releases and updates
 * @todo Uncomment and change the url if you want to support remote plugin updating with new versions of your plugin
 * To remove: delete the section of code below and delete the file called version-control.json in the plugin root
 *
 * This section runs the remote plugin updating service, so you can issue distributed updates to your plugin
 *
 * @note See the instructions for version updating to understand the steps involved.
 * @link https://github.com/DiscipleTools/disciple-tools-publicsite-p4m/wiki/Configuring-Remote-Updating-System
 *
 * @todo Enable this section with your own hosted file
 * @todo An example of this file can be found in (version-control.json)
 * @todo Github is a good option for delivering static json.
 */
/**
 * Check for plugin updates even when the active theme is not Disciple.Tools
 *
 * Below is the publicly hosted .json file that carries the version information. This file can be hosted
 * anywhere as long as it is publicly accessible. You can download the version file listed below and use it as
 * a template.
 * Also, see the instructions for version updating to understand the steps involved.
 * @see https://github.com/DiscipleTools/disciple-tools-version-control/wiki/How-to-Update-the-Starter-Plugin
 */
//add_action( 'plugins_loaded', function (){
//    if ( is_admin() ){
//        // Check for plugin updates
//        if ( ! class_exists( 'Puc_v4_Factory' ) ) {
//            if ( file_exists( get_template_directory() . '/dt-core/libraries/plugin-update-checker/plugin-update-checker.php' )){
//                require( get_template_directory() . '/dt-core/libraries/plugin-update-checker/plugin-update-checker.php' );
//            }
//        }
//        if ( class_exists( 'Puc_v4_Factory' ) ){
//            Puc_v4_Factory::buildUpdateChecker(
//                'https://raw.githubusercontent.com/DiscipleTools/disciple-tools-publicsite-p4m/master/version-control.json',
//                __FILE__,
//                'disciple-tools-publicsite-p4m'
//            );
//
//        }
//    }
//} );
