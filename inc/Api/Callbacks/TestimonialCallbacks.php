<?php

/**
 * @package MhPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class TestimonialCallbacks extends BaseController
{

  public function shortcodePage(){
    
    return require_once("$this->plugin_path/templates/admin/subpages/testimonial.php");
  }
}
