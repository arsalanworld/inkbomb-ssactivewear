<?php
namespace SSActivewear\Model;

class Product extends \InkbombCore\Model\Product
{
    const SSACTIVEWEAR_PRODUCT = 'ssactivewear_product';
    const PART_NUMBER = 'ssactive_product_partnumber';
    const BRAND_NAME = 'ssactive_product_brandname';
    const BRAND_IMAGE = 'ssactive_product_brandimage';
    const STYLE_NAME = 'ssactive_product_stylename';
    const BOX_REQUIRED = 'ssactive_product_boxrequired';

    public function markApiProduct( $productId )
    {
        update_post_meta( $productId, static::SSACTIVEWEAR_PRODUCT, 1 );
    }
    /**
     * @param string|int $productId
     * @param string $partnumber
     */
    public function setPartNumber( $productId, $partnumber )
    {
        update_post_meta( $productId, static::PART_NUMBER, $partnumber );
    }

    /**
     * @param string|int $productId
     * @param string $brandName
     */
    public function setBrandName( $productId, $brandName )
    {
        update_post_meta( $productId, static::BRAND_NAME, $brandName );
    }

    /**
     * @param string|int $productId
     * @param string $brandImage
     */
    public function setBrandImage( $productId, $brandImage )
    {
        update_post_meta( $productId, static::BRAND_IMAGE, $brandImage );
    }

    /**
     * @param string|int $productId
     * @param string $styleNme
     */
    public function setStyleName( $productId, $styleNme )
    {
        update_post_meta( $productId, static::STYLE_NAME, $styleNme );
    }

    /**
     * @param string|int $productId
     * @param string|int $boxRequired
     */
    public function setBoxRequired( $productId, $boxRequired )
    {
        update_post_meta( $productId, static::BOX_REQUIRED, $boxRequired );
    }

    /**
     * @param string|int $productId
     * @return string|null
     */
    public function getPartNumber( $productId )
    {
        return get_post_meta( $productId, static::PART_NUMBER, true );
    }

    /**
     * @param string|int $productId
     * @return string|null
     */
    public function getBrandName( $productId )
    {
        return get_post_meta( $productId, static::BRAND_NAME, true );
    }

    /**
     * @param string|int $productId
     * @return string
     */
    public function getBrandImage( $productId )
    {
        return get_post_meta( $productId, static::BRAND_IMAGE, true );
    }

    /**
     * @param string|int $productId
     * @return string
     */
    public function getStyleName( $productId )
    {
        return get_post_meta( $productId, static::STYLE_NAME, true );
    }

    /**
     * @param string|int $productId
     * @return string
     */
    public function getBoxRequired( $productId )
    {
        return get_post_meta( $productId, static::BOX_REQUIRED, true );
    }
}