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




);


?>
