<?php

/**
 * @package MhPlugin
 */

namespace Inc\Api\Callbacks;


class CptCallbacks
{
  
  public function cptSectionManager()
  {
    echo 'Create as many Custom Post Type as yo want.';
  }

  public function cptSanitize($input){

    $output = get_option('mh_admin_cpt') ?: array();

    // var_dump($_POST); //$_POST have the all value of a form which method is post
    // die();

    /**
     * Delete CPT in this if block
     */
    if(isset($_POST['remove'])){
      
      unset($output[$_POST['remove']]);

      return $output;
    }

    // if(empty($output)){
    //   $new_input = array($input['post_type'] => $input);
    //   $output = $new_input;
    // }    
    // else{
    //   foreach($output as $key => $value){    
    //     if($input['post_type'] === $key){
    //       $output[$key] = $input;
    //     }else{
    //       $output[$input['post_type']] = $input;
    //     }
    //   }
    // }
    

    if(count($output) == 0){ 
      $output[$input['post_type']] = $input;
      return $output;
    }

    foreach($output as $key => $value){      
      if($input['post_type'] === $key){
        $output[$key] = $input;
      }else{
        $output[$input['post_type']] = $input;
      }
    }
    return $output;
  }

  public function textField($args){
    $name = $args['label_for'];
    $option_name = $args['option_name'];
    $value = '';
    $readonly = '';

    if(isset($_POST['edit_post'])){
      $input = get_option( $option_name );
      $value = $input[ $_POST['edit_post']][$name];
      $readonly =  ($name == 'post_type') ? 'readonly' : '';
    }

    echo '<input type="text" class="regular-text" id="' . $name . '" name="' . $option_name . '[' . $name .']" value="'.$value.'" placeholder="'. $args['placeholder'] .'" required '. $readonly .'/>';
  }

  public function checkboxField($args)
  {
    $name = $args['label_for'];
    $class = $args['class'];
    $option_name = $args['option_name'];
    $checked = false;
    
    if(isset($_POST['edit_post'])){
      $checkbox = get_option( $option_name );
      $checked = isset($checkbox[$_POST['edit_post']][$name]) ?: false;
    }

    echo '<div class="' . $class . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name .']" value="1" class="" ' . ( $checked ? 'checked' : '') . '/><label for="'.$name.'"><div></div></label></div>';
  }

}
