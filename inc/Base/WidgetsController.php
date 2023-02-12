<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use Inc\Base\BaseController;
use Inc\Api\Widgets\MediaWidget;

class WidgetsController extends BaseController{
  public function register(){
    if(!$this->activated('media_widget')) return;

    $media_widget = new MediaWidget();
    $media_widget->register();
  }
}
