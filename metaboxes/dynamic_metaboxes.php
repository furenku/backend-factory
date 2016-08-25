<?php

global $metaboxes;
$metaboxes = array(
   'editorial'=>array(

      'post_type'    => 'editorial',
      'name'         => 'editorial-persona',
      'title'        => 'Editorial',
      'description'  => '

      ',

      'fields' => array(
         array(
            'field_name'            => 'editorial-persona',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('persona'),

            'field_label'           => 'Miembros de la Editorial',
            'description'           => '
            <p class="fontXXS" style="font-size:10px">
            Elige los miembros pertenecientes a la Organización.
            </p>
            <p class="fontXXS" style="font-size:10px">
            (Deben ser creados previamente)
            </p>
            ',
            'markup_function'       => 'standard_metabox_markup'
         ),

      )

   ),

   'persona'=>array(

      'post_type'    => 'persona',
      'name'         => 'persona-editorial',
      'title'        => 'Editoriales!',

      'description'  => '...',

      'fields' => array(
         array(
            'field_name'            => 'persona-editorial',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('editorial'),

            'field_label'           => 'Editoriales a las que pertenece',
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

   ),

   'product'=>array(

      'post_type'    => 'product',
      'name'         => 'product-editoriales',
      'title'        => 'Información Producto',
      'description'  => '

      ',

      'fields' => array(
         array(
            'field_name'            => 'product-editorial',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('editorial'),

            'field_label'           => 'Editorial',
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


         array(
            'field_name'            => 'product-persona',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('persona'),

            'field_label'           => 'Personas',
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

   ),



      'proyecto'=>array(

         'post_type'    => 'proyecto',
         'name'         => 'proyecto-editoriales',
         'title'        => 'Información proyectoo',
         'description'  => '

         ',

         'fields' => array(
            array(
               'field_name'            => 'proyecto-editorial',

               'field_type'            => 'related_post',
               'repeatable'            => true,
               'related_post_types'    => array('editorial'),

               'field_label'           => 'Editorial',
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


            array(
               'field_name'            => 'proyecto-persona',

               'field_type'            => 'related_post',
               'repeatable'            => true,
               'related_post_types'    => array('persona'),

               'field_label'           => 'Personas',
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

            array(
               'field_name'            => 'proyecto-product',

               'field_type'            => 'related_post',
               'repeatable'            => true,
               'related_post_types'    => array('product'),

               'field_label'           => 'Productos',
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

      ),

      'taller'=>array(

         'post_type'    => 'taller',
         'name'         => 'taller-info',
         'title'        => 'Información',
         'description'  => '

         ',

         'fields' => array(
            array(
               'field_name'            => 'fechas',

               'field_type'            => 'datebooking',
               'repeatable'            => true,

               'field_label'           => 'Fecha(s) de Taller',
               'description'           => '
               <p class="fontXXS" style="font-size:10px">
               Introduce las fechas de tu taller.
               Puedes agendar varios periodos del mismo taller.
               </p>
               <p class="fontXXS" style="font-size:10px">
               (Deben ser creados previamente)
               </p>
               ',
               'markup_function'       => 'standard_metabox_markup'
            ),
            array(
               'field_name'            => 'taller-persona',

               'field_type'            => 'related_post',
               'repeatable'            => true,
               'related_post_types'    => array('persona'),

               'field_label'           => 'Talleristas',
               'description'           => '
               <p class="fontXXS" style="font-size:10px">
               Elige los talleristas del taller.
               </p>
               <p class="fontXXS" style="font-size:10px">
               (Deben ser creados previamente)
               </p>
               ',
               'markup_function'       => 'datebooking_metabox_markup'
            ),

         )

      ),

);


include_once 'dynamic_metaboxes_save.php';
