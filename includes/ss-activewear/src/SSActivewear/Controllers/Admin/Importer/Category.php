<?php
namespace SSActivewear\Controllers\Admin\Importer;

use SSActivewear\Model\Service\CategoryData;

class Category
{
    private $serviceModel;

    public function import_all()
    {
        if (is_admin() && isset( $_POST['import'] )) {
            $result = array(
                "success" => true
            );
            try {
                $categories = $this->getServiceModel()->getAll();
                // Note down categories in CSV

                wp_send_json(array(
                    "success" => true
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
    protected function getServiceModel(): CategoryData
    {
        if ( empty( $this->serviceModel ) ) {
            $this->serviceModel = new CategoryData();
        }

        return $this->serviceModel;
    }
}