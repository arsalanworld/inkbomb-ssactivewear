<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class SpecsData extends AbstractService
{
    /**
     * SpecsData uri.
     *
     * @var string
     */
    protected $uri = Config::API_V2 . '/specs/';
}