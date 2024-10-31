<?php
namespace MyGallery\Traits;

/**
 * Parsing config parameters from
 *
 * PHP version 7.0
 *
 * @package Models
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */
trait ConfigParse
{
    /**
     * Create config object from parsed data.
     *
     * @param integer $configCode code of config params.
     * @return object
     */
    protected function setConfig(int $configCode)
    {

        $configs=preg_split('//u', $configCode, null, PREG_SPLIT_NO_EMPTY);
        return (object)array(
            'galleryMode'=>(bool)$configs[0],
            'loop'=>(bool) $configs[1],
            'thumbsNumber'=>$configs[2],
            'items'=>$configs[3]
        );
    }
}
