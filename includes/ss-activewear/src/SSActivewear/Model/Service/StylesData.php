<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class StylesData extends AbstractService
{
    /**
     * @var string
     */
    protected $uri = Config::API_V2 . '/styles/';

    /**
     * Returns styles matching filter condition.
     *
     * @param string|int|null $search
     * @return array
     * @throws \Exception
     */
    public function searchResults( $search ): array
    {
        return $this->filterResultsByKey( "search", $search );
    }

    /**
     * Filters results by style id.
     *
     * @param string|int|null $styleId
     * @return array
     * @throws \Exception
     */
    public function filterResultsByStyleId( $styleId ): array
    {
        return $this->filterResultsByKey( "styleid", $styleId );
    }

    /**
     * Filters results by part number.
     *
     * @param string|int|null $partNumber
     * @return array
     * @throws \Exception
     */
    public function filterResultsByPartId( $partNumber ): array
    {
        return $this->filterResultsByKey( "partnumber", $partNumber );
    }
}