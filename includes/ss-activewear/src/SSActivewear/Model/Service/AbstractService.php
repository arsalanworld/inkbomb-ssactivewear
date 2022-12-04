<?php
namespace SSActivewear\Model\Service;

use InkbombCore\Http\Request;
use SSActivewear\Http\Api;
use SSActivewear\Model\Config;
use SSActivewear\Model\Credentials;

abstract class AbstractService
{
    /**
     * @var Api
     */
    private $api;

    /**
     * @var Credentials
     */
    private $credentials;

    /**
     * @param string $uri
     * @param string|null $method
     * @param string|null $mediaType
     * @param string|null $body
     * @return array
     * @throws \Exception
     */
    public function sendRequest(string $uri, ?string $method = 'GET', ?string $mediaType = '', ?string $body = ''): array
    {
        if ( empty( $mediaType ) ) {
            $mediaType = Config::MEDIA_TYPE_JSON;
        }

        $url = Config::ENDPOINT . $uri;
        if ( !str_contains( $url, "?" ) ) {
            $url .= "?";
        }

        $url .= "mediatype={$mediaType}";
        $httpRequest = new Request(
            [],
            [
                "contentType" => "application/json",
                "credentials" => $this->getCredentials()
            ],
            $body,
            $method,
            $url
        );

        return $this->getApi()->execute($httpRequest);
    }

    /**
     * @return Credentials
     */
    private function getCredentials(): Credentials
    {
        if ( empty( $this->credentials ) ) {
            $this->credentials = new Credentials();
        }
        return $this->credentials;
    }

    /**
     * @return Api
     */
    private function getApi(): Api
    {
        if ( empty( $this->api ) ) {
            $this->api = new Api();
        }

        return $this->api;
    }
}