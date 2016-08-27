<?php

global $metaboxes;

$metaboxes = array(

   'evento-informacion'=>array(

      'post_type'    => 'evento',
      'name'         => 'evento-informacion',
      'title'        => 'Lugar',

      'description'  => 'Información del Evento',

      'fields' => array(
         array(
            'field_name'            => 'evento-lugar',

            'field_type'            => 'related_post',
            'repeatable'            => false,
            'related_post_types'    => array('lugar'),

            'field_label'           => 'Lugar del Evento',
            'description'           => '
               <p class="fontXXS" style="font-size:10px">
                  Escoge el lugar del evento.
               </p>
               <p class="fontXXS" style="font-size:10px">
                  (Debe ser creado previamente)
               </p>
            ',
            'markup_function'       => 'standard_metabox_markup'
         ),
         array(
            'field_name'            => 'date' . '-' . '0',

            'field_type'            => 'date',
            'repeatable'            => false,

            'field_label'           => 'Fecha del Evento',
            'description'           => '
            <p class="fontXXS" style="font-size:10px">
               Escoge la fecha y hora del evento.
            </p>
            ',
            'markup_function'       => 'standard_metabox_markup'
         ),

      )

   ),




);


?>
