<?php
namespace SSActivewear\View\Template;

class Renderer extends \InkbombCore\View\Template\Renderer
{
    /**
     * @return string
     */
    public static function getTemplateDir(): string
    {
        return INKBOMB_SS_CLASS_PATH . 'View/html/';
    }
}