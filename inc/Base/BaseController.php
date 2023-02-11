<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

/**
 * This BaseController class is only exteneds by other class
 * In this class we define all the require_once and use
 */

class BaseController
{
  public $plugin_path;
  public $plugin_url;
  public $plugin_basename;

  public $managers = array();

  public function __construct()
  {
    /**
     * second perameter is the postion of this file from root. this file is in "Base" folder which is in "inc" folder and "inc" is in root folder, which meens this file postion is in 2
     * this number also define how many times we need to go back to have the exect directory, like for basename we have to go back 3 times
     */
    $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
    $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
    $this->plugin_basename = plugin_basename(dirname(__FILE__, 3) . '/mh-plugin.php');

    $this->managers = array(
      'cpt_manager' => 'Activate CPT Manager',
      'taxonomy_manager' => 'Activate Taxonomy Manager',
      'media_widget' => 'Activate Media Widget',
      'gallery_manager' => 'Activate Gallery Manager',
      'testimonial_manager' => 'Activate Testimonial Manager',
      'templates_manager' => 'Activate Templates Manager',
      'auth_manager' => 'Activate Auth Manager',
      'membership_manager' => 'Activate Membership Manager',
      'chat_manager' => 'Activate Chat Manager',
    );
  }

  public function activated($key){
    
    $option = get_option( 'mh_admin' );

		return isset( $option[ $key ] ) ? $option[ $key ] : false;
  }
}
