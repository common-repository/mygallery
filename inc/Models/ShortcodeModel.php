<?php
namespace MyGallery\Models;

use MyGallery\Traits\ConfigParse;
use MyGallery\Traits\Helpers;
use MyGallery\Traits\Images;

/**
 * Operates with shortcode object.
 *
 * PHP version 7.0
 *
 * @package Models
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */

class ShortcodeModel
{
    /**
     * Add setConfig() method.
     */
    use ConfigParse;
    /**
     * Add createImageObject() method.
     */
    use Images;
    /**
     * Add removeBrackets() method.
     */
    use Helpers;
     /**
     * RegExp pattern to get shortcode name.
     *
     * @var string
     */
    protected $shortcodeNamePattern = '/(?P<name>my\-gallery)/i';
    /**
     * Not parsed shortcode string.
     *
     * @var string
     */
    protected $originalCode;
    /**
     * Array of images ids.
     *
     * @var array
     */
    protected $imagesIds = array();
    /**
     * Title of the gallery.
     *
     * @var string
     */
    protected $title = '';
    /**
     * Array of image urls.
     *
     * @var array
     */
    public $images;
    /**
     * Object with parsed parts of shortcode[ids,config,misc]
     * ids- ids of images [string]
     * config-gallery config [string]
     * misc-gallery title [string].
     *
     * @var \stdClass
     */
    public $code;
    /**
     * Gallery settings. If settings was not set than set to default.
     *
     * @var \stdClass
     */
    public $settings;
    /**
     * Init function
     *
     *  @param string $code
     *  @param \stdClass $default_settings
     */
    public function __construct($code, $default_settings)
    {
        $replace_quotes_code=preg_replace('/(&quot;)/i', '"', $code);
        $this->originalCode = substr_replace($replace_quotes_code, ' ', -1, 0);
        $this->settings = $default_settings;
        $this->code = (object) array(
            'ids' => '',
            'config' => '',
            'misc' => '',
        );
        $this->init();
    }
    /**
     * Initialize.
     *
     * @return void
     */
    protected function init()
    {

        $this->parseCode();
    }
    /**
     * Creates shortcode object in proper format for front end
     *
     * @return object
     */
    public function toObject()
    {
        return (object) array(
            'code' => $this->code,
            'images' => $this->images,
            'settings' => $this->settings,
            '_originalCode' => $this->originalCode,
        );
    }
    /**
     * Parse shortcode attributes with shortcode_parse_atts() wp function.
     *
     * @return void
     */
    protected function parseCode()
    {
       
        $attr = \shortcode_parse_atts($this->originalCode);
         //parse image ids
        $this->parseImageIds($attr);
        //parsing and escaping gallery title
        $this->parseTitle($attr);
        //parsing gallery config
        $this->parseConfig($attr);
    }
    /**
     * Parse gallery images ids and create array of image objects with image properties.
     *
     * @param array $attr Array of shortcode attribute.
     * @return array
     */
    protected function parseImageIds(array $attr)
    {
        $ids='';
        if (isset($attr['ids'])) {
            $ids = $this->removeBrackets($attr['ids']);
            $this->images = $this->setImage(explode(',', $ids));
            $this->code->ids = 'ids=' . $ids;
        }

        return  explode(',', $ids);
    }
    /**
     * Parse gallery title.
     *
     * @param array $attr Array of shortcode attribute.
     * @return string
     */
    protected function parseTitle(array $attr)
    {
        if (isset($attr['title'])) {
            $title = $this->removeBrackets($attr['title']);
            $this->title = esc_html($title);
            $this->settings->misc->title = $this->title;
            $this->code->misc .= 'title="' . $this->title . '"';
        }
        return $this->title;
    }
    /**
     * Parse gallery config.
     *
     * @param array $attr Array of shortcode attribute.
     * @return string
     */
    protected function parseConfig(array $attr)
    {
        $config='';
        if (isset($attr['config'])) {
            $config = $this->removeBrackets($attr['config']);
            $this->settings->config = $this->setConfig($config);
            $this->code->misc .= ' config=' . (int) $config;
        }
        return $config;
    }
    /**
     * Facade function for Images Trait function
     *
     * @param array $ids Image ids.
     * @return array
     */
    protected function setImage(array $ids)
    {

        return $this->createImageObject($ids);
    }

    /**
     * Set default structure for empty shortcode object.
     *
     * @return void
     */
    protected function setEmptyShortcode()
    {
        $this->images = array();
        $this->code = (object) array(
            'ids' => '',
            'config' => '',
            'misc' => '',
        );
    }
    /**
     * Check if shortcode has correct name.
     *
     * @return boolean
     */
    protected function checkShortcodeName()
    {
        preg_match($this->shortcodeNamePattern, $this->originalCode, $match);

        return isset($match['name']);
    }
}
