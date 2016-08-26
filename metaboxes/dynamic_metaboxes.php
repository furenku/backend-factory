<?php

global $metaboxes;

$metaboxes = array(

   'persona-banda'=>array(

      'post_type'    => 'persona',
      'name'         => 'persona-banda',
      'title'        => 'Bandas',

      'description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis at quos fugit!',

      'fields' => array(
         array(
            'field_name'            => 'persona-banda',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('banda'),

            'field_label'           => 'Bandas a las que pertenece',
            'description'           => '
            <p class="fontXXS" style="font-size:10px">
               Lorem ipsum dolor sit amet, consectetur.
            </p>
            <p class="fontXXS" style="font-size:10px">
               Lorem ipsum dolor sit amet, consectetur adipisicing.
            </p>
            ',
            'markup_function'       => 'standard_metabox_markup'
         ),

      )

   ),

   'banda'=>array(

      'post_type'    => 'banda',
      'name'         => 'banda-persona',
      'title'        => 'Miembros de la Banda',

      'description'  => 'Lorem ipsum dolor sit amet, consectetur adipisicing.',

      'fields' => array(
         array(
            'field_name'            => 'banda-persona',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('banda'),

            'field_label'           => 'Miembros de la Banda',
            'description'           => '
            <p class="fontXXS" style="font-size:10px">
            ...
            </p>
            <p class="fontXXS" style="font-size:10px">
            ...
            </p>
            ',
            'markup_function'       => 'standard_metabox_markup'
         ),

      )

   )

);

include 'metabox_markup.php';
include_once 'dynamic_metaboxes_save.php';
