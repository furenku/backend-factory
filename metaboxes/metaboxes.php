<?php

global $metaboxes;


$test_fields = array();
$field_types = array( "text", "url", "email", "integer", "float", "date", "time", "textarea", "html", "upload" );
//
// foreach ($field_types as $field_type ) {
//
//    $test_fields[] = array(
//       'field_name'            => 'test-metabox-'. $field_type .'-field',
//       'field_type'            => ''. $field_type .'',
//       'repeatable'            => $field_type != "date" ?  true : false,
//       'field_label'           => ucfirst( $field_type ) . ' Field',
//       'description'           => 'A ' .$field_type .' field.',
//    );
//
// }


$test_fields[] = array(
   'field_name'            => 'test-cpt-rpt',

   'field_type'            => 'related_post',
   'repeatable'            => true,
   'related_post_types'    => array('rpt'),

   'field_label'           => 'Related Post Type Posts',
   'description'           => '',
   'markup_function'       => 'standard_metabox_markup'
   );

$metaboxes = array(

   'test-metabox'=>array(

      'post_type'    => 'test-cpt',
      'name'         => 'test-cpt-metabox',
      'title'        => 'Test CPT Metabox',

      'description'  => 'Test all Field Types',

      'fields' => $test_fields
   ),

   'related-metabox'=>array(

      'post_type'    => 'rpt',
      'name'         => 'rpt-metabox',
      'title'        => 'Related Post Type',

      'description'  => 'Related Post Types',

      'fields' => array(
         array(
            'field_name'            => 'rpt-test-cpt',

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


   'translated-cpt-test-text'=>array(

      'post_type'    => 'translated-cpt',
      'name'         => 'translated-cpt-metabox',
      'title'        => 'Translated CPT Metabox',

      'description'  => 'Fill in Translated',
      'translations'          => array('es','en'),

      'fields' => array(

         array(
            'field_name'            => 'test-cpt-translated',
            'field_type'            => 'text',
            'repeatable'            => false,
            'field_label'           => 'Translated Field',
            'description'           => 'A translated field.',
            'markup_function'       => 'standard_metabox_html',
            'translations'          => array(
              'en' => array(
                'field_label'           => 'Translated Field',
                'description'           => 'A translated field.',
              ),
              'es' => array(
                'field_label'           => 'Campo Traducido',
                'description'           => 'Un campo traducido.',
              )
            )
         ),
         array(
            'field_name'            => 'test-cpt-translated-repeatable',
            'field_type'            => 'text',
            'repeatable'            => true,
            'field_label'           => 'Translated repeatable Field',
            'description'           => 'A translated repeatable field.',
            'markup_function'       => 'standard_metabox_html',
            'translations'          => array(
              'en' => array(
                'field_label'           => 'Translated Field r',
                'description'           => 'A translated field r.',
              ),
              'es' => array(
                'field_label'           => 'Campo Traducido r',
                'description'           => 'Un campo traducido r.',
              )
            )
         ),
         array(
            'field_name'            => 'test-cpt-not-translated',
            'field_type'            => 'text',
            'repeatable'            => false,
            'field_label'           => 'Field without Translation',
            'description'           => 'Field without Translation',
            'markup_function'       => 'standard_metabox_html'
         ),
         array(
            'field_name'            => 'test-cpt-not-translated-r',
            'field_type'            => 'text',
            'repeatable'            => true,
            'field_label'           => 'Field without Translation r',
            'description'           => 'Field without Translation r',
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
