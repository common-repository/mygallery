<?php
namespace MyGallery\Menu\Admin;

use MyGallery\Interfaces\MenuPageInterface;
use MyGallery\Utils\MenuConfig;
use MyGallery\Traits\TemplateFactoryFacade;

/**
 * Class renders menu page.
 *
 * PHP version 7.0
 *
 * @package Menu
 * @author  Evgeniy S.Zalevskiy <2600@ukr.net>
 * @license MIT
 */

class MenuPage implements MenuPageInterface
{
    /**
     * Trait adds getTemplate() method.
     */
    use TemplateFactoryFacade;
    /**
     * Object of menu configuration class that holds menu configs.
     *
     * @var object
     */
    protected $config;
    /**
     * Init function.
     *
     * @param MenuConfig $config Object stdClass that holds menu configs.
     */
    public function __construct(MenuConfig $config)
    {
        $this->init($config);
    }
    /**
     * Initiate and add menu configuration file.
     *
     * @return void
     */
    public function init(MenuConfig $config)
    {
        $configObject=$config->get();
        if (!is_object($configObject)) {
            throw new Exception('Wrong config data format.Should be instance of stdClass');
        }
        $this->config = $configObject;
        \add_action('admin_menu', array($this, 'addMainMenu'));
    }
    /**
     * Callback for "admin_menu" action
     *
     * @return void
     */
    public function addMainMenu()
    {

        $menu = $this->config->menu;
        \add_menu_page($menu->page_title, $menu->menu_title, $menu->capability, $menu->menu_slug, '', $menu->icon);
        $this->addSubMenus();
    }
  
    /**
     * Renders submenu
     *
     * @return void
     */
    public function addSubMenus()
    {
        $subMenu = $this->config->menu->subs;
        foreach ($subMenu as $sub) {
            $template = $this->getTemplate($sub->template);
            \add_submenu_page($sub->parent_slug, $sub->page_title, $sub->menu_title, $sub->capability, $sub->menu_slug, array($template, 'renderWithEcho'));
        }
    }
}
