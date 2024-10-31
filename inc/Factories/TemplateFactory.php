<?php
namespace MyGallery\Factories;

use MyGallery\View\TemplateRender;

/**
 * Factory class creates instance of TemplateRender class
 *
 * PHP version 7.0
 *
 * @package  Factories
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */

class TemplateFactory
{

    /**
     * Getter for creating new instance
     *
     * @param integer $templatePath path to template file.
     * @return TemplateRender
     */

    public static function get(string $templatePath)
    {
        return new TemplateRender($templatePath);
    }
}
