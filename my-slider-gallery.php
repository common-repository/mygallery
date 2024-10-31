<?php

/*
Plugin Name: MySliderGallerys   
Plugin URI: https://github.com/zalevsk1y/my-slider-gallery
Description: Plugin was renamed. MyGallery become MySliderGallery. Add slider and gallery to your post fast and easy.
Version: 2.0.8
Author: Evgeny S.Zalevskiy <2600@ukr.net>
Author URI: https://github.com/zalevsk1y/
License: MIT
 */
?>
<?php
namespace MyGallery;

if (! defined('ABSPATH')) {
    exit;
}


define("MYGALLERY_PLUGIN_VERSION", "2.0.8");
define("MYGALLERY_PLUGIN_SLUG", "my-gallery");
define("MYGALLERY_PLUGIN_SHORTCODE", "my-gallery");
define("MYGALLERY_PLUGIN_NAMESPACE", __NAMESPACE__);
define("MYGALLERY_PLUGIN_URL", plugins_url("", __FILE__));
define("MYGALLERY_PLUGIN_DIR", plugin_dir_path(__FILE__));

require "autoload.php";
$default_gallery_settings=require MYGALLERY_PLUGIN_DIR.'defaultGallerySettings.php';
$slider_template_path=MYGALLERY_PLUGIN_DIR . "/template/slider/content-slider.php";
$media_buttons_template_path=MYGALLERY_PLUGIN_DIR . "/template/media-button/media-button.php";
$modules = [];

$modules['menu_config']=new Utils\MenuConfig(MYGALLERY_PLUGIN_DIR.'menu-config.php');
//---Factories
$modules['shortcode_model_factory']=new Factories\ShortcodeFactory($default_gallery_settings);
$modules['post_model_factory']=new Factories\PostFactory($modules['shortcode_model_factory']);
//---Admin menu modules

$modules['menu_page'] = new Menu\Admin\MenuPage($modules['menu_config']);
$modules["main"] = new Core\Main($modules['menu_config']);
$modules["slider"] = new View\Slider($slider_template_path);
$modules['media_buttons']=new View\MediaButtons($media_buttons_template_path);
$modules["rest_posts_list"] = new Rest\PostsListController();
$modules["rest_shortcodes_get"] = new Rest\ShortcodeController($modules['post_model_factory']);
