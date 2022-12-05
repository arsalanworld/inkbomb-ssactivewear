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
     * @var string
     */
    protected $uri;

    /**
     * Returns all data for requested service.
     *
     * @return array
     * @throws \Exception
     */
    public function getAll(): array
    {
        return $this->sendRequest( $this->uri );
    }

    /**
     * Returns requested service by filter.
     * The parameter can be a comma separated identifier ids or an Identifier id.
     *
     * @param string $identifier
     * @return array
     * @throws \Exception
     */
    public function filterResults( string $identifier ): array
    {
        return $this->sendRequest($this->uri . $identifier);
    }

    /**
     * Returns specifically requested fields.
     * {fields} parameter is comma separated list of requested service object fields.
     *
     * @param string $fields
     * @return array
     * @throws \Exception
     */
    public function filterFields( string $fields ): array
    {
        return $this->sendRequest( "{$this->uri}?fields={$fields}" );
    }

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