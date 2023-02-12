<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class Enqueue extends BaseController
{

  /**
   * register method always use all the classes for tigger methods
   *
   * @return void
   */
  public function register()
  {
    add_action('admin_enqueue_scripts', array($this, 'enqueue'));
  }

  public function enqueue()
  {
    //enqueue all our scripts

    wp_enqueue_script( 'media-upload' );

    wp_enqueue_media();

    wp_enqueue_style('mypluginstyle', $this->plugin_url . '/assets/mystyle.min.css', __FILE__);
    wp_enqueue_script('mypluginscript', $this->plugin_url . '/assets/myscript.min.js', __FILE__);
  }
}
