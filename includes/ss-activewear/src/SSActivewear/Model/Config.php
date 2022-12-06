<?php
namespace SSActivewear\Model;

use SSActivewear\Hook\Settings;

class Config extends \InkbombCore\Model\Config
{
    public const ENDPOINT = 'https://api-ca.ssactivewear.com/';
    public const MEDIA_CDN = 'https://cdn.ssactivewear.com/';
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

    /**
     * @return string
     */
    public function getCustomerNumber(): string
    {
        $customerNumber = $this->getOptionsArray( Settings::OPTION_CUSTOMER_NUMBER );
        return ( !empty( $customerNumber ) ) ? $customerNumber[ Settings::OPTION_CUSTOMER_NUMBER ]: '';
    }

    /**
     * @return string
     */
    public function getAPIKey(): string
    {
        $apiKey = $this->getOptionsArray( Settings::OPTION_API_KEY );
        return ( !empty( $apiKey ) ) ? $apiKey[Settings::OPTION_API_KEY] : '';
    }
}