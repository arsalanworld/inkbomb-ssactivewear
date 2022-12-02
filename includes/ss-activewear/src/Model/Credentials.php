<?php
namespace SSActivewear\Model;

use SSActivewear\Hook\Settings;

class Credentials implements \InkbombCore\Http\CredentialInterface
{
    /**
     * Customer Number.
     */
    const CUSTOMER_NUMBER = 'customerNumber';

    /**
     * API Key.
     */
    const API_KEY = 'APIKey';

    /**
     * @var Config
     */
    private $config;

    /**
     * @inheritDoc
     */
    public function getAuth(): array
    {
        return [
            static::CUSTOMER_NUMBER => $this->getCustomerNumber(),
            static::API_KEY => $this->getAPIKey()
        ];
    }

    /**
     * @return string
     */
    private function getCustomerNumber(): string
    {
        $customerNumber = $this->getConfig()->getOptionsArray( Settings::OPTION_CUSTOMER_NUMBER );
        return ( !empty( $customerNumber ) ) ? $customerNumber[ Settings::OPTION_CUSTOMER_NUMBER ]: '';
    }

    /**
     * @return string
     */
    private function getAPIKey(): string
    {
        $apiKey = $this->getConfig()->getOptionsArray( Settings::OPTION_API_KEY );
        return ( !empty( $apiKey ) ) ? $apiKey[Settings::OPTION_API_KEY] : '';
    }

    /**
     * Get Config model instance.
     *
     * @return Config
     */
    private function getConfig(): Config
    {
        if ( empty(  $this->config ) ) {
            $this->config = new Config();
        }

        return $this->config;
    }
}