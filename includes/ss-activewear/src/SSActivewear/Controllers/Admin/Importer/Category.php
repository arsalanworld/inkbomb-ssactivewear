<?php
namespace SSActivewear\Controllers\Admin\Importer;

use SSActivewear\Model\CsvManager;
use SSActivewear\Model\Service\CategoryData;

class Category
{
    /**
     * @var CategoryData
     */
    private $serviceModel;

    /**
     * @var CsvManager
     */
    private $csvManager;

    /**
     * @var \SSActivewear\Model\Category
     */
    private $categoryModel;

    /**
     * Perform import all operation.
     */
    public function import_all()
    {
        if (is_admin() && isset( $_POST['import'] )) {
            $result = array(
                "success" => true
            );
            try {
                $categories = $this->getServiceModel()->getAll();
                // Note down categories in CSV
                //$this->getCsvManager()->writeCategoryCSV( $categories, CsvManager::CATEGORY_CSV );
                $importCount = 0;
                foreach ( $categories as $category ) {
                    $this->getCategoryModel()->addCategory( $category );
                    $importCount++;
                }
                wp_send_json(array(
                    "success" => true,
                    "message" => "A total of {$importCount} categories swere imported."
                ));
            } catch (\Exception $e) {
                wp_send_json(array(
                    "failure" => true,
                    "message" => $e->getMessage()
                ));
            }
        }

        die();
    }

    /**
     * @return CategoryData
     */
    protected function getServiceModel()
    {
        if ( empty( $this->serviceModel ) ) {
            $this->serviceModel = new CategoryData();
        }

        return $this->serviceModel;
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

    /**
     * @return \SSActivewear\Model\Category
     */
    protected function getCategoryModel()
    {
        if ( empty( $this->categoryModel ) ) {
            $this->categoryModel = new \SSActivewear\Model\Category();
        }
        return $this->categoryModel;
    }
}