<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists('inkbomb_ssactivewear_deactivation') ) {
    function inkbomb_ssactivewear_deactivation () {
        // Uninstall the SSActivewear cron hook.
        $cron_item = \SSActivewear\Cron\Import\Product::HOOK_NAME;
        if ( wp_next_scheduled ( $cron_item ) ) {
            wp_clear_scheduled_hook( $cron_item );
        }
    }
}