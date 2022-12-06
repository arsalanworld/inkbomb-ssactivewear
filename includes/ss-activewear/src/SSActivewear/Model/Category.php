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

    /**
     * Returns the list of Category Ids associated with SSActivewear.
     * {$value} can be a string or a comma separated value and is optional.
     *
     * @param string $key
     * @param string|null $value
     * @return array|string|null
     */
    public function getCatIdsByApiCategory( $key, $value="", $commaseparated = false )
    {
        global $wpdb;
        if ( !empty( $value ) ) {
            $value = " AND meta_value IN(" . $value . ")";
        }

        $results = $wpdb->get_results( "select post_id from $wpdb->postmeta where meta_key = '"
            . $key . "'" . $value, ARRAY_A );
        if ( !$commaseparated ) {
            return $results;
        }

        return implode(",", $results);
    }
}