<?php
//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

global $wpdb; // Must have this or else!

$ingredients = $wpdb->query("SELECT ID FROM " . $wpdb->posts . " WHERE post_type = 'ingredient' ");
if( $ingredients ){
  
  foreach( $ingredients as $ingredient){
    $ids[] = $ingredient;
  }
  
  if( count($ids) > 0 ){
    $wpdb->query("DELETE FROM {$wpdb->posts} WHERE ID IN {$ids} ");
    $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE post_id IN {$ids} ");
  }
  
}


