<?php
namespace SSActivewear\Model;

use SSActivewear\Hook\Settings;

class Config extends \InkbombCore\Model\Config
{
    public const ENDPOINT = 'https://api-ca.ssactivewear.com/';
    public const API_V2 = 'v2';
    public const MEDIA_TYPE_JSON = 'json';
    public const MEDIA_TYPE_XML = 'xml';

    /**
     * @param string|null $filterIndex
     * @return array
     */
    public function getOptionsArray( ?string $filterIndex = '' ): array
    {
        return $this->getConfigArray( Settings::OPTION_NAME, $filterIndex );
    }
}