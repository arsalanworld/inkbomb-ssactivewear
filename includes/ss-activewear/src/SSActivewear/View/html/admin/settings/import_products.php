<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SSActivewear\Model\Category;

$cat = new \SSActivewear\Model\Category();
$catList = $cat->getCatIdsByApiCategory( Category::CATEGORY_ID );
if ( count($catList) ):
?>
<div class="wrap woocommerce">
    <div id="output" class="inkbomb-message-output"></div>
    <h1 class="screen-reader-text"><?php esc_html_e( 'Import Products', INKBOMB_SS_DOMAIN ); ?></h1>
    <h2><?php esc_html_e( 'Import Products', INKBOMB_SS_DOMAIN ); ?></h2>
    <p><?php esc_html_e( 'Click import button to begin importing the products.', INKBOMB_SS_DOMAIN ); ?></p>
    <div class="import-wrapper">
        <button type="button" id="import_btn" class="button-primary woocommerce-save-button inkbomb-importer-btn"
                style="vertical-align: middle;"><?php esc_html_e('Import Products', INKBOMB_SS_DOMAIN); ?></button>
    </div>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        $(document).on('click', '#import_btn', function () {
            $("#output")
                .html("Requesting product import API.")
                .removeClass('success')
                .removeClass('failure')
                .show();
            $(this).attr('disabled', 'disabled');
            var currentTarget = this;
            $.post(ajaxurl, {
                import: 1,
                action: 'ssactivewear_import_products'
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

                var span = '<span class="dashicons dashicons-yes-alt"></span>',
                    cls = 'inkbomb-message-output success';
                if ( data.failure != undefined ) {
                    span = '<span class="dashicons dashicons-dismiss"></span>';
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
<?php
else:
    $settings_page = 'ssactivewear-settings';
?>
<div class="wrap woocommerce">
    <div id="output" class="inkbomb-message-output failure" style="display: block;">
        Please import the Categories first from <a href="?page=<?php echo $settings_page; ?>&tab=import_categories" style="color: #fe6;">Category tab</a>.
    </div>
</div>
<?php
endif;
?>
