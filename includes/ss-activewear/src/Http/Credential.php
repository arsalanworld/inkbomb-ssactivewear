<?php
namespace SSActivewear\Http;

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
     * @inheritDoc
     */
    public function getAuth(): array
    {
        return [];
    }
}