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
      'post_type' => 'another-cpt',
      'hierarchical' => 'false',
      'singular' => "Another CPT",
      'plural' => "Another CPTs"
   )

);


?>
