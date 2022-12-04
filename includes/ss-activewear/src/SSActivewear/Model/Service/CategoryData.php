<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class CategoryData extends AbstractService
{
    /**
     * @var string
     */
    private $uri = Config::API_V2 . '/categories/';

    /**
     * Returns all categories.
     *
     * @return array
     * @throws \Exception
     */
    public function getAll(): array
    {
        return $this->sendRequest( $this->uri );
    }

    /**
     * Returns category by filter.
     * The parameter can be a comma separated category ids or a Category id.
     *
     * @param string $category
     * @return array
     * @throws \Exception
     */
    public function filterResults( string $category ): array
    {
        return $this->sendRequest($this->uri . $category);
    }

    /**
     * Returns specifically requested fields.
     * {fields} parameter is comma separated list of category object fields.
     *
     * @param string $fields
     * @return array
     * @throws \Exception
     */
    public function filterFields( string $fields ): array
    {
        return $this->sendRequest( "{$this->uri}?fields={$fields}" );
    }
}