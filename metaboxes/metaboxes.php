<?php

global $metaboxes;


$test_fields = array();
$field_types = array( "text", "url", "email", "integer", "float", "date", "time", "textarea", "html", "upload" );

foreach ($field_types as $field_type ) {

   $test_fields[] = array(
      'field_name'            => 'test-metabox-'. $field_type .'-field',
      'field_type'            => ''. $field_type .'',
      'repeatable'            => true,
      'field_label'           => ucfirst( $field_type ) . ' Field',
      'description'           => 'A ' .$field_type .' field.',
   );

}

$metaboxes = array(

   'test-metabox'=>array(

      'post_type'    => 'test-cpt',
      'name'         => 'test-cpt-metabox',
      'title'        => 'Test CPT Metabox',

      'description'  => 'Test all Field Types',

      'fields' => $test_fields
   ),

   'related-metabox'=>array(

      'post_type'    => 'related-post-type',
      'name'         => 'related-post-type-metabox',
      'title'        => 'Related Post Type',

      'description'  => 'Related Post Types',

      'fields' => array(
         array(
            'field_name'            => 'related-post-type-test-cpt',

            'field_type'            => 'related_post',
            'repeatable'            => true,
            'related_post_types'    => array('test-cpt'),

            'field_label'           => 'Related Post Type Posts',
            'description'           => '',
            'markup_function'       => 'standard_metabox_markup'
            ),
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


   'repeatable-field-cpt' => array(

      'post_type'    => 'repeatable-field-cpt',
      'name'         => 'repeatable-test',
      'title'        => 'Repeatable Test',

      'description'  => '',

      'fields' => array(

         array(
            'field_name'            => 'repeatable-field',
            'field_type'            => 'text',
            'repeatable'            => true,
            'field_label'           => 'Repeatable Field',
            'description'           => 'A Repeatable field.',
            'markup_function'       => 'standard_metabox_html'
         ),


      )
   ),




);


?>
