<?php
namespace SSActivewear\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use SSActivewear\Model\Credentials;

class Api implements \InkbombCore\Http\ApiInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @inheritDoc
     */
    public function execute($httpRequest)
    {
        $options = $httpRequest->getOptions();
        $header = $httpRequest->getHeaders();
        try {
            if ( !isset( $header['credentials'] ) || !$header['credentials'] instanceof Credentials) {
                throw new \Exception("Credentials object is missing.");
            }

            $response = $this->getClient()->request(
                $httpRequest->getMethod(),
                $httpRequest->getUri(),
                [
                    'headers' => [
                        'Content-Type' => isset($header['contentType']) ?? 'application/json',
                        'Authorization' => 'Basic ' . $header['credentials']->getBasicAuthentication(),
                    ],
                    'body' => $httpRequest->getBody()
                ]
            );

            return json_decode( $response->getBody()->getContents(), true );
        } catch (GuzzleException|\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @return Client
     */
    private function getClient(): Client
    {
        if ( empty( $this->client ) ) {
            $this->client = new Client();
        }
        return $this->client;
    }
}