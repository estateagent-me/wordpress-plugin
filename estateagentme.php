<?php
/**
 *
 * @link              https://estateagent.me
 * @since             1.0.0
 * @package           Estateagentme
 *
 * @wordpress-plugin
 * Plugin Name:       EstateAgent.Me
 * Plugin URI:        https://estateagent.me
 * Description:       List your properties on a WordPress-powered site via your EstateAgent.Me Agent Account
 * Version:           1.2.0
 * Author:            EstateAgent.Me
 * Author URI:        https://estateagent.me
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       estateagentme
 * Domain Path:       /languages
 */

define('EA_VERSION', '1.2.0');

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * https://github.com/YahnisElsts/plugin-update-checker
 */
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/estateagent-me/wordpress-plugin',
	__FILE__,
	'estateagentme'
);

$myUpdateChecker->setBranch('master');

// Load conf.ini
$conf = parse_ini_file(__DIR__ . '/conf.ini', true);

// Setup & define EA_DOMAIN
$ea_domain = 'https://estateagent.me';
if (isset($conf['EA_DOMAIN'])) {
    $ea_domain = $conf['EA_DOMAIN'];
}
define('EA_DOMAIN', $ea_domain);

// Define EA_CDN
$ea_cdn = EA_DOMAIN . '/wp/';
define('EA_CDN', $ea_cdn);


// register_activation_hook( __FILE__, 'activate_estateagentme' );
// register_deactivation_hook( __FILE__, 'deactivate_estateagentme' );

require_once plugin_dir_path( __FILE__ ) . 'inc/classes/base.php';          // base model
require_once plugin_dir_path( __FILE__ ) . 'inc/classes/price.php';         // price model
require_once plugin_dir_path( __FILE__ ) . 'inc/classes/property.php';      // property model

require_once plugin_dir_path( __FILE__ ) . 'inc/install.php';               // install (for creating DB's)
require_once plugin_dir_path( __FILE__ ) . 'inc/cron_update.php';           // function that updates data
require_once plugin_dir_path( __FILE__ ) . 'inc/cron_schedule.php';         // run cron itself


add_action( 'admin_init', 'is_wp_control_installed' );
function is_wp_control_installed() {
    if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'wp-crontrol/wp-crontrol.php' ) ) {
        add_action( 'admin_notices', 'ea_wp_control_inactive' );
    }
}

function ea_wp_control_inactive(){
    ?><div class="ea-notice warning" style="display:block;">
        <p><strong>
            The EstateAgent.Me plugin requires the
            <a href="plugin-install.php?s=wp+control&tab=search&type=term" target="_blank">WP Control plugin</a>
            installed, activated & setup correctly in order to fully function.
        </strong></p>
        <p><strong>
            Please follow <a href="options-general.php?page=estateagentme">our instructions</a> on how to do this.
        </strong></p>
    </div><?php
}

/** ---------------------------------------------------------------------------------------------------- **/

/**
 * The admin page
 */
class options_page
{
    function __construct()
    {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'admin_init', array( $this, 'ea_settings' ) );
    }

    function admin_menu()
    {
        add_options_page(
            'EstateAgent.Me',
            'EstateAgent.Me',
            'manage_options',
            'estateagentme',
            array(
                $this,
                'settings_page'
            )
        );
    }

    function ea_settings()
    {
        register_setting( 'ea-settings', 'ea-agent-id' );
        register_setting( 'ea-settings', 'ea-auth-key' );
        register_setting( 'ea-settings', 'ea-google-maps-api-key' );
        register_setting( 'ea-settings', 'ea-default-search-selection' );
        register_setting( 'ea-settings', 'ea-accent-colour' );
        register_setting( 'ea-settings', 'ea-accent-colour-alt' );
    }

    function settings_page()
    {
        global $wpdb;

        if (isset($_GET['run']))
        {
            require_once plugin_dir_path( __FILE__ ) . 'inc/manual_run_page.php';
            // install();
            EACronUpdate(false);
        }
        else
        {
            // if we have nothing in Agents table, but auth_key & agent_id is set, do a run
            // this is usually a case where they've just installed the plugin for the first time.
            $numAgent = intval($wpdb->get_row("SELECT COUNT(*) as num FROM `{$wpdb->prefix}ea_agent`")->num);
            $auth_key = get_option('ea-auth-key');
            $agent_id = get_option('ea-agent-id');
            if ($numAgent < 1 && !empty($auth_key) && !empty($agent_id)) {
                EACronUpdate();
            }
            
            require_once plugin_dir_path( __FILE__ ) . 'inc/settings_page.php';
        }
    }
}
new options_page();

