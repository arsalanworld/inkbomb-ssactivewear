<?php
namespace SSActivewear\Model;

class Category extends \InkbombCore\Model\Category
{
    const CATEGORY_ID = '_ssactivewear_cat_id';
    const CATEGORY_IMAGE = '_ssactivewear_cat_image';

    /**
     * @param string $name
     * @param int|string $ssCatId
     * @return array|int|int[]|\WP_Error
     */
    public function addCategory( $categoryData )
    {
        $data = $this->add( $categoryData['name'] );
        if ( !empty( $data ) && !is_array($data)) {
            $data['term_id'] = $data;
        }

        if ( isset( $data['term_id'] ) ) {
            update_post_meta( $data['term_id'], static::CATEGORY_ID, $categoryData['categoryID'] );
            update_post_meta( $data['term_id'], static::CATEGORY_IMAGE, $categoryData['image'] );
        }

        return $data;
    }
}