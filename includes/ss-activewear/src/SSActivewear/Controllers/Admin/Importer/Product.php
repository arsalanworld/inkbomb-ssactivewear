<?php
namespace SSActivewear\Controllers\Admin\Importer;

use SSActivewear\Model\CsvManager;
use SSActivewear\Model\Service\ProductData;
use SSActivewear\Model\Service\StylesData;

class Product
{
    /**
     * @var CsvManager
     */
    private $csvManager;

    /**
     * @var StylesData
     */
    private $stylesService;

    /**
     * @var ProductData
     */
    private $productService;

    public function import()
    {
        if ( is_admin() && isset( $_POST['import'] ) ) {
            // 1. Fetch styles data.
            $stylesData = $this->getStylesService()->getAll();
            // 2. Record the Styles data in a csv.
            $this->getCsvManager()->writeCategoryCSV( $stylesData, CsvManager::STYLES_CSV );
        }

        die();
    }

    /**
     * Returns the Styles service model.
     *
     * @return StylesData
     */
    private function getStylesService()
    {
        if ( empty( $this->stylesService ) ) {
            $this->stylesService = new StylesData();
        }

        return $this->stylesService;
    }

    /**
     * Returns the Product service model.
     *
     * @return ProductData
     */
    private function getProductService()
    {
        if ( empty( $this->productService ) ) {
            $this->productService = new ProductData();
        }

        return $this->productService;
    }

    /**
     * @return CsvManager
     */
    protected function getCsvManager()
    {
        if ( empty( $this->csvManager ) ) {
            $this->csvManager = new CsvManager();
        }
        return $this->csvManager;
    }
}