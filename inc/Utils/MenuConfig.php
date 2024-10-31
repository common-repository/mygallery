<?php

namespace MyGallery\Utils;

/**
 * Class load menu config file.
 *
 * PHP version 7.0
 *
 * @package Utils
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT https://opensource.org/licenses/MIT
 */
class MenuConfig
{

    /**
     * Array with configs that need to create WP admin menu.
     *
     *  @var array
     */
    protected $config;
    /**
     * Template that checks input main menu config structure.
     *
     * @var array
     */
    protected $main_menu_keys=array(
        'page_title'=>0,
        'menu_title'=>1 ,
        'capability'=>2,
        'menu_slug'=>3,
        'template'=>4,
        'icon'=>5
    );
    /**
     * Template that checks input sub menu config structure.
     *
     * @var array
     */
    protected $sub_menu_keys=array(
        'page_title'=>0,
        'parent_slug'=>1,
        'menu_title'=>2,
        'capability'=>3,
        'menu_slug'=>4,
        'template'=>5
    );
    /**
     * Init function.
     *
     * @param string $config_path Path to file with config array.
     * @throws \Exception
     */
    public function __construct(string $config_path)
    {
        if (!file_exists($config_path)) {
            throw new \Exception('Cannot load template file '.$config_path);
        }
        $config=include $config_path;
        $this->verifyFormat($config['menu']);
        $this->config=$config;
    }
    /**
     * Get the menu config in diff formats
     *
     * @param string $format Format of output config.
     * @return object|array|string
     */
    public function get(string $format = 'object')
    {
        $config_json=json_encode($this->config);
        switch ($format) {
            case 'object':
                return json_decode($config_json);
            case 'json':
                return $config_json;
            case 'array':
                return json_decode($config_json, true);
        }
    }
    /**
     * Verify of menu config file format.
     *
     * @param array $config Menu config file.
     * @return void
     */
    protected function verifyFormat(array $config)
    {
        $check_main_menu=array_diff_key($this->main_menu_keys, $config);
        if (count($check_main_menu)>0) {
            $this->wrongFormat($check_main_menu, 'main');
        }
        if (array_key_exists('subs', $config)&&is_array($config['subs'])) {
            foreach ($config['subs'] as $sub_menu) {
                $check_sub_menu=array_diff_key($this->sub_menu_keys, $sub_menu);
                if (count($check_sub_menu)>0) {
                    $this->wrongFormat($check_sub_menu, 'sub');
                }
            }
        }
    }
    /**
     * Get missed keys and throw Exception.
     *
     * @param array $check_main_menu Array of missed keys.
     * @param string $menu_type Should be main|sub.
     * @throws \Exception
     * @return void
     */
    protected function wrongFormat(array $check_main_menu, string $menu_type)
    {
        switch ($menu_type) {
            case 'main':
                $menu_keys=$this->main_menu_keys;
                break;
            case 'sub':
                $menu_keys=$this->sub_menu_keys;
                break;
            default:
                throw  new \Exception('Wrong menu type name.Menu type should be "main" or "sub".You Entered: '.$menu_type);
        }
        $missed_keys=[];
        foreach ($check_main_menu as $value) {
            $missed_keys[]=array_search($value, $menu_keys);
        }
            throw new \Exception('Wrong main menu config file format.No needed keys in config file: '.implode(',', $check_main_menu).'.You need to add "'. implode(',', $missed_keys).'" parameter');
    }
}
