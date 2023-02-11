<?php

/**
 * @package MhPlugin
 */

namespace Inc\Base;

class Activate
{
  public static function activate()
  {
    flush_rewrite_rules();

    $default = array();

    if(!get_option('mh_admin')){
      update_option('mh_admin', $default);
    }

    if(!get_option('mh_admin_cpt')){
      update_option('mh_admin_cpt', $default);
    }

    if(!get_option('mh_admin_tax')){
      update_option('mh_admin_tax', $default);
    }
    
  }
}
