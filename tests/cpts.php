<?php

global $cpts;

$cpts = array();

for ($i=0; $i < 12; $i++) {

   array_push(
      $cpts,
      array(
         'post_type' => 'test_item_' . $i,
         'hierarchical' => 'true',
         'singular' => "Test Element " . $i,
         'plural' => "Test Elements " . $i
      )
   );

}

?>
