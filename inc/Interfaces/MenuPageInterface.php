<?php
namespace MyGallery\Interfaces;

use MyGallery\Utils\MenuConfig;

/**
 * Interface for Menu pages class.
 *
 * PHP version 7.0
 *
 * @package  Menu
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */
interface MenuPageInterface
{
    /**
     * Init menu method.
     *
     * @param MenuConfig $config Instance of MenuConfig that provides menu config data.
     * @return void
     */
    public function init(MenuConfig $config);
}
