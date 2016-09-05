<?php

global $metaboxes;


$fields = array();

for ($i=1; $i <= 10; $i++) {
   $field = array(
      'field_name'            => 'listado-artista-'.$i,
      'field_type'            => 'text',
      'repeatable'            => false,
      'field_label'           => 'Artista '.$i,
      'markup_function'       => 'standard_metabox_html'
   );

   array_push($fields, $field);


   $field = array(
      'field_name'            => 'listado-cancion-'.$i,
      'field_type'            => 'text',
      'repeatable'            => false,
      'field_label'           => 'Canción '.$i,
      'markup_function'       => 'standard_metabox_html'
   );

   array_push($fields, $field);

}

$metaboxes = array(

   'top10-listado'=>array(

      'post_type'    => 'top-10',
      'name'         => 'top10-listado',
      'title'        => 'Listado',

      'description'  => 'Selecciona los artistas y canciones',

      'fields' => $fields
   ),




);


?>
