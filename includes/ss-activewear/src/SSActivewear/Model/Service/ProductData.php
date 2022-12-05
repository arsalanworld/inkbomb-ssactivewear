<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class ProductData extends AbstractService
{
    private $VariationsUri = Config::API_V2 . '/products/';

    private $specsUri = Config::API_V2 . '/specs/';
}