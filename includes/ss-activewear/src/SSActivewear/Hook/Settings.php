<?php
namespace SSActivewear\Hook;

use InkbombCore\Hook\AbstractSettings;
use SSActivewear\Model\Config;
use SSActivewear\View\Template\Renderer;

class Settings extends AbstractSettings
{
    const OPTION_GROUP = 'ssactivewear_settings';
    const OPTION_NAME = 'ssactivewear_settings';
    const SECTION_ID = 'ssactivewear_settings_section';
    const PAGE_NAME = 'ssactivewear_settings_page';
    const OPTION_CUSTOMER_NUMBER = 'customer_number';
    const OPTION_API_KEY = 'api_key';
    const OPTION_APPEND_BRAND_INFO = 'append_brand_info';
    const OPTION_APPEND_STYLE_INFO = 'append_style_info';
    const OPTION_MARKUP = 'markup_value';

    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'register' ) );
    }

    public function register()
    {
        // Register setting
        register_setting( static::OPTION_GROUP, static::OPTION_NAME );
        // Add setting section
        add_settings_section( static::SECTION_ID, 'S&S Activewear Settings', function () {}, static::PAGE_NAME );
        $fields = array(
            array(
                "field_id" => "_fld_" . self::OPTION_CUSTOMER_NUMBER,
                "title" => "Account number",
                "callback" => function () {
                    $this->getFieldRenderer()->render(
                        array(
                            'field_id' => '_fld_' . static::OPTION_CUSTOMER_NUMBER,
                            'name' => static::OPTION_CUSTOMER_NUMBER,
                            'value' => $this->getConfig()->getCustomerNumber(),
                            "section_id" => Settings::SECTION_ID,
                            "option_name" => Settings::OPTION_NAME
                        ),
                    );
                }
            ),
            array(
                "field_id" => "_fld_" . static::OPTION_API_KEY,
                "title" => "API key",
                "callback" => function () {
                    $this->getFieldRenderer()->render(
                        array(
                            'field_id' => '_fld_' . static::OPTION_API_KEY,
                            'name' => static::OPTION_API_KEY,
                            'value' => $this->getConfig()->getAPIKey(),
                            'type' => \InkbombCore\Hook\Settings\Renderer::INPUT_PASSWORD,
                            "section_id" => Settings::SECTION_ID,
                            "option_name" => Settings::OPTION_NAME
                        )
                    );
                },
            ),
            array(
                "field_id" => "_fld_" . static::OPTION_APPEND_BRAND_INFO,
                "title" => "Append Brand Name to title",
                "callback" => function () {
                    $this->getFieldRenderer()->render(
                        array(
                            'field_id' => '_fld_' . static::OPTION_APPEND_BRAND_INFO,
                            'name' => static::OPTION_APPEND_BRAND_INFO,
                            'value' => $this->getConfig()->isAppendBrandInfo(),
                            'type' => \InkbombCore\Hook\Settings\Renderer::SELECT,
                            "section_id" => Settings::SECTION_ID,
                            "option_name" => Settings::OPTION_NAME,
                            "options" => array(
                                "0" => "No",
                                "1" => "Yes",
                            ),
                            "note" => "Appears before the title. For example: <em>\"Addidas - Product Name\"</em>"
                        )
                    );
                },
            ),
            array(
                "field_id" => "_fld_" . static::OPTION_APPEND_STYLE_INFO,
                "title" => "Append Style Name to title",
                "callback" => function () {
                    $this->getFieldRenderer()->render(
                        array(
                            'field_id' => '_fld_' . static::OPTION_APPEND_STYLE_INFO,
                            'name' => static::OPTION_APPEND_STYLE_INFO,
                            'value' => $this->getConfig()->isAppendStyleInfo(),
                            'type' => \InkbombCore\Hook\Settings\Renderer::SELECT,
                            "section_id" => Settings::SECTION_ID,
                            "option_name" => Settings::OPTION_NAME,
                            "options" => array(
                                "0" => "No",
                                "1" => "Yes",
                            ),
                            "note" => "Appears after the title. For example: <em>\"Product Name - StyleName\"</em>"
                        )
                    );
                },
            ),
            array(
                "field_id" => "_fld_" . static::OPTION_MARKUP,
                "title" => "Markup percentage",
                "callback" => function () {
                    $this->getFieldRenderer()->render(
                        array(
                            'field_id' => '_fld_' . static::OPTION_MARKUP,
                            'name' => static::OPTION_MARKUP,
                            'value' => $this->getConfig()->getMarkupValue(),
                            'type' => \InkbombCore\Hook\Settings\Renderer::INPUT_NUMBER,
                            "section_id" => Settings::SECTION_ID,
                            "option_name" => Settings::OPTION_NAME,
                            'class' => '',
                        )
                    );
                },
            ),
        );

        foreach ( $fields as $field ) {
            $this->add_field( $field['field_id'], $field['title'], $field['callback'] );
        }
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

    /**
     * Renders the settings page
     */
    public function displaySettingPage()
    {
        Renderer::render('admin/settings.php', [
            'title' => get_admin_page_title()
        ]);
    }

    /**
     * @return \InkbombCore\Hook\Settings\Renderer
     */
    protected function getFieldRenderer()
    {
        if ( empty( $this->fieldRenderer ) ) {
            $this->fieldRenderer = new \InkbombCore\Hook\Settings\Renderer();
        }
        return $this->fieldRenderer;
    }

    /**
     * @return Config
     */
    private function getConfig(): Config
    {
        if ( empty ( $this->config ) ) {
            $this->config = new Config();
        }

        return $this->config;
    }
}