<?php

namespace MyGallery\View;

use MyGallery\Factories\ShortcodeFactory;
use MyGallery\Traits\ConfigParse;
use MyGallery\Traits\Helpers;
use MyGallery\Traits\Images;
use MyGallery\Traits\TemplateFactoryFacade;

/**
 * Render template for slider and gallery.
 *
 * PHP version 7.0
 *
 * @package View
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT https://opensource.org/licenses/MIT
 */

class Slider
{
    use Images;
    use ConfigParse;
    use Helpers;
    /**
     * Adds getTemplate() method.
     */
    use TemplateFactoryFacade;

    protected $template;

    public function __construct(string $templatePath)
    {
        $this->template = $this->getTemplate($templatePath);
        $this->registerShortcode();
    }
    /**
     * Register shortcode.
     *
     * @return void
     */
    protected function registerShortcode()
    {
        add_shortcode('my-gallery', array($this, 'render'));
    }

    /**
     * Render html for slider from template.
     *
     * @param array $attr Parameters for slider render.
     *
     * @return string
     */
    public function render($attr)
    {
        if (count($attr) === 0 || !isset($attr['ids'])) {
            return '';
        }

        //there is no filter to get shortcode before it will be parsed and passed as array of $attr to
        //do_shortcode_tag() function. /wp-includes/shortcodes.php #199 do_shortcode()

        $attr = isset($attr[0]) ? $this->fixParsingProblems($attr) : $attr;
        if (\is_wp_error($attr)) {
            return '';
        }

        $imageIds = explode(',', $attr['ids']);
        //Variables that need to be included in template.
        $args = array(
            'title' => $this->getTitle($attr),
            'config' => $this->getConfig($attr),
            'images' => $this->createImageObject($imageIds, array('full', 'thumbnail')),
            'boolToString' => array($this, 'boolToString'),
        );
  
        $content = $this->template->addArguments($args)->render();
        return $content;
    }
    /**
     * Get title from array of attributes.
     *
     * @param array $attr Array of shortcode attributes.
     * @return  string
     */
    protected function getTitle(array $attr)
    {
        if (isset($attr['title'])) {
            return $this->removeProblemSymbols($attr['title']);
        } else {
            return '';
        }
    }
    /**
     * Get config from array of attributes.
     *
     * @param array $attr Array of shortcode attributes.
     * @return object Object of stdClass with default configs.
     */
    protected function getConfig(array $attr)
    {
        if (isset($attr['config'])) {
            return $this->setConfig((int) $attr['config']);
        } else {
            return (ShortcodeFactory::$defaultSettings)->config;
        }
    }
    /**
     * Solve problem with parsing shortcode parameters.
     * Some time shortcode was saved with &qoute; instead of quotes.It confuses WP regexp.
     * and $attr array instead param name contains parts of title with digital key.
     * This function solves this problem.It finds and glue params with keys == digits in one string.
     * Вo not throw Exceptions because this is not a critical error.
     * And Exceptions adversely affect performance.
     *
     * @param array $attr Array with parsed shortcode attributes.
     *
     * @return array
     */

    protected function fixParsingProblems($attr)
    {
        $pattern = '/(?P<digit_key>[\d]+)/i';
        $new_attr = array();
        $counter = 0;
        $prop_value = '';
        $prop_name = null;
        foreach ($attr as $key => $item) {
            if ($key === 0 && $counter === 0) {
                return new \WP_Error('parsing_shortcode_error', 'Wrong shortcode format.');
            }

            preg_match_all($pattern, $key, $matches);
            if (count($matches['digit_key']) > 0) {
                $prop_value .= $item . ' ';
            } else {
                if ($counter > 0) {
                    $new_attr[$prop_name] = $this->removeProblemSymbols($prop_value);
                }

                $prop_value = $item . ' ';
                $prop_name = $key;
            }
            $counter++;
        }
        $new_attr[$prop_name] = $prop_value;
        return $new_attr;
    }
}
