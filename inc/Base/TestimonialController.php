<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

use \Inc\Base\BaseController;

class TestimonialController extends BaseController
{

  public $settings;

  public $callbacks;

  public $subpages = array();

  public function register(){

    
    if ( ! $this->activated( 'testimonial_manager' ) ) return;

    add_action( 'init', array($this, 'testimonial_cpt') );

    add_action('add_meta_boxes', array($this, 'addMetaBoxes'));

    add_action( 'save_post', array($this, 'saveMetaBox'));
    
  }

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

  public function renderAuthorBox($post){
    wp_nonce_field('mh_testimonial_author', 'mh_testimonial_author_nonce');

    $value = get_post_meta($post->ID, '_mh_testimonial_author_key', true);


    ?> 
    
<label for="mh_testimonial_author">Testimonial Author</label>
<input type="text" id="mh_testimonial_author", name="mh_testimonial_author" calass="widefat" value="<?php echo $value ?>">

<?php
  }

  public function saveMetaBox($post_id)
  {
    if(!isset($_POST['mh_testimonial_author_nonce'])){
      return $post_id;
    }

    $nonce = $_POST['mh_testimonial_author_nonce'];

    if(!wp_verify_nonce( $nonce, 'mh_testimonial_author' )){
      return $post_id;
    }

    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
      return $post_id;
    }

    if(!current_user_can( 'edit_post', $post_id )){
      return $post_id;
    }

    $data = sanitize_text_field( $_POST['mh_testimonial_author'] );
    
    update_post_meta( $post_id, '_mh_testimonial_author_key', $data );

  }
  
}
