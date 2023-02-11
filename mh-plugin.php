<?php
/*
Plugin Name: Hasan Plugin
Plugin URI:
description: Hello! This is a test plugin. I am learning WordPress plugin development in this file. !
Version: 1.0.0
Author: Md Al Mehedi Hasan
*/

/**
 * @package MhPlugin
 */



//If this file is called unathorised way, this line will abort the action
defined('ABSPATH') or die('Hey, you can\t access this file, you silly human!!!');

// Require once the Composer Autoload
if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
  require_once dirname(__FILE__) . '/vendor/autoload.php';
}


/**
 * This code runs during plugin activation 
 */
// use Inc\Base\Activate;
function activate_mh_plugin()
{
  // Activate::activate();
  Inc\Base\Activate::activate();
}
register_activation_hook(__FILE__, 'activate_mh_plugin');

/**
 * This code runs during plugin deactivation 
 */
// use Inc\Base\Deactivate;
function deactivate_mh_plugin()
{
  // Deactivate::deactivate();
  Inc\Base\Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_mh_plugin');


/**
 * Initialize all the core classes of the plugin
 */
if (class_exists('Inc\\Init')) {
  Inc\Init::register_services();
}
