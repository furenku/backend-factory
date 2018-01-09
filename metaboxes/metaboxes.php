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


   'translated-cpt-test-metabox-no-translation' => array(

      'post_type'    => 'translated-cpt',
      'name'         => 'translated-cpt-metabox',
      'title'        => 'Translated CPT Metabox',

      'description'  => 'Fill in Translated',
      // 'translations'          => array('es','en'),

      'fields' => array(


         array(
            'field_name'            => 'test-cpt-not-translated-group3',
            'field_type'            => 'field_group',
            'repeatable'            => true,
            'field_label'           => 'Group Field, no translation',
            'description'           => 'A group field, no translation.',
            'markup_function'       => 'standard_metabox_html',

          'field_group'           => array(
            'notrans_fg1' => array(
               'field_name'            => 'notrans_fg1',
               'field_type'            => 'text',
               'field_label'           => 'f1'
            ),
            'notrans_fg2' => array(
               'field_name'            => 'notrans_fg2',
               'field_type'            => 'text',
               'field_label'           => 'f2'
            ),
            'notrans_fg3' => array(
               'field_name'            => 'notrans_fg3',
               'field_type'            => 'text',
               'field_label'           => 'f3'
            ),

          )
       ),
         array(
            'field_name'            => 'test-cpt-not-translated-group4',
            'field_type'            => 'field_group',
            'repeatable'            => false,
            'field_label'           => 'Group Field, no translation',
            'description'           => 'A group field, no translation.',
            'markup_function'       => 'standard_metabox_html',

          'field_group'           => array(
            'notrans_fg1' => array(
               'field_name'            => 'notrans_fg1',
               'field_type'            => 'text',
               'field_label'           => 'f21'
            ),
            'notrans_fg2' => array(
               'field_name'            => 'notrans_fg2',
               'field_type'            => 'text',
               'field_label'           => 'f22'
            )

          )
       ),


         array(
            'field_name'            => 'test-cpt-not-translated-testtextnr',
            'field_type'            => 'text',
            'repeatable'            => false,
            'field_label'           => 'tst nr',
            'description'           => 'tst',
            'markup_function'       => 'standard_metabox_html',
          ),


         array(
            'field_name'            => 'test-cpt-not-translated-testtext',
            'field_type'            => 'text',
            'repeatable'            => true,
            'field_label'           => 'tst r',
            'description'           => 'tst',
            'markup_function'       => 'standard_metabox_html',
          ),



      )
   ),


   'translated-cpt-test-metabox-translation' => array(

      'post_type'    => 'translated-cpt',
      'name'         => 'translated-cpt-metabox',
      'title'        => 'Translated CPT Metabox',

      'description'  => 'Fill in Translated',
      'translations'          => array('es','en'),

      'fields' => array(
        //
        // array(
        //    'field_name'            => 'test-cpt-translated',
        //    'field_type'            => 'text',
        //    'repeatable'            => false,
        //    'field_label'           => 'Translated Field',
        //    'description'           => 'A translated field.',
        //    'markup_function'       => 'standard_metabox_html',
        //    'translations'          => array(
        //      'en' => array(
        //        'field_label'           => 'Translated Field',
        //        'description'           => 'A translated field.',
        //      ),
        //      'es' => array(
        //        'field_label'           => 'Campo Traducido',
        //        'description'           => 'Un campo traducido.',
        //      )
        //    )
        // ),
        //
        //
        // array(
        //    'field_name'            => 'test-cpt-translated-repeatable',
        //    'field_type'            => 'text',
        //    'repeatable'            => true,
        //    'field_label'           => 'Translated repeatable Field',
        //    'description'           => 'A translated repeatable field.',
        //    'markup_function'       => 'standard_metabox_html',
        //    'translations'          => array(
        //      'en' => array(
        //        'field_label'           => 'Translated Field r',
        //        'description'           => 'A translated field r.',
        //      ),
        //      'es' => array(
        //        'field_label'           => 'Campo Traducido r',
        //        'description'           => 'Un campo traducido r.',
        //      )
        //    )
        // ),
        //
        //
        //   array(
        //      'field_name'            => 'test-cpt-translated-group',
        //      'field_type'            => 'field_group',
        //      'repeatable'            => false,
        //      'field_label'           => 'Translated group Field',
        //      'description'           => 'A translated group field.',
        //      'markup_function'       => 'standard_metabox_html',
        //      'translations'          => array( 'en' => array(
        //        'field_label'           => 'Translated field group',
        //      ),
        //      'es' => array(
        //        'field_label'           => 'Group de campos traducido ',
        //      )
        //    ),
        //
        //    'field_group'           => array(
        //      'trans_fg1' => array(
        //         'field_name'            => 'trans_fg1',
        //         'field_type'            => 'text',
        //         'translations'          => array(
        //           'en' => array(
        //             'field_label'           => 'translated field group field 1',
        //           ),
        //           'es' => array(
        //             'field_label'           => 'traducido field group field 1',
        //           )
        //         )
        //      ),
        //      'trans_fg2' => array(
        //         'field_name'            => 'trans_fg2',
        //         'field_type'            => 'text',
        //         'translations'          => array(
        //           'en' => array(
        //             'field_label'           => 'translated field group field 2',
        //           ),
        //           'es' => array(
        //             'field_label'           => 'traducido field group field 2',
        //           )
        //         )
        //      ),
        //      'trans_fg3' => array(
        //         'field_name'            => 'trans_fg3',
        //         'field_type'            => 'text',
        //         'translations'          => array(
        //           'en' => array(
        //             'field_label'           => 'translated field group field 3',
        //           ),
        //           'es' => array(
        //             'field_label'           => 'traducido field group field 3',
        //           )
        //         )
        //      ),
        //
        //    )
        // ),


        array(
             'field_name'            => 'test-cpt-translated-group_r',
             'field_type'            => 'field_group',
             'repeatable'            => true,
             'field_label'           => 'Translated group Field repeatable',
             'description'           => 'A translated group field repeatable.',
             'markup_function'       => 'standard_metabox_html',
             'translations'          => array(
               'en' => array(
                 'field_label'           => 'Translated field group repeatable',
               ),
               'es' => array(
                 'field_label'           => 'Group de campos traducido repetible  ',
               )
             ),

           'field_group'           => array(
             'trans_fg1' => array(
                'field_name'            => 'trans_fg1',
                'field_type'            => 'text',
                'field_label'            => 'text 0',
                'translations'          => array(
                  'en' => array(
                    'field_label'           => 'translated field group field 1',
                  ),
                  'es' => array(
                    'field_label'           => 'traducido field group field 1',
                  )
                )
             ),
             'trans_fg2' => array(
                'field_name'            => 'trans_fg2',
                'field_type'            => 'text',
                'field_label'            => 'text 1',
                'translations'          => array(
                  'en' => array(
                    'field_label'           => 'translated field group field 2',
                  ),
                  'es' => array(
                    'field_label'           => 'traducido field group field 2',
                  )
                )
             ),
             'trans_fg3' => array(
                'field_name'            => 'trans_fg3',
                'field_type'            => 'text',
                'field_label'            => 'text 2',
                'translations'          => array(
                  'en' => array(
                    'field_label'           => 'translated field group field 3',
                  ),
                  'es' => array(
                    'field_label'           => 'traducido field group field 3',
                  )
                )
             ),

           )
        ),

      )
   ),






   //
   // 'translated-cpt-test-metabox-no-translation' => array(
   //
   //    'post_type'    => 'translated-cpt',
   //    'name'         => 'not-translated-cpt-metabox',
   //    'title'        => 'Not Translated CPT Metabox',
   //
   //    'description'  => 'Fill in Fields',
   //    // 'translations'          => array('es','en'),
   //
   //    'fields' => array(
   //
   //      array(
   //           'field_name'            => 'test-cpt-not-translated-group_r',
   //           'field_type'            => 'field_group',
   //           'repeatable'            => true,
   //           'field_label'           => 'not Translated group Field repeatable',
   //           'description'           => 'A not translated group field repeatable.',
   //           'markup_function'       => 'standard_metabox_html',
   //
   //           'field_group'           => array(
   //             'trans_fg1' => array(
   //                'field_name'            => 'trans_fg1',
   //                'field_type'            => 'text',
   //                'field_label'           => 'text 0'
   //             ),
   //             'trans_fg2' => array(
   //                'field_name'            => 'trans_fg2',
   //                'field_type'            => 'text',
   //                'field_label'           => 'text 1'
   //             ),
   //             'trans_fg3' => array(
   //                'field_name'            => 'trans_fg3',
   //                'field_type'            => 'text',
   //                'field_label'           => 'text 2',
   //             ),
   //
   //         )
   //      ),
   //
   //    )
   // ),






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
