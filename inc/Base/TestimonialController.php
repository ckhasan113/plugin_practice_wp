<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use Inc\Api\SettingsApi; 
use \Inc\Base\BaseController;
use Inc\Api\Callbacks\TestimonialCallbacks;

class TestimonialController extends BaseController
{

  public $settings;
  public $test_callbacks;

  public function register(){

    if ( ! $this->activated( 'testimonial_manager' ) ) return;

    $this->settings = new SettingsApi();
    $this->test_callbacks = new TestimonialCallbacks();

    add_action( 'init', array($this, 'testimonial_cpt') );
    add_action('add_meta_boxes', array($this, 'addMetaBoxes'));
    add_action( 'save_post', array($this, 'saveMetaBox'));

    // add_action( 'manage_{post_type}_posts_columns')
    add_action( 'manage_testimonial_posts_columns', array($this, 'setCustomColumns'));
    add_action( 'manage_testimonial_posts_custom_column', array($this, 'setCustomColumnsData'), 10, 2);
    /**
     * 10 - is the ordering number for this custom method. 10 is default value
     * 2 - means this method have 2 arguments
     */

     add_filter( 'manage_edit-testimonial_sortable_columns', array($this, 'setCustomColumnsSortable'));


     $this->setShortcodePage();
    
  }

  public function setShortcodePage(){
    $subpage = array(
      array(
        'parent_slug' => 'edit.php?post_type=testimonial',
        'page_title' => 'Shortcodes',
        'menu_title' => 'Shortcodes',
        'capability' => 'manage_options',
        'menu_slug' => 'mh_testimonial',
        'callback' => array($this->test_callbacks, 'shortcodePage'),
      ),
    );

    $this->settings->addSubPages($subpage)->register();
  }

  /**
   * This function create "Testimonial" section in menu
   *
   * @return void
   */
  public function testimonial_cpt(){

    $labels = array(
      'name' => 'Testimonials',
      'singular_name' => 'Testimonial',
    );

    $args = array(
      'labels' => $labels,
      'public' => true,
      'has_archive' => false,
      'menu_icon' => 'dashicons-testimonial',
      'exclude_from_search' => true,
      'publicly_queryable' => false,
      'supports' => array('title', 'editor'),
    );

    register_post_type( 'testimonial', $args );
  }

  /**
   * this function add meta box in the admin page
   *
   * @return void
   */
  public function addMetaBoxes(){
    add_meta_box(
      'testimonial_author',
      'Author',
      array($this, 'renderAuthorBox'),
      'testimonial',
      'side',
      'default'
    );
  }

  /**
   * This method is to design the meta box and show the value
   *
   * @param [WP_Post object] $post
   * @return void
   */
  public function renderAuthorBox($post){

    wp_nonce_field('mh_testimonial', 'mh_testimonial_nonce');

    $data = get_post_meta($post->ID, '_mh_testimonial_author_key', true);

    $name = isset($data['name']) ? $data['name'] : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$approved = isset($data['approved']) ? $data['approved'] : false;
		$featured = isset($data['featured']) ? $data['featured'] : false;

    ?> 
    
      <label class="meta-label" for="mh_testimonial_author">Testimonial Author</label>
      <input type="text" id="mh_testimonial_author", name="mh_testimonial_author" calass="widefat" value="<?php echo $name ?>"/>

      <label class="meta-label mt-10" for="mh_testimonial_email">Author Email</label>
      <input type="text" id="mh_testimonial_email", name="mh_testimonial_email" calass="widefat" value="<?php echo esc_attr( $email ) ?>"/>

      <div class="meta-container mt-10">
          <label class="meta-label w-50 text-left" for="mh_testimonial_approved">Approved</label>
          <div class="text-right w-50 inline">
            <div class="ui-toggle inline"><input type="checkbox" id="mh_testimonial_approved" name="mh_testimonial_approved" value="1" <?php echo $approved ? 'checked' : ''; ?>>
              <label for="mh_testimonial_approved"><div></div></label>
            </div>
          </div>
        </div>

        <div class="meta-container">
          <label class="meta-label w-50 text-left" for="mh_testimonial_featured">Featured</label>
          <div class="text-right w-50 inline">
            <div class="ui-toggle inline"><input type="checkbox" id="mh_testimonial_featured" name="mh_testimonial_featured" value="1" <?php echo $featured ? 'checked' : ''; ?>>
              <label for="mh_testimonial_featured"><div></div></label>
            </div>
          </div>
        </div>

    <?php
  }


  /**
   * This function store the meta box data
   *
   * @param [int] $post_id
   * @return void
   */
  public function saveMetaBox($post_id){

    if(!isset($_POST['mh_testimonial_nonce'])){
      return $post_id;
    }

    $nonce = $_POST['mh_testimonial_nonce'];

    if(!wp_verify_nonce( $nonce, 'mh_testimonial' )){
      return $post_id;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
      return $post_id;
    }

    if(!current_user_can( 'edit_post', $post_id )){
      return $post_id;
    }

    // $data = sanitize_text_field( $_POST['mh_testimonial_author'] );
    $data = array(
			'name' => sanitize_text_field( $_POST['mh_testimonial_author'] ),
			'email' => sanitize_text_field( $_POST['mh_testimonial_email'] ),
			'approved' => isset($_POST['mh_testimonial_approved']) ? 1 : 0,
			'featured' => isset($_POST['mh_testimonial_featured']) ? 1 : 0,
		);
    
    update_post_meta( $post_id, '_mh_testimonial_author_key', $data );

  }


  /**
   * this method use for creating custom columns
   *
   * @param [array] $columns
   * @return $columns
   */
  public function setCustomColumns($columns){

    // store the default filed value
    $title = $columns['title'];
    $date = $columns['date'];

    // remove the default columns
    unset($columns['title'], $columns['date']);

    // create the custom columns one after another in which oder we want to see
    $columns['name'] = 'Author Name';
    $columns['title'] = $title;
    $columns['approved'] = 'Approved';
    $columns['featured'] = 'Featured';
    $columns['date'] = $date;

    return $columns;

  }

  /**
   * this method set the value of the custom columns
   *
   * @param [array] $column
   * @param [int] $post_id
   * @return void
   */
  public function setCustomColumnsData($column, $post_id)
  {
    $data = get_post_meta($post_id, '_mh_testimonial_author_key', true);

    $name = isset($data['name']) ? $data['name'] : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$approved = isset($data['approved']) && $data['approved'] === 1 ? '<strong>YES</strong>' : '<strong>NO</strong>';
		$featured = isset($data['featured']) && $data['featured'] === 1 ? '<strong>YES</strong>' : '<strong>NO</strong>';

    switch($column){
      case 'name' :
        echo '<strong>'.$name.'</strong><br/><a href="mailto:"'.$email.'">'.$email.'</a>';
        break;

      case 'approved' :
        echo $approved;
        break;

      case 'featured' :
        echo $featured;
        break;
    }

  }

  /**
   * this function set the columns in testimonial list sortable
   *
   * @param [array] $columns
   * @return $columns
   */
  public function setCustomColumnsSortable($columns){
    
    $columns['name'] = 'name';
    $columns['approved'] ='approved';
    $columns['featured'] ='featured';

    return $columns;
  }
  
}
