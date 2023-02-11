<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use \Inc\Api\SettingsApi;
use \Inc\Base\BaseController;
use \Inc\Api\Callbacks\AdminCallbacks;
use \Inc\Api\Callbacks\TaxonomyCallbacks;

class TaxonomyController extends BaseController
{

  public $settings;

  public $callbacks;
  public $tax_callbacks;

  public $subpages = array();
  public $taxonomies = array();

  public function register(){

    
    if ( ! $this->activated( 'taxonomy_manager' ) ) return;

    $this->settings = new SettingsApi();

    $this->callbacks = new AdminCallbacks();
    $this->tax_callbacks = new TaxonomyCallbacks();

    $this->setSubpages();

    $this->setSettings();
    $this->setSections();
    $this->setFields();

    $this->settings->addSubPages($this->subpages)->register();

    $this->storeCustomTaxonomies();

    if(!empty($this->taxonomies)){
      add_action( 'init', array($this, 'registerCustomTaxonomy'));
    }
  }

  public function setSubpages()
  {
    $this->subpages = array(
      array(
        'parent_slug' => 'mh_admin',
        'page_title' => 'Custom Taxonomies',
        'menu_title' => 'Taxonomies',
        'capability' => 'manage_options',
        'menu_slug' => 'mh_taxonomy',
        'callback' => array($this->callbacks, 'adminTaxonomies'),
      ),
    );
  }

  public function setSettings()
  {
    $args = array(
      array(
        'option_group' => 'mh_admin_tax_settings',
        'option_name' => 'mh_admin_tax',
        'callback' => array($this->tax_callbacks,'taxSanitize'),
      ),
    );
    
    $this->settings->setSettings($args);
  }

  public function setSections()
  {
    $args = array(
      array(
        'id' => 'mh_tax_index',
        'title' => 'Custom Taxonomy Manager',
        'callback' => array($this->tax_callbacks, 'taxSectionManager'),
        'page' => 'mh_taxonomy' 
      ),

    );

    $this->settings->setSections($args);
  }

  public function setFields()
  {

    /**
     * Those followings need to set
     * 
     * taxonomy
     * singular name
     * Hierarchical
     */
    
    $args = array(
      //  taxonomy
      array(
          'id' => 'taxonomy',
          'title' => 'Custom Taxonomy ID',
          'callback' => array($this->tax_callbacks, 'textField'),
          'page' => 'mh_taxonomy',
          'section' => 'mh_tax_index',
          'args' => array(
            'option_name' => 'mh_admin_tax',
            'label_for' => 'taxonomy',
            'placeholder' => 'eg. genre',
            'array' => 'taxonomy',
          )
        ),

        // singular name
        array(
          'id' => 'singular_name',
          'title' => 'Singular Name',
          'callback' => array($this->tax_callbacks, 'textField'),
          'page' => 'mh_taxonomy',
          'section' => 'mh_tax_index',
          'args' => array(
            'option_name' => 'mh_admin_tax',
            'label_for' => 'singular_name',
            'placeholder' => 'eg. Genre',
            'array' => 'taxonomy',
          )
        ),

        // Hierarchical
        array(
          'id' => 'hierarchical',
          'title' => 'Hierarchical',
          'callback' => array($this->tax_callbacks, 'checkboxField'),
          'page' => 'mh_taxonomy',
          'section' => 'mh_tax_index',
          'args' => array(
            'option_name' => 'mh_admin_tax',
            'label_for' => 'hierarchical',
            'class' => 'ui-toggle',
            'array' => 'taxonomy',
          )
        ),
    );
    

    $this->settings->setFields($args);
  }

  public function storeCustomTaxonomies(){
    //get the taxonomies array
    $options = get_option('mh_admin_tax') ?: array();

    //store those into an array
    foreach($options as $option){
      $labels = array(
				'name'              => $option['singular_name'],
				'singular_name'     => $option['singular_name'],
				'search_items'      => 'Search ' . $option['singular_name'],
				'all_items'         => 'All ' . $option['singular_name'],
				'parent_item'       => 'Parent ' . $option['singular_name'],
				'parent_item_colon' => 'Parent ' . $option['singular_name'] . ':',
				'edit_item'         => 'Edit ' . $option['singular_name'],
				'update_item'       => 'Update ' . $option['singular_name'],
				'add_new_item'      => 'Add New ' . $option['singular_name'],
				'new_item_name'     => 'New ' . $option['singular_name'] . ' Name',
				'menu_name'         => $option['singular_name'],
			);

			$this->taxonomies[] = array(
				'hierarchical'      => isset($option['hierarchical']) ? true : false,
				'labels'            => $labels,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => true,
				'rewrite'           => array( 'slug' => $option['taxonomy'] ),
			);

    }
    
  }

  //register the taxonomy
  public function registerCustomTaxonomy(){
    foreach($this->taxonomies as $taxonomy){
      register_taxonomy( $taxonomy['rewrite']['slug'], array('post'), $taxonomy );
    }
  }
}
