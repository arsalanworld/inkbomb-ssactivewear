<?php
namespace SSActivewear\Model\Service;

class CategoryData
{
    /**
     * Returns all categories.
     *
     * @return array
     */
    public function getAll(): array
    {
        return [];
    }

    /**
     * Returns category by filter.
     * The parameter can be a comma separated category ids or a Category id.
     *
     * @param string $category
     * @return array
     */
    public function filterResults( string $category ): array
    {
        return [];
    }

    /**
     * Returns specifically requested fields.
     * {fields} parameter is comma separated list of category object fields.
     *
     * @param string $fields
     * @return array
     */
    public function filterFields( string $fields ): array
    {
        return [];
    }
}