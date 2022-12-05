<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
use SSActivewear\View\Template\Renderer;

$default_tab = null;
$tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
$settings_page = 'ssactivewear-settings';
?>
<div class="wrap">
    <h1><?php /** @var $title */ echo $title; ?></h1>
    <nav class="nav-tab-wrapper">
        <a href="?page=<?php echo $settings_page; ?>" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Settings</a>
        <a href="?page=<?php echo $settings_page; ?>&tab=import_categories" class="nav-tab <?php if($tab==='import_categories'):?>nav-tab-active<?php endif; ?>">Import Category</a>
        <a href="?page=<?php echo $settings_page; ?>&tab=import_products" class="nav-tab <?php if($tab==='import_products'):?>nav-tab-active<?php endif; ?>">Import Products</a>
    </nav>
    <div class="tab-content">
        <?php
        $tab_to_render = 'admin/settings/general_settings.php';
        switch( $tab ) {
            case 'import_categories':
                $tab_to_render = 'admin/settings/import_categories.php';
                break;
            case 'import_products':
                $tab_to_render = 'admin/settings/import_products.php';
                break;
        }
        Renderer::render($tab_to_render);
        ?>
    </div>
</div>
