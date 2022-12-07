<?php
namespace SSActivewear\Cron\Import;

use InkbombCore\Logger\InkbombLogger;
use SSActivewear\Model\Category;
use SSActivewear\Model\Config;
use SSActivewear\Model\CsvManager;
use SSActivewear\Model\Service\ProductData;

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

    /**
     * @var ProductData
     */
    private $productDataService;

    public function execute()
    {
        // Read csv.
        $data = $this->getCsvManager()->read( CsvManager::STYLES_CSV, true );
        if ( empty( $data ) ) {
            $this->clearScheduler( static::HOOK_NAME );
            InkbombLogger::log( "Completed the product import." );
            return;
        }

        try {
            $product = $this->createProduct( $data );
            $productApiData = $this->getProductService()->filterResultsByStyleId( $product->get_sku() );
            $this->addAttributes( $product, $productApiData );
            $this->addVariations( $product, $productApiData );
        } catch ( \Exception $e ) {
            InkbombLogger::log("[Inkbomb SSActivewear]: " . $e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return \WC_Product_Variable
     * @throws \WC_Data_Exception
     */
    protected function createProduct(array $data)
    {
        $categories = $this->getCategory()->getCatIdsByApiCategory( Category::CATEGORY_ID, $data['categories'] );
        $productId = wc_get_product_id_by_sku( $data['styleID'] );
        if ( $productId ) {
            $is_new_import = false;
        }

        $product = new \WC_Product_Variable( $productId );
        $title = $data['title'];
        if ( isset( $data['brandName'] ) ) {
            $title = $data['brandName'] . " - " . $title;
        }

        if ( isset( $data['styleName'] ) ) {
            $title .= " - " . $data['styleName'];
        }

        $product->set_name( $title );
        $product->set_description( $data['description'] );
        $product->set_sku( $data['styleID'] );
        $product->set_category_ids( $categories );
        $product->set_status( $is_new_import ? 'publish' : 'draft' );
        $product->set_catalog_visibility( 'visible' );
        $product->save();

        $productId = $product->get_id();
        $productModel = $this->getProduct();
        if ( !empty( $data['styleImage'] ) ) {
            $imageUrl = Config::MEDIA_CDN . $data['styleImage'];
            // Upload the image
            $productModel->uploadImgToProduct( $product, $imageUrl );
        }

        // Mark the product as SSActivewear
        $productModel->markApiProduct( $productId );
        if ( isset( $data['partNumber'] ) ) {
            $productModel->setPartNumber( $productId, $data['partNumber'] );
        }

        if ( isset( $data['brandName'] ) ) {
            $productModel->setBrandName( $productId, $data['brandName'] );
        }

        if ( isset( $data['styleName'] ) ) {
            $productModel->setStyleName( $productId, $data['styleName'] );
        }

        if ( isset( $data['brandImage'] ) ) {
            $productModel->setBrandImage( $productId, $data['brandImage'] );
        }

        if ( isset( $data['boxRequired'] ) ) {
            $productModel->setBoxRequired( $productId, $data['boxRequired'] );
        }

        return $product;
    }

    /**
     * Add attributes.
     *
     * @param \WC_Product_Variable $product
     * @param array $data
     */
    protected function addAttributes( \WC_Product_Variable $product, array $data )
    {
        $attributes = array("Color" => array(), "Size" => array());
        array_walk($data, function ($v, $k) use(&$attributes) {
            if ( !in_array($v["colorName"], $attributes["Color"]) ) {
                $attributes["Color"][] = $v["colorName"];
            }

            if ( !in_array($v["sizeName"], $attributes["Size"]) ) {
                $attributes["Size"][] = $v["sizeName"];
            }
        });

        $attribute_object = array();
        foreach ($attributes as $key => $attribute) {
            $new_attribute = new \WC_Product_Attribute();
            $new_attribute->set_name( $key );
            $new_attribute->set_options( $attribute );
            $new_attribute->set_visible(1);
            $new_attribute->set_variation(1);
            $attribute_object[ $key ] = $new_attribute;
        }

        $product->set_attributes( $attribute_object );
        $product->save();
    }

    protected function addVariations( \WC_Product_Variable $product, $data )
    {
        array_walk($data, function ($item, $i) use(&$product) {
            $variation = $this->getProduct()->getVariationBySku( $item['sku'] );
            if ( !$variation ) {
                $variation = new \WC_Product_Variation();
            }

            $variation->set_parent_id( $product->get_id() );
            $variation->set_sku( $item['sku'] );
            $variation->set_manage_stock( true );
            $variation->set_catalog_visibility( 'visible' );
            $stockStatus = 'instock';
            if ( !$item['qty'] ) {
                $stockStatus = 'outofstock';
            }

            $variation->set_stock_status( $stockStatus );
            $variation->set_stock_quantity( $item['qty'] );
            $variation->set_sale_price( $item['salePrice'] );
            $variation->set_regular_price( $item['customerPrice'] );
            $variation->set_weight( $item['unitWeight'] );
            $variation->save();

            // Upload image
            if ( !empty( $item['colorDirectSideImage'] ) ) {
                $this->getProduct()->uploadImgToProduct( $variation, Config::MEDIA_CDN . $item['colorDirectSideImage'] );
            }

            update_post_meta( $variation->get_id(), 'attribute_color',  $item['colorName']);
            update_post_meta( $variation->get_id(), 'attribute_size',  $item['sizeName']);

        });
    }

    /**
     * @param string $hook
     */
    protected function clearScheduler( $hook )
    {
        if ( wp_next_scheduled( $hook ) ) {
            wp_clear_scheduled_hook( $hook );
        }
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
     * Get the category model instance.
     *
     * @return Category
     */
    protected function getCategory()
    {
        if ( empty ( $this->category ) ) {
            $this->category = new Category();
        }

        return $this->category;
    }

    /**
     * Get the product model instance.
     *
     * @return \SSActivewear\Model\Product
     */
    protected function getProduct()
    {
        if ( empty ( $this->product ) ) {
            $this->product = new \SSActivewear\Model\Product();
        }

        return $this->product;
    }

    protected function getProductService()
    {
        if ( empty( $this->productDataService ) ) {
            $this->productDataService = new ProductData();
        }

        return $this->productDataService;
    }
}