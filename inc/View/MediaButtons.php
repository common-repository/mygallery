<?php

namespace MyGallery\View;

use MyGallery\Traits\TemplateFactoryFacade;

/**
 * Render media button for classic editor
 *
 * PHP version 7.0
 *
 * @package View
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT https://opensource.org/licenses/MIT
 */

class MediaButtons
{
    /**
     * Add getTemplate() method
     */
    use TemplateFactoryFacade;
    /**
     * Instance of TemplateRender that renders from template with args.
     *
     * @var MyGallery\View\TemplateRender;
     */
    protected $template;
    /**
     * Init function.
     *
     * @param string $templatePath Path to  Media buttons template.
     */
    public function __construct(string $templatePath)
    {
        $this->template =  $this->getTemplate($templatePath);
        $this->registerFilters();
    }
    /**
     * Add filter to edit media buttons content.
     *
     * @return void
     */
    protected function registerFilters()
    {
        add_filter('media_buttons_context', array($this, 'renderMediaButton'));
    }
    /**
     * Render template of media button.
     *
     * @param string $buttons Content of media button.
     * @return string
     */
    public function renderMediaButton(string $buttons)
    {
        $button = $this->template->render();
        return $buttons . $button;
    }
}
