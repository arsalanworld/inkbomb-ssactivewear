<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class StylesData extends AbstractService
{
    /**
     * @var string
     */
    protected $uri = Config::API_V2 . '/styles/';
}