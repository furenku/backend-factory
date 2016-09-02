<?php

global $cpts;

// $cpts = array();

// for ($i=0; $i < 12; $i++) {

   $cpts = array(

      array(
         'post_type' => 'persona',
         'hierarchical' => 'false',
         'singular' => "Persona",
         'plural' => "Personas"
      )
      ,
      array(
         'post_type' => 'banda',
         'hierarchical' => 'false',
         'singular' => "Banda",
         'plural' => "Bandas",
      )
      ,
      array(
         'post_type' => 'concierto',
         'hierarchical' => 'false',
         'singular' => "Concierto",
         'plural' => "Conciertos",
      )

   );

// }

?>
