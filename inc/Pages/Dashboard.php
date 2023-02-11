<?php

/**
 * @package MhPlugin
 */

namespace Inc\Pages;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use Inc\Api\Callbacks\ManagerCallbacks;

class Dashboard extends BaseController
{
  public $settings;

  public $callbacks;
  public $callbacks_manager;

  public $pages = array();

  // public $subpages = array();
  // public $subpages2 = array(); 

  public function register()
  {
    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();
    $this->callbacks_manager = new ManagerCallbacks();

    $this->setPages();
    // $this->setSubpages();

    $this->setSettings();
    $this->setSections();
    $this->setFields();

    // $this->settings->addPages($this->pages)->withSubPage('Dashboard')->addSubPages($this->subpages)->addSubPages($this->subpages2)->register();
    $this->settings->addPages($this->pages)->withSubPage('Dashboard')->register();
  }

  public function setPages()
  {
    $this->pages = [
      [
        'page_title' => 'Mh Admin',
        'menu_title' => 'Admin Settings',
        'capability' => 'manage_options',
        'menu_slug' => 'mh_admin',
        'callback' => array($this->callbacks, 'adminDashboard'),
        'icon_url' => 'dashicons-heart',
        'position' => 110
      ],
      [
        'page_title' => 'Mh Store',
        'menu_title' => 'Store Settings',
        'capability' => 'manage_options',
        'menu_slug' => 'mh_store',
        'callback' => array($this->callbacks, 'storeDashboard'),
        'icon_url' => 'dashicons-store',
        'position' => 109
      ]
    ];
  }

  /**
   * setSubpages() divided into other controller
   *
   */
  // public function setSubpages()
  // {
  //   $this->subpages = array(
  //     array(
  //       'parent_slug' => 'mh_admin',
  //       'page_title' => 'Custom Post type',
  //       'menu_title' => 'CPT',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'mh_cpt',
  //       'callback' => array($this->callbacks, 'adminCPT')
  //     ),

  //     array(
  //       'parent_slug' => 'mh_admin',
  //       'page_title' => 'Custom Taxonomies',
  //       'menu_title' => 'Taxonomies',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'mh_taxonomies',
  //       'callback' => array($this->callbacks, 'adminTaxonomies'),
  //     ),

  //     array(
  //       'parent_slug' => 'mh_admin',
  //       'page_title' => 'Custom Widgets',
  //       'menu_title' => 'Widgets',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'mh_widgets',
  //       'callback' => array($this->callbacks, 'adminWidgets'),
  //     )
  //   );

  //   $this->subpages2 = array(
  //     array(
  //       'parent_slug' => 'mh_store',
  //       'page_title' => 'Custom Post type',
  //       'menu_title' => 'CPT',
  //       'capability' => 'manage_options',
  //       'menu_slug' => 'mh_cpt2',
  //       'callback' => array($this->callbacks, 'storeCPT'),
  //     ),
  //   );
  // }

  public function setSettings()
  {
    /**
    * This args is database optimize code
    */
    $args[] = array(
      'option_group' => 'mh_plugin_settings',
      'option_name' => 'mh_admin',
      'callback' => array($this->callbacks_manager,'checkboxSanitize'),
        );

        /**
         * This args is non database optimize code
         */
    // $args = array();
    // foreach($this->managers as $key => $value){
    //   $args[] = array(
    //     'option_group' => 'mh_plugin_settings',
    //     'option_name' => $key,
    //     'callback' => array($this->callbacks_manager,'checkboxSanitize'),
    //   );
    // }
    
    $this->settings->setSettings($args);
  }

  public function setSections()
  {
    $args = array(
      array(
        'id' => 'mh_admin_index',
        'title' => 'Settings',
        'callback' => array($this->callbacks_manager, 'adminSectionManager'),
        'page' => 'mh_admin' //menu slug of the page that i want to show this section
      ),

    );

    $this->settings->setSections($args);
  }

  public function setFields()
  {

    $args = array();
    foreach($this->managers as $key => $value){
      $args[] = array(
        'id' => $key,
        'title' => $value,
        'callback' => array($this->callbacks_manager, 'checkboxField'),
        'page' => 'mh_admin',
        'section' => 'mh_admin_index',
        'args' => array(
          'option_name' => 'mh_admin',// 'option_name' is added to optimize database
          'label_for' => $key,
          'class' => 'ui-toggle',
        )
      );
    }

    $this->settings->setFields($args);
  }
}
