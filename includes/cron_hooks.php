<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SSActivewear\Cron\Import\Product;

if ( !function_exists( 'ssactivewear_register_cron' ) ) {
    function ssactivewear_register_cron()
    {
        $product = new Product();
        /**
         *  ===================================
         * ||        Cron Import hooks       ||
         *  ===================================
         */
        add_action(Product::HOOK_NAME, array( $product, 'execute' ));
    }
}
