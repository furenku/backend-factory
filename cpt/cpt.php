<?php

global $cpts;

$cpts = array(

   array(
      'post_type' => 'test-cpt',
      'hierarchical' => 'false',
      'singular' => "Test CPT",
      'plural' => "Test CPTs"
   ),

   array(
      'post_type' => 'date-cpt',
      'hierarchical' => 'false',
      'singular' => "Date CPT",
      'plural' => "Date CPTs"
   ),

   array(
      'post_type' => 'repeatable-field-cpt',
      'hierarchical' => 'false',
      'singular' => "Repeatable Field",
      'plural' => "Repeatable Fields"
   ),

   array(
      'post_type' => 'related-post-type',
      'hierarchical' => 'false',
      'singular' => "Post with Related Post Type",
      'plural' => "Posts with Related Post Type"
   ),


);


?>
