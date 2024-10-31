<?php
namespace MyGallery\Traits;

use MyGallery\Factories\TemplateFactory;
use MyGallery\View\TemplateRender;

/**
 * Trait for include facade method for Template Factory.
 *
 * PHP version 7.0
 *
 * @package  Factories
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */

trait TemplateFactoryFacade
{
    /**
     * Facade for templateFactory return TemplateRenderer class.
     *
     * @param string $templatePath Path to template.
     * @return TemplateRender
     */
    protected function getTemplate(string $templatePath)
    {
        return TemplateFactory::get($templatePath);
    }
}
