<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;

class TemplatesController extends BaseController
{

  public $settings;

  public $callbacks;

  public $subpages = array();

  public function register(){

    
    if ( ! $this->activated( 'templates_manager' ) ) return;

    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();

    $this->setSubpages();

    $this->settings->addSubPages($this->subpages)->register();
  }

  public function setSubpages()
  {
    $this->subpages = array(
      array(
        'parent_slug' => 'mh_admin',
        'page_title' => 'Templates',
        'menu_title' => 'Templates Manager',
        'capability' => 'manage_options',
        'menu_slug' => 'mh_templates',
        'callback' => array($this->callbacks, 'adminTemplates'),
      ),
    );
  }
}
