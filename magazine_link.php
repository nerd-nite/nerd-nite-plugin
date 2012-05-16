<?php
/*
Plugin Name: Magazine Link
*/

add_filter('the_content','nerdnite_magazine_link');


function nerdnite_magazine_link($content) {
    global $wpdb, $blog_id, $pagename;
    
   #print_r($query);
  if ($pagename == 'magazine' and $blog_id != 1) {
      $content = "Hello";
      #print_r($query);
    #switch_to_blog(1);
    #$other_pages = get_pages();
    #print_r($other_pages);
    #restore_current_blog();
    #print_r($arg_list);
    }
    return $content;
}