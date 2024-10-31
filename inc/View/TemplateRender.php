<?php

namespace MyGallery\View;

/**
 * Class loading template file and render it with args.
 *
 * PHP version 7.0
 *
 * @package View
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT https://opensource.org/licenses/MIT
 */

class TemplateRender
{
    /**
     * Path to template file.
     *
     * @var string
     */
    protected $path;
    /**
     * Array of arguments that need to be included in template.
     *
     * @var array
     */
    protected $args;
    /**
     * Init function.
     *
     * @param string $template_path Path to template file.
     * @param array $args Array of arguments that should be available to this template.
     */
    public function __construct(string $template_path = "", array $args = [])
    {
        if (!empty($template_path)) {
            $this->isTemplateExists($template_path);
            $this->path = $template_path;
            $this->args = $args;
        }
    }
    /**
     * Check if template file exists and has right format.
     *
     * @param string $template_path Path to template file.
     * @return void
     */
    protected function isTemplateExists(string $template_path)
    {
        if (!file_exists($template_path) && strpos($template_path, '.php', -5) == -1) {
            throw new \Exception('Cannot load template file or file in wrong format ' . $template_path);
        }
    }
    /**
     * Adds new template for render.
     *
     * @param string $template_path Path to template file.
     * @param array $args args that should be available to this template.
     * @return void
     */
    public function addNewTemplate(string $template_path, array $args = [])
    {
        $this->isTemplateExists($template_path);
        $this->path = $template_path;
        $this->args = $args;
    }
    /**
     * Add arguments used in template.
     *
     * @param array $args arguments that should be used in template.
     * @return void
     */
    public function addArguments(array $args = [])
    {
        $this->args = $args;
        return $this;
    }
    /**
     * Echo rendered content.
     *
     * @return void
     */
    public function renderWithEcho()
    {
        echo $this->render();
    }
    /**
     * Render template.
     *
     *
     * @return string
     */
    public function render()
    {
        if (empty($this->path)) {
            throw new \Exception('Path did not set ' . $template_path);
        }

        if (count($this->args) > 0) {
            foreach ($this->args as $key => $item) {
                ${$key} = $item;
            }
        }
     
        ob_start();
        include $this->path;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }
}
