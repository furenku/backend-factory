<?php

global $cpts;

// $cpts = array();

// for ($i=0; $i < 12; $i++) {

   $cpts = array(

      array(
         'post_type' => 'evento',
         'hierarchical' => 'false',
         'singular' => "Evento",
         'plural' => "Eventos"
      )
      ,
      array(
         'post_type' => 'lugar',
         'hierarchical' => 'false',
         'singular' => "Lugar",
         'plural' => "Lugares",
      )

   );

// }

?>
