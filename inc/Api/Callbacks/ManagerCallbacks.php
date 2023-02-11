<?php

/**
 * @package MhPlugin
 */

namespace Inc\Api\Callbacks;

use Inc\Base\BaseController;

class ManagerCallbacks extends BaseController
{
  public function checkboxSanitize($input)
  {
    


    /**
     * this return is non database optimize
     */
    // use any of the return
    // return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    // return (isset($input) ? true : false);

    /**
     * this code and return system is database optimize
     */
    $output = array();
    foreach($this->managers as $key => $value){
      $output[$key] = isset($input[$key]) ? true : false;
    }

    return $output;
  }

  public function adminSectionManager()
  {
    echo 'Activate the Section and Features of this Plugin by activating the checkboxes';
  }

  public function checkboxField($args)
  {
    // var_dump($args);

    $name = $args['label_for'];
    $class = $args['class'];

    /**
     * this part is non database optimize
     */
    // $checkbox = get_option($name);
    // echo '<div class="' . $class . '"><input type="checkbox" id="' . $name . '" name="' . $name .'" value="1" class=""' . ($checkbox[$name] ? 'checked' : '') . '/><label for="'.$name.'"><div></div></label></div>';

    
    /**
     * this part is database optimize
     */
    $option_name = $args['option_name'];
    $checkbox = get_option($option_name);

    $checked = isset($checkbox[$name]) ? ($checkbox[$name] ? true : false) : false;

    echo '<div class="' . $class . '"><input type="checkbox" id="' . $name . '" name="' . $option_name . '[' . $name .']" value="1" class="" ' . ( $checked ? 'checked' : '') . '/><label for="'.$name.'"><div></div></label></div>';
  }
}
