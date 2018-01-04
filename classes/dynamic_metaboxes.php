<?php

include_once 'template-standard_metabox.php';

class DynamicMetaboxes {

   var $metaboxes;
   var $field_creator;

   var $backendFactory;

   public function __construct( $backendFactory_ ) {

      $this->backendFactory = $backendFactory_;

      $this->metaboxes = array();

      add_action("add_meta_boxes", array( $this, 'register_metaboxes' ) );
      add_action("save_post", array( $this, 'save_metaboxes' ) );

      $this->field_creator = new MetaboxFieldCreator();


   }
   public function add_metabox( $metabox = NULL ) {
      if( $metabox ) {
         array_push( $this -> metaboxes, $metabox );
      }
   }


   public function register_metaboxes()
   {
      foreach($this -> metaboxes as $metabox) :
         add_meta_box(
         $metabox['post_type']."-meta-box",
         $metabox['title'],
         array($this,"standard_metabox_html"),
         $metabox['post_type'],
         "normal",
         "high",
         array('metabox'=>$metabox )
      );
   endforeach;



}

public function save_metaboxes($post_id=0, $post=0, $update=0)
{

    $currentTranslation = $this->backendFactory->currentTranslation;


   // ob_start();
   // var_dump($_POST);
   // $vardump = ob_get_clean();
   // ob_end_clean();
   // $errors .= $vardump;
   // $errors .= "<br>";

   if(!current_user_can("edit_post", $post_id))
   return $post_id;


   if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
   return $post_id;
   if( is_array( $this->metaboxes ) );

   foreach( $this->metaboxes as $metabox ) {



      if( $metabox['post_type'] == get_post($post_id)->post_type ) :
         if( ! isset($_POST[ $metabox['name']."-metabox-nonce" ]) || ! wp_verify_nonce($_POST[ $metabox['name']."-metabox-nonce" ], basename(__FILE__)))
         return $post_id;


         foreach($metabox['fields'] as $field) :

           $field_name = $field['field_name'];


            if(isset($_POST[ $field_name ]))
            {
                $field_translated_values = array();

                if( is_array($field['translations']) ) :
                  foreach( $field['translations'] as $translationKey => $translation ) :
                    $field_translated_values[$translationKey] = $_POST[ $field_name . "_" . $translationKey ];
                  endforeach;
                endif;

               $field_value = $_POST[ $field_name ];

               $field_type = $field['field_type'];


               // update_post_meta($post_id, "test", $field_name );
               // update_post_meta($post_id, "date" , 123 );

               if( $field_type == "datebooking" ) {

                  $dates = array();

                  if(isset($_POST[ 'start_date'])) {
                     $dates[ 'start_date' ] = $_POST[ 'start_date'];
                  }

                  if(isset($_POST[ 'end_date'])) {
                     $dates[ 'end_date' ] = $_POST[ 'end_date'];
                  }

                  if(isset($_POST[ 'schedule'])) {
                     $dates[ 'schedule' ] = $_POST[ 'schedule'];
                  }

                  if(isset($_POST[ 'place'])) {
                     $dates[ 'place' ] = $_POST[ 'place'];
                  }

                  if(isset($_POST[ 'start_date'])) {
                     $dates[ 'start_date' ] = $_POST[ 'start_date'];
                  }

                  update_post_meta(
                  $post_id,
                  'dates',
                  $dates
               );

            } elseif( $field_type == "related_post" ) {

               $related_post_ids = $field_value;

               $related_post_type = $field['related_post_types'];
               $related_post_type = $related_post_type[0];

               // check previous value to see if any posts were deleted

               $old_related_posts = get_post_meta( $post_id, $field_name, true );
               $exclude_this_post_in_posts = array();

               $related_post_field_name = $related_post_type . '-' . $metabox['post_type'];

               foreach ($old_related_posts as $old_related_post ) {

                  if( $old_related_post && $old_related_post != "" && ! in_array( $old_related_post, $related_post_ids ) ) {

                     $related_post_posts = get_post_meta( $old_related_post, $related_post_field_name, true );

                     if(is_array( $related_post_posts )) {

                        if (($key = array_search( $post_id, $related_post_posts )) !== false) {
                           unset($related_post_posts[$key]);
                        }

                        update_post_meta(
                        $old_related_post,
                        $related_post_type . '-' . $metabox['post_type'],
                        array_unique($related_post_posts) );

                     }

                  }


               }

               if( is_array($related_post_ids) ) {

                  foreach( $related_post_ids as $related_post_id ) {


                     $related_post_type = $field['related_post_types'];
                     $related_post_type = $related_post_type[0];

                     $field_name =  $metabox['post_type'] . '-' . $related_post_type;

                     $field_value = $_POST[ $field_name ];

                     // check if related post has array of related posts
                     $posts = get_post_meta(
                     $related_post_id,
                     $related_post_type . '-' . $metabox['post_type'],
                     true );

                     if( is_array($posts) ) {
                        // if post has array, add this post to it (if its not already there)
                        if( ! in_array($post_id,$post_id))
                        array_push($posts, $post_id);
                     } else {
                        // otherwise create array in it, and associate this post
                        $posts = array( $post_id );
                     }


                     update_post_meta(
                     $related_post_id,
                     $related_post_type . '-' . $metabox['post_type'],
                     array_unique($posts) );

                  }
               }

            } elseif( $field_type == "date" ) {

               // $field_value = date('Y-m-d h:i:s', strtotime( $field_value ) );
               $field_value = date('Y-m-d', strtotime( $field_value ) );

            }
            elseif( $field_type == "html" ) {

               // $field_value = date('Y-m-d h:i:s', strtotime( $field_value ) );
               // $field_value = htmlentities2($field_value);

            }

            // $error = false;
            //
            // // Do stuff.
            // $woops=1;
            // if ($woops) {
            //    $error = new WP_Error($code, $msg);
            // }
            //
            // if ($error) {
            //    $_SESSION['backend-factory-errors'] = $error->get_error_message();
            // }



            if( is_array($field['translations']) ) :

              foreach( $field['translations'] as $translationKey => $translation ) :
                update_post_meta(
                  $post_id,
                  $field_name . "_" . $translationKey,
                  $field_translated_values[$translationKey]
               );
              endforeach;

            else:

                update_post_meta(
                  $post_id,
                  $field_name,
                  $field_value
                );

            endif;


      }

   endforeach;

endif;

$_SESSION['backend-factory-errors'] = $errors;


// $_SESSION['backend-factory-errors'] = "should save";

}



}





public function standard_metabox_html( $post,  $callback_args ) {

   $args = $callback_args['args'];
   $metabox = $args['metabox'];

   wp_nonce_field(basename(__FILE__), $metabox['name']."-metabox-nonce" );

   ?>


   <div class="metabox">

      <nav class="metabox-language-selector">
          <ul>
            <?php foreach ($metabox['translations'] as $key) : ?>

              <li>
                <a href="#" lang="<?php echo $key; ?>">
                  <?php echo $this->backendFactory->translations[$key]['label']; ?>
                </a>
              </li>

            <?php endforeach; ?>
          </ul>
      </nav>

      <p>
         <?php echo $metabox['description']; ?>
      </p>

      <div class="metabox-fields">
         <?php




         foreach($metabox['fields'] as $field ) :

           $fieldLabel = $field['field_label'];

           $fieldName = $field['field_name'];

           if( ! array_key_exists( $currentTranslation, $field['translations'] ) ) :
             if( isset($field['description'] ) && $field['description'] != '' ) :
               $fieldDescription = $field['description'];
             else:
               $fieldDescription = NULL;
             endif;

           endif;

            ?>

              <div class="field-container">
               <?php

               $container_classes = "field-inputs ";
               $container_classes .= 'field-' . $field['field_type'];
               $container_classes .= $field['repeatable'] ? " field-repeatable-inputs" : "";

               if( $field['repeatable'] ) :
                  ?>

                  <div class="repeatable-model hidden">
                     <div class="input-container">
                        <?php
                        $value = NULL;
                        echo $this->field_creator->create_field( $field, $value );
                        ?>
                     </div>
                  </div>

               <?php endif; ?>

                 <?php if ($field['translations'] ) :

                   foreach ($field['translations'] as $key => $translation) : ?>

                   <h4 class="label-translated" lang="<?php echo $key; ?>">
                      <?php echo $translation['field_label']; ?>
                    </h4>
                    <p class="description-translated" lang="<?php echo $key; ?>">
                      <?php echo $translation['description']; ?>
                    </p>
                  <?php endforeach;

               else:

                 if( $fieldLabel ) :
                   ?>
                   <h4>
                     <?php echo $fieldLabel; ?>
                   </h4>
                   <?php
                 endif;

                 if( $fieldDescription ) : ?>
                   <p>
                     <?php echo $fieldDescription; ?>
                   </p>
                 <?php endif;

               endif;
               ?>


               <div class="<?php echo $container_classes; ?>">
                  <?php

                  if( is_array($field['translations']) ) :

                  foreach( $field['translations'] as $translationKey => $translation ) :
                    $fieldName = $field['field_name'] . "_" . $translationKey;

                    $value = get_post_meta( $post->ID, $fieldName, true);

                    $valueArray = array();

                    if( ! $value ) {
                       // If field is empty, show an empty form component:
                       array_push( $valueArray, NULL );

                    } else {
                       // if this field's value is an array:
                       if( is_array($value) ) {

                          foreach( $value as $one_value ) {
                             // don't display any null or empty values
                             if( $one_value && $one_value != "" ) {
                                array_push( $valueArray, $one_value );
                             }
                          }

                          // add an empty one for repeatables
                          array_push( $valueArray, NULL );

                       } else {

                          // don't display any null or empty values
                          if($value && $value != "") {
                             array_push( $valueArray, $value );
                          }


                       }
                    }


                    foreach ($valueArray as $field_value ) :
                       ?>
                       <div class="input-container">
                          <?php
                          echo $this->field_creator->create_field( $field, $field_value, $translationKey );
                          ?>
                       </div>


                    <?php endforeach; ?>


                <?php endforeach; ?>

                <div class="input-container hidden">
                  <?php
                  echo $this->field_creator->create_field( $field, $field_value, NULL );
                  ?>
                </div>

              <?php else:


                $value = get_post_meta( $post->ID, $fieldName, true);

                $valueArray = array();

                if( ! $value ) {
                   // If field is empty, show an empty form component:
                   array_push( $valueArray, NULL );

                } else {
                   // if this field's value is an array:
                   if( is_array($value) ) {

                      foreach( $value as $one_value ) {
                         // don't display any null or empty values
                         if( $one_value && $one_value != "" ) {
                            array_push( $valueArray, $one_value );
                         }
                      }

                      // add an empty one for repeatables
                      array_push( $valueArray, NULL );

                   } else {

                      // don't display any null or empty values
                      if($value && $value != "") {
                         array_push( $valueArray, $value );
                      }


                   }
                }


                foreach ($valueArray as $field_value ) :
                   ?>
                   <div class="input-container">
                      <?php
                      echo $this->field_creator->create_field( $field, $field_value );
                      ?>
                   </div>


                <?php endforeach; ?>



              <?php endif; ?>


               </div>

               <?php

               if( $field['repeatable'] ) {
                  ?>

                  <button class="add_repeatable button">
                     Add Another
                  </button>

                  <?php
               }

               ?>
            </div>
            <?php
         endforeach;

         ?>



      </div>

   </div>


   <?php
}













   // public function testfunc() {
   //    echo '<h1>Init metaboxes</h1>';
   // }

}

function load_wp_media_files() {
   wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );

















?>
