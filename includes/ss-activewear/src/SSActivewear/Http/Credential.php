<?php
namespace SSActivewear\Http;

use SSActivewear\Model\Config;

class Credential implements \InkbombCore\Http\CredentialInterface
{
    /**
     * Authentication ID.
     */
    const CUSTOMER_NUMBER = 'customerNumber';

    /**
     * Authentication Password.
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
     * @return Config
     */
    private function getConfig()
    {
        if ( empty( $this->config ) ) {
            $this->config = new Config();
        }

        return $this->config;
    }
}