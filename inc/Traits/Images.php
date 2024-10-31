<?php
namespace MyGallery\Traits;

/**
 * Get image urls and creates object
 *
 * PHP version 7.0
 *
 * @package Models
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */
trait Images
{
    /**
     * Creates object with image ids and urls.
     *
     * @param array $imageIds Array of image ids.
     * @param string|array $size Size of images could be a string or array.
     * @return object
     */
    protected function createImageObject(array $imageIds, $size = 'thumbnail')
    {
        $image=array();
        foreach ($imageIds as $image_id) {
            $image_url=$this->getImageUrl((int)$image_id, $size);
            if ($image_url) {

                $image[]=(object)array(
                    'id'=>(int)$image_id,
                    'url'=>$image_url,
                    'alt'=>$this->getImageMeta((int)$image_id),
                    'title'=>$this->getImageTitle((int)$image_id)
                );
            }
        }
        return $image;
    }
    /**
     * Get image url using wp_get_attachment_image_src() function.
     *
     * @param integer $id
     * @param string|array $size Size of images could be a string or array.
     * @return object|boolean
     */
    protected function getImageUrl(int $id, $size = 'thumbnail')
    {
        $image=array();
        if (gettype($size)=='string') {
            $image_url=wp_get_attachment_image_src($id, $size);
            return $image_url[0];
        } elseif (gettype($size)=='array') {
            foreach ($size as $size_name) {
                $image[$size_name]=wp_get_attachment_image_src($id, $size_name);
            }
            return (object)$image;
        }
         return false;
    }
    /**
     * Get image alt meta.
     *
     * @param integer $id Image attachment id.
     * @return string
     */
    protected function getImageMeta(int $id){
        $image_alt=get_post_meta($id, '_wp_attachment_image_alt', true);
        return $image_alt;
    }
    /**
     * Gets image title.
     *
     * @param integer $id Image attachment id.
     * @return string
     */
    protected function getImageTitle(int $id){
        $image_title=get_the_title($id);
        return $image_title;
    }
}
