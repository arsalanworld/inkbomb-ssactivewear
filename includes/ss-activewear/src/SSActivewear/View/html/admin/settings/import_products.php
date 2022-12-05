<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="wrap woocommerce">
    <div id="output" class="inkbomb-message-output"></div>
    <h1 class="screen-reader-text"><?php esc_html_e( 'Import Products', INKBOMB_SS_DOMAIN ); ?></h1>
    <h2><?php esc_html_e( 'Import Products', INKBOMB_SS_DOMAIN ); ?></h2>
    <p>Click import button to begin importing the products.</p>
    <div class="import-wrapper">
        <button type="button" id="import_btn" class="button-primary woocommerce-save-button inkbomb-importer-btn"
                style="vertical-align: middle;"><?php esc_html_e('Import Products', INKBOMB_SS_DOMAIN); ?></button>
    </div>
</div>