/** ---------------------------------------------------------------------------------------------------- **/

/**
 * Content replacements for front-end
 */
add_filter('the_content', 'ContentReplacements');
function ContentReplacements( $content )
{
    // search form
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'public/inc/page-styles.php';
    include plugin_dir_path( __FILE__ ) . 'public/inc/property.search_form.php';
    $search_form_html = ob_get_clean();
    $content = str_replace('[EA_PROPERTY_SEARCH_FORM]', $search_form_html, $content);

    // search results
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'public/inc/page-styles.php';
    include plugin_dir_path( __FILE__ ) . 'public/inc/property.results.php';
    $results_html = ob_get_clean();
    $content = str_replace('[EA_PROPERTY_RESULTS]', $results_html, $content);

    // details page
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'public/inc/page-styles.php';
    include plugin_dir_path( __FILE__ ) . 'public/inc/property.details_page.php';
    $details_page_html = ob_get_clean();
    $content = str_replace('[EA_PROPERTY_DETAILS_PAGE]', $details_page_html, $content);

    // vendor login
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'public/inc/page-styles.php';
    include plugin_dir_path( __FILE__ ) . 'public/inc/vendor_login.php';
    $login_page_html = ob_get_clean();
    $content = str_replace('[EA_VENDOR_LOGIN]', $login_page_html, $content);
    
    return $content;
}

/** ---------------------------------------------------------------------------------------------------- **/

/**
 * adding CSS & JS to public pages
 */
function css_and_js()
{
    // jquery
    if (!wp_script_is( 'jquery', 'enqueued' )) {
        wp_enqueue_script( 'jquery' );
    }
    // wp_deregister_script('jquery');
    // wp_enqueue_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), EA_VERSION, false);

    wp_enqueue_script('bootstrap-js', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js', array('jquery'), EA_VERSION, false);

    wp_enqueue_style('fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', array(), EA_VERSION, false);

    // lightslider
    wp_enqueue_script('lightslider-js', 'https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/js/lightslider.min.js', array('jquery'), EA_VERSION, false);
    wp_enqueue_style('lightslider-css', 'https://cdnjs.cloudflare.com/ajax/libs/lightslider/1.1.6/css/lightslider.min.css', array(), EA_VERSION, false);

    // css-element-queries
    wp_enqueue_script('css-element-queries-ResizeSensor', 'https://cdnjs.cloudflare.com/ajax/libs/css-element-queries/1.2.1/ResizeSensor.min.js', array('jquery'), EA_VERSION, false);
    wp_enqueue_script('css-element-queries-ElementQueries', 'https://cdnjs.cloudflare.com/ajax/libs/css-element-queries/1.2.1/ElementQueries.min.js', array('jquery'), EA_VERSION, false);

    // ekko-lightbox
    wp_enqueue_script('ekko-lightbox-js', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.min.js', array('jquery'), EA_VERSION, false);
    wp_enqueue_style('ekko-lightbox-css', 'https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/5.3.0/ekko-lightbox.css', array(), EA_VERSION, false);

    wp_enqueue_style('ea-styles-cdn', EA_CDN.'css/wp.css', array(), EA_VERSION, 'all');
    wp_enqueue_style('ea-styles', plugins_url('public/css/styles.css' ,__FILE__ ), array(), EA_VERSION, 'all');
}
add_action('wp_enqueue_scripts', 'css_and_js');

/** ---------------------------------------------------------------------------------------------------- **/

/**
 * adding CSS & JS to Admin pages
 */
function admin_css_and_js( $hook_suffix ) {
    
    wp_enqueue_style('ea-admin-styles', EA_CDN.'admin/wp-admin.css', array(), EA_VERSION, 'all');

    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'colour-picker-init', plugins_url('admin/colour-picker.js', __FILE__ ), array('jquery','wp-color-picker'), EA_VERSION, true );
}
add_action( 'admin_enqueue_scripts', 'admin_css_and_js' );

/** ---------------------------------------------------------------------------------------------------- **/

function css_and_js__properties() {
    if ( is_page( 'properties' ) ) {
    wp_enqueue_script( 'property-search-form-js', plugins_url('public/js/property-search-form.js', __FILE__ ), array('jquery','bootstrap-js','ekko-lightbox-js','lightslider-js'), EA_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'css_and_js__properties' );

function css_and_js__property_details() {
    if ( is_page( 'property-details' ) ) {
        wp_enqueue_script( 'property-details-js', plugins_url('public/js/property-details.js', __FILE__ ), array('jquery','bootstrap-js','ekko-lightbox-js','lightslider-js'), EA_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'css_and_js__property_details' );

/** ---------------------------------------------------------------------------------------------------- **/

/**
 * do URL rewriting for property details page
 * REMEMBER - visit Settings > Permalinks page in order to refresh these rules
 */
function property_detail_rewrite()
{
    $propertiesPage = get_posts(array('name' => 'properties', 'post_type' => 'page'));
    $propertyDetailsPage = get_posts(array('name' => 'property-details', 'post_type' => 'page'));
    $vendorLoginPage = get_posts(array('name' => 'login', 'post_type' => 'page'));

    $propertiesPage_Exists = false;
    $propertyDetailsPage_Exists = false;
    $vendorLoginPage_Exists = false;

    if (isset($propertiesPage) && ! empty($propertiesPage) && isset($propertiesPage[0])) $propertiesPage_Exists = true;
    if (isset($propertyDetailsPage) && ! empty($propertyDetailsPage) && isset($propertyDetailsPage[0])) $propertyDetailsPage_Exists = true;
    if (isset($vendorLoginPage) && ! empty($vendorLoginPage) && isset($vendorLoginPage[0])) $vendorLoginPage_Exists = true;

    // Properties page does not exist - create it
    if (! $propertiesPage_Exists) {
        $propertiesPage  = [
            'post_title'     => 'Properties',
            'post_type'      => 'page',
            'post_name'      => 'properties',
            'post_content'   => '[EA_PROPERTY_SEARCH_FORM][EA_PROPERTY_RESULTS]',
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => get_current_user_id(),
            'menu_order'     => 0,
            'guid'           => site_url() . "/properties",
        ];
        $propertiesPageID = wp_insert_post( $propertiesPage, false );
    }

    // Does property details page exist?
    if ($propertyDetailsPage_Exists) {
        $propertyDetailsPageID = $propertyDetailsPage[0]->ID;
        // Property details page exists, but it doesn't contain [EA_PROPERTY_DETAILS_PAGE] - update it's content
        if (!strstr($propertyDetailsPage[0]->post_content, '[EA_PROPERTY_DETAILS_PAGE]')) {
            $propertyDetailsPage  = [
                'ID'                => $propertyDetailsPage[0]->ID,
                'post_content'      => '[EA_PROPERTY_DETAILS_PAGE]',
            ];
            wp_update_post( $propertyDetailsPage, false );
        }
    } else {
        // Property details page does not exist - create it
        $propertyDetailsPage  = [
            'post_title'     => 'Property Details',
            'post_type'      => 'page',
            'post_name'      => 'property-details',
            'post_content'   => '[EA_PROPERTY_DETAILS_PAGE]',
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => get_current_user_id(),
            'menu_order'     => 0,
            'guid'           => site_url() . "/property-details",
        ];
        $propertyDetailsPageID = wp_insert_post( $propertyDetailsPage, false );
    }

    // Vendor login page does not exist - create it
    if (! $vendorLoginPage_Exists) {
        $vendorLoginPage  = [
            'post_title'     => 'Login',
            'post_type'      => 'page',
            'post_name'      => 'login',
            'post_content'   => '[EA_VENDOR_LOGIN]',
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => get_current_user_id(),
            'menu_order'     => 0,
            'guid'           => site_url() . "/login",
        ];
        $vendorLoginPageID = wp_insert_post( $vendorLoginPage, false );
    // Vendor login page does exist, but it doesn't contain [EA_VENDOR_LOGIN] - update it's content
    } elseif ($vendorLoginPage_Exists && !strstr($vendorLoginPage[0]->post_content, '[EA_VENDOR_LOGIN]')) {
        $vendorLoginPage  = [
            'ID'                => $vendorLoginPage[0]->ID,
            'post_content'      => '[EA_VENDOR_LOGIN]',
        ];
        wp_update_post( $vendorLoginPage, false );
    }

    add_rewrite_rule(
        '^properties\/for-(.+)\/in-(.+)\/([0-9]+)/?', // properties/for-sale/in-london/206
        'index.php?page_id=' . $propertyDetailsPageID . '&property_id=$matches[2]',
        'top');
}
add_action('init', 'property_detail_rewrite', 10, 0);

/**
 * property details page error - "property-details" page slug cannot be found
 */
function property_detail_rewrite_error_no_details_page()
{
    ?>
    <div class="ea-notice warning" style="display:block;">
        <p><?php _e( '<strong>EA Plugin Error : </strong>There has been an error locating the "Property Details" page. Ensure that the slug of the page is "property-details".' ); ?></p>
    </div>
    <?php
}

/** ---------------------------------------------------------------------------------------------------- **/