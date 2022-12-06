<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class ProductData extends AbstractService
{
    /**
     * Products uri.
     *
     * @var string
     */
    protected $uri = Config::API_V2 . '/products/';

    /**
     * Filters the results by style id.
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

    /**
     * Filters the results by provided styles.
     *
     * @param string|int|null $styles
     * @return array
     * @throws \Exception
     */
    public function filterResultsByStyles( $styles ): array
    {
        return $this->filterResultsByKey( "style", $styles );
    }
}