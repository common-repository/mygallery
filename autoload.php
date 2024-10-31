<?php

function my_gallery_plugin_autoload($class)
{

    if (strpos($class, MYGALLERY_PLUGIN_NAMESPACE) === false) {
        return;
    }

    $class_mod = str_replace(MYGALLERY_PLUGIN_NAMESPACE . '\\', '', $class);
    $cl = str_replace('\\', '/', $class_mod);
    $path = __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . $cl . ".php";
    if (file_exists($path)) {
        include $path;
    }
}

spl_autoload_register("my_gallery_plugin_autoload");
