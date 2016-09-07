<?php

global $metaboxes;




$metaboxes = array(

   'test-metabox'=>array(

      'post_type'    => 'test-cpt',
      'name'         => 'test-cpt-metabox',
      'title'        => 'Test CPT Metabox',

      'description'  => 'Fill in Custom Fields',

      'fields' => array(

         array(
            'field_name'            => 'test-metabox-field-1',
            'field_type'            => 'text',
            'repeatable'            => false,
            'field_label'           => 'Test Field 1',
            'description'           => 'A custom field.',
            'markup_function'       => 'standard_metabox_html'
         ),
         array(
            'field_name'            => 'test-metabox-field-2',
            'field_type'            => 'text',
            'repeatable'            => false,
            'field_label'           => 'Test Field 2',
            'description'           => 'A custom field.',
            'markup_function'       => 'standard_metabox_html'
         )

      )
   ),

   'date-cpt-date-metabox'=>array(

      'post_type'    => 'date-cpt',
      'name'         => 'date-cpt-metabox',
      'title'        => 'Date CPT Metabox',

      'description'  => 'Fill in Date',

      'fields' => array(

         array(
            'field_name'            => 'test-cpt-date',
            'field_type'            => 'date',
            'repeatable'            => false,
            'field_label'           => 'Date Field',
            'description'           => 'A date field.',
            'markup_function'       => 'standard_metabox_html'
         ),
         

      )
   ),




);


?>
