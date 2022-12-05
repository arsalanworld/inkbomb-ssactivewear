<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

?>
<div class="wrap woocommerce">
    <div id="output" class="inkbomb-message-output"></div>
    <h1 class="screen-reader-text"><?php esc_html_e( 'Import Categories', INKBOMB_SS_DOMAIN ); ?></h1>
    <h2><?php esc_html_e( 'Import Categories', INKBOMB_SS_DOMAIN ); ?></h2>
    <p>Click import button to begin importing the categories.</p>
    <div class="import-wrapper">
        <button type="button" id="import_btn" class="button-primary woocommerce-save-button inkbomb-importer-btn"
                style="vertical-align: middle;"><?php esc_html_e('Import', INKBOMB_SS_DOMAIN); ?></button>
    </div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        $(document).on('click', '#import_btn', function () {
            $("#output").html("Requesting product import API.").show();
            $(this).attr('disabled', 'disabled');
            var currentTarget = this;
            $.post(ajaxurl, {
                import: 1,
                action: 'ssactivewear_import_all_cats'
            }, function (data) {
                $("#output")
                    .removeAttr('class')
                    .html("");
                $(currentTarget).removeAttr("disabled");
                let default_msg = "Something went wrong with import. Please try again later.";
                if ( data === undefined ) {
                    alert(default_msg);
                    return;
                }

                var span = '<span class="dashicons dashicons-saved"></span>',
                    cls = 'inkbomb-message-output success';
                if ( data.failure != undefined ) {
                    span = '<span class="dashicons dashicons-no-alt"></span>';
                    cls = 'inkbomb-message-output failure';
                }

                $("#output").html(span + ((data.message != undefined) ? data.message : default_msg));
                $("#output")
                    .addClass( cls )
                    .show();
                setTimeout(function () {
                    $("#output").hide();
                }, 60000);
            });
        });
    });
</script>