<?php
namespace MyGallery\Factories;
use MyGallery\Factories\ShortcodeFactory;
use MyGallery\Models\PostModel;

/**
 * Factory class creates instance of PostModel class
 *
 * PHP version 7.0
 *
 * @package  Factories
 * @author   Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license  MIT
 */

class PostFactory
{   
    /**
     * Shortcode factory.
     *
     * @var ShortcodeFactory
     */
    protected $factory;
    /**
     * Init function.
     */
    public function __construct(ShortcodeFactory $factory){
        $this->factory=$factory;
    }
    /**
     * Getter for creating new instance.
     *
     * @param integer $postId Id of post.
     * @return PostModel
     */

    public function get($postId)
    {
        return new PostModel($postId,$this->factory);
    }
}
