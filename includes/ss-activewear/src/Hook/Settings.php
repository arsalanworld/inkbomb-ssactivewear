<?php
namespace SSActivewear\Hook;

use InkbombCore\Hook\AbstractSettings;

class Settings extends AbstractSettings
{
    const OPTION_GROUP = 'ssactivewear_settings';
    const OPTION_NAME = 'ssactivewear_settings';
    const SECTION_ID = 'ssactivewear_settings_section';
    const PAGE_NAME = 'ssactivewear_settings_page';
    const OPTION_CUSTOMER_NUMBER = 'customer_number';
    const OPTION_API_KEY = 'api_key';

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'register' ) );
    }

    public function register()
    {
        // TODO: Implement register() method.
    }

    /**
     * Add Alphabroder as submenu under inkbomb main menu.
     */
    public function add_submenu_page()
    {
        $page_hook = add_submenu_page(
            'inkbomb-sinalite-settings',
            __('S&S Activewear', INKBOMB_SS_DOMAIN),
            __('S&S Activewear ', INKBOMB_SS_DOMAIN),
            'manage_options',
            'ssactivewear-settings',
            array($this, 'displaySettingPage'),
            50
        );
    }
}