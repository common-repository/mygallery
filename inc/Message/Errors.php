<?php
namespace MyGallery\Message;

/**
 * Class error message storage
 *
 * PHP version 7.0
 *
 * @package  Message
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */
class Errors
{
/**
 * Text message
 *
 * @param string $slug Slug of the message.
 * @return string|void
 */
    public static function text($slug)
    {
        switch ($slug) {
            case 'NO_RIGHTS_TO_READ':
                return \__('Sorry, but you do not have permission to read posts data', MYGALLERY_PLUGIN_SLUG);
            case 'NO_RIGHTS_TO_WRITE':
                return \__('Sorry, but you do not have permission to edit posts data', MYGALLERY_PLUGIN_SLUG);
        }
    }
}
