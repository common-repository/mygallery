<?php
namespace MyGallery\Message;

/**
 * Class success message storage
 *
 * PHP version 7.0
 *
 * @package  Message
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */
class Success
{
/**
 * Text message
 *
 * @param string $slug Slug of the message.
 * @return string|void
 */
    public static function text(string $slug)
    {
        switch ($slug) {
            case 'SUCCESS':
                return \__('Operation was successful.', MYGALLERY_PLUGIN_SLU);
        }
    }
}
