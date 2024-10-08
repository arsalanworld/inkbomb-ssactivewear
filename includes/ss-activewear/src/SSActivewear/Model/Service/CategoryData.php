<?php
namespace SSActivewear\Model\Service;

use SSActivewear\Model\Config;

class CategoryData extends AbstractService
{
    /**
     * @var string
     */
    protected $uri = Config::API_V2 . '/categories/';
}