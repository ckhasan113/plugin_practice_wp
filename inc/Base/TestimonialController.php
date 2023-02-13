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
    wp_nonce_field('mh_testimonial', 'mh_testimonial_nonce');

    $data = get_post_meta($post->ID, '_mh_testimonial_author_key', true);

    $name = isset($data['name']) ? $data['name'] : '';
		$email = isset($data['email']) ? $data['email'] : '';
		$approved = isset($data['approved']) ? $data['approved'] : false;
		$featured = isset($data['featured']) ? $data['featured'] : false;


    ?> 
    
      <label class="meta-label" for="mh_testimonial_author">Testimonial Author</label>
      <input type="text" id="mh_testimonial_author", name="mh_testimonial_author" calass="widefat" value="<?php echo $name ?>"/>

      <label class="meta-label" for="mh_testimonial_email">Author Email</label>
      <input type="text" id="mh_testimonial_email", name="mh_testimonial_email" calass="widefat" value="<?php echo esc_attr( $email ) ?>"/>

      <div class="meta-container">
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


  public function saveMetaBox($post_id)
  {
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
  
}
