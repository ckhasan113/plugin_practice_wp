<?php

/**
 * @package MhPlugin
 */

/* 
* Toigger this file on Plugin uninstall
* This file is need to clear the data stored in the database for this plugin
*/

// Security check - If the uninstall tigger by accidentely
if (!defined('WP_UNINSTALL_PLUGIN')) {
  die;
}

/* Clear Database stored data */
// WordPress buildin method
// $books = get_posts(array('post_type' => 'book', 'numberposts' => -1)); 
// if numberposts value is -1 then all post is in the list is selected

/**
 In foreach loop ===
 * If the second peramiter is false then if the post is in trush it will not deleted 
 * Otherwise if it is true then every post whether it is in trush, or draft, or publish it will delete 
 * */
// foreach ($books as $book) {
//   wp_delete_post($book->ID, true);
// }


// Delete using SQL, this is very fastest way to delete
//Access the database via SQL
global $wpdb;
$wpdb->query("DELETE FROM plugin_posts WHERE post_type = 'book'");
$wpdb->query("DELETE FROM plugin_postmeta WHERE post_id NOT IN (SELECT id FROM plugin_posts)");
$wpdb->query("DELETE FROM plugin_term_relationships WHERE object_id NOT IN (SELECT id FROM plugin_posts)");
