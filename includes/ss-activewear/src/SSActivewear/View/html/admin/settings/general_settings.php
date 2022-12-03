<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SSActivewear\Hook\Settings;

?>
<form action="options.php" method="post">
    <?php
    settings_fields( Settings::OPTION_GROUP );
    do_settings_sections( Settings::PAGE_NAME ); ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
</form>