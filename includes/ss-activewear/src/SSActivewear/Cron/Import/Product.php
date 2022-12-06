<?php
namespace SSActivewear\Cron\Import;

use InkbombCore\Logger\InkbombLogger;
use SSActivewear\Model\Category;
use SSActivewear\Model\Config;
use SSActivewear\Model\CsvManager;

class Product
{
    const HOOK_NAME = 'ssactivewear_product_import';

    /**
     * @var CsvManager
     */
    private $csvManager;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var \InkbombCore\Model\Product
     */
    private $product;

    public function execute()
    {
        // Read csv.
        $data = $this->getCsvManager()->read( CsvManager::STYLES_CSV, true );
        if ( empty( $data ) ) {
            $this->clearScheduler( static::HOOK_NAME );
            InkbombLogger::log( "Completed the product import." );
            return;
        }

        $categories = $this->getCategory()->getCatIdsByApiCategory( Category::CATEGORY_ID, $data['categories'] );
        $productId = wc_get_product_id_by_sku( $data['styleID'] );
        if ( $productId ) {
            $is_new_import = false;
        }

        $product = new \WC_Product_Variable( $productId );
        $product->set_name( $data['title'] );
        $product->set_description( $data['description'] );
        $product->set_sku( $data['styleID'] );
        $product->set_category_ids( $categories );
        $product->set_status( 'importing' );
        $product->set_catalog_visibility( 'hidden' );
        $product->save();

        if ( !empty( $data['styleImage'] ) ) {
            $imageUrl = Config::MEDIA_CDN . $data['styleImage'];
            // Upload the image
            $this->getProduct()->uploadImgToProduct( $product, $imageUrl );
        }

        // Mark the product as Alphabroder
        update_post_meta( $product->get_id(), \SSActivewear\Model\Product::SSACTIVEWEAR_PRODUCT, 1 );
        
    }

    /**
     * @param string $hook
     */
    private function clearScheduler( $hook )
    {
        if ( wp_next_scheduled( $hook ) ) {
            wp_clear_scheduled_hook( $hook );
        }
    }

    /**
     * @return CsvManager
     */
    private function getCsvManager()
    {
        if ( empty( $this->csvManager ) ) {
            $this->csvManager = new CsvManager();
        }

        return $this->csvManager;
    }

    /**
     * Get the category model instance.
     *
     * @return Category
     */
    private function getCategory()
    {
        if ( empty ( $this->category ) ) {
            $this->category = new Category();
        }

        return $this->category;
    }

    /**
     * Get the product model instance.
     *
     * @return \InkbombCore\Model\Product
     */
    private function getProduct()
    {
        if ( empty ( $this->product ) ) {
            $this->product = new \InkbombCore\Model\Product();
        }

        return $this->product;
    }
}