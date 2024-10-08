<?php
/**
 * Plugin Name: Inkbomb SSActivewear
 * Plugin URI: https://inkbomb.ca/
 * Description: SSActivewear API integration.
 * Version: 1.0.0
 * Author: Inkbomb
 * Author URI: https://inkbomb.ca/
 * Developer: Arsalan
 * Developer URI: https://arsalanajmal.com/
 * Text Domain: ssactivewear
 *
 * WC requires at least: 3.5
 * WC tested up to: 7.1
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if ( !function_exists('add_action') ) {
    echo "No direct access allowed";
    exit();
}
/**
 * Version Checking
 */
if ( version_compare( get_bloginfo('version'), '4.0', '<' ) ) {
    $message = 'Plugin is not supported for the version less than 4.0';
    die($message);
}

/**
 * Constants
 */
define('INKBOMB_SS_PATH', plugin_dir_path(__FILE__ ));
define('INKBOMB_SS_CLASS_PATH', plugin_dir_path(__FILE__ ) . 'includes/ss-activewear/src/SSActivewear/');
define('INKBOMB_SS_URI', plugin_dir_url( __FILE__ ));
const INKBOMB_SS_DOMAIN = 'ssactivewear';

/**
 * Check if woocommerce is activated
 */
if ( !in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
    die("Woocommerce must be active before activating the Inkbomb plugins.");
}

/**
 * Include autoload for PSR-4 classes
 */
require ( ABSPATH . 'wp-content/plugins/inkbomb-core/includes/lib/guzzlehttp/vendor/autoload.php');
require ( ABSPATH . 'wp-content/plugins/inkbomb-core/includes/core/autoloader.php');
require ( INKBOMB_SS_PATH . '/includes/ss-activewear/autoloader.php' );

if ( !class_exists( 'WcInkbombSSActivewear' ) ) {
    class WcInkbombSSActivewear
    {
        public function __construct()
        {
            /**
             * Import the files.
             */
            $this->include_files();
            /**
             * Add hooks and filters
             */
            $this->add_hooks();
        }

        public function include_files()
        {
            require_once INKBOMB_SS_PATH . "includes/activation.php";
            require_once INKBOMB_SS_PATH . "includes/cron_hooks.php";
        }

        public function add_hooks()
        {
            // Register activation hook.
            register_activation_hook(__FILE__, 'inkbomb_ssactivewear_activation');

            // Deactivation hook
            register_deactivation_hook( __FILE__ , 'inkbomb_ssactivewear_deactivation');

            // Add submenu
            add_action('admin_menu', array(new \SSActivewear\Hook\Settings(), 'add_submenu_page'), 100);

            // Add ajax actions
            $category = new \SSActivewear\Controllers\Admin\Importer\Category();
            add_action( 'wp_ajax_ssactivewear_import_all_cats', array( $category, 'import_all' ) );
            $product = new \SSActivewear\Controllers\Admin\Importer\Product();
            add_action( 'wp_ajax_ssactivewear_import_products', array( $product, 'import' ) );
            add_action( 'wp_ajax_ssactivewear_stop_import_products', array( $product, 'stopImport' ) );

            // ##################### Price Hooks ##################### \\
            $productModel = new \SSActivewear\Model\Product();
            // Simple, grouped and external products.
            add_filter('woocommerce_product_get_price', array( $productModel, 'getPriceWithMarkup' ), 99, 2 );
            add_filter('woocommerce_product_get_regular_price', array( $productModel, 'getPriceWithMarkup' ), 99, 2 );
            // Variations
            add_filter('woocommerce_product_variation_get_regular_price', array( $productModel, 'getPriceWithMarkup' ), 99, 2 );
            add_filter('woocommerce_product_variation_get_price', array( $productModel, 'getPriceWithMarkup' ), 99, 2 );

            // Variable (price range)
            add_filter('woocommerce_variation_prices_price', array( $productModel, 'getVariablePriceWithMarkup' ), 99, 3 );
            add_filter('woocommerce_variation_prices_regular_price', array( $productModel, 'getVariablePriceWithMarkup' ), 99, 3 );

            // Handling price caching (see explanations at the end)
            add_filter( 'woocommerce_get_variation_prices_hash', array( $productModel, 'addPriceMarkupToVariationPricesHash' ), 99, 3 );
            // ##################### End: Price Hooks ##################### \\

            ssactivewear_register_cron();
        }
    }

    $inkbombSSActivewear = new WcInkbombSSActivewear();
}