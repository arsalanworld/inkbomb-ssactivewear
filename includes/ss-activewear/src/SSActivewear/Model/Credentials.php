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
            static::CUSTOMER_NUMBER => $this->getConfig()->getCustomerNumber(),
            static::API_KEY => $this->getConfig()->getAPIKey()
        ];
    }

    /**
     * Returns the basic authentication token.
     *
     * @return string
     */
    public function getBasicAuthentication(): string
    {
        return base64_encode(
            $this->getConfig()->getCustomerNumber() . ':'
            . $this->getConfig()->getAPIKey()
        );
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