<?php
namespace MyGallery\Factories;

use MyGallery\Models\ShortcodeModel;

/**
 * Factory class creates instance of ShortcodeModel class
 *
 * PHP version 7.0
 *
 * @package  Factories
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */
class ShortcodeFactory
{
    public static $defaultSettings;
    /**
     * Init function.Set default settings for shortcode parameters.
     *
     * @param \stdClass $settings default settings for gallery.
     * 
     */
    public function __construct(\stdClass $settings)
    {
        self::$defaultSettings = $settings;
    }
    /**
     * Getter for creating new instance.
     *
     * @param string $code shortcode.
     * @return ShortcodeModel
     */
    public function get(string $code)
    {
        if (self::$defaultSettings) {
            return new ShortcodeModel($code, self::$defaultSettings);
        }
    }
}
