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
     * @param string|int|null $fields
     * @return array
     * @throws \Exception
     */
    public function filterFields( $fields ): array
    {
        return $this->filterResultsByKey( "fields", $fields );
    }

    /**
     * @param string $key
     * @param string|int|null $value
     * @return array
     * @throws \Exception
     */
    protected function filterResultsByKey( string $key, $value ): array
    {
        return $this->sendRequest( "{$this->uri}?{$key}={$value}" );
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
        } else {
            $url .= "&";
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