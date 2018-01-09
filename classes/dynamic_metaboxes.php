<?php

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
         $metabox['title']."-meta-box",
         $metabox['title'],
         array($this,"standard_metabox_html"),
         $metabox['post_type'],
         "normal",
         "high",
         array('metabox'=>$metabox)
       );
     endforeach;

   }

public function save_metaboxes($post_id=0, $post=0, $update=0)
{

    $currentTranslation = $this->backendFactory->currentTranslation;



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

                if( is_array($field['translations']) ) :

                  $field_translated_values = array();

                  foreach( $field['translations'] as $translationKey => $translation ) :

                    $field_translated_values[$translationKey] = $_POST[ $field_name . "_" . $translationKey ];

                    if( is_array($field_translated_values[$translationKey]) ) {

                      $field_translated_values[$translationKey] = array_filter(
                        $field_translated_values[$translationKey],
                        function( $v ) {
                          return $v != '';
                        }
                      );
ob_start();
var_dump( array_values( $field_translated_values[$translationKey] ) );
$dump = ob_get_contents();
$errors = $dump;
                      $field_translated_values[$translationKey] = array_values( $field_translated_values[$translationKey] );

                    }
                  endforeach;


                else:

                  $errors = "no translations array ! []";

                endif;

               $field_value = $_POST[ $field_name ];

               if( is_array($field_value) ) {

                 $field_value = array_filter(
                   $field_value,
                   function( $v ) {
                     return $v != '';
                   }
                 );

                 $field_value = array_values( $field_value );

               }

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



            // clean array

            if( $field['field_type'] == "field_group" ) {

              if( is_array($field_value) ) {

                if( is_array($field_translated_values) ) {


                  $field_translated_values = cleanArray( $field_translated_values );

                  $field_translated_values = array_filter(
                    $field_translated_values,
                    function( $key, $valueArray ) {
                      if( is_array( $valueArray )) :
                        foreach( $valueArray as $eachKey => $eachValue ) :
                          $empty = $eachValue == '';
                          if( $empty ) {
                            unset( $valueArray[ $eachKey ] );
                          }
                        endforeach;
                      endif;

                      $empty = count($valueArray<=0);

                      return $empty;
                    },
                    ARRAY_FILTER_USE_BOTH
                  );


                } else {

                  $field_value = array_filter(
                    $field_value,
                    function( $v ) {
                      return ! $v == '';
                    }
                  );

                }


                foreach( $field_value as $key => $value ) {

                  $eachFieldGroup = $field_value[$key];

                  if( is_array( $eachFieldGroup ) ) {

                    $field_value[$key] = array_filter(
                      $eachFieldGroup,
                      function($v,$k) {
                        $empty = $v == '';
                        if( $empty ) {
                          unset( $eachFieldGroup[$k] );
                        } else {
                          if( is_array($v) ) {
                            foreach( $v as $ak=>$av ) {
                              $empty = $av == '';
                              if( $empty ) {
                                unset( $v[$ak] );
                              }
                            }
                            if( ! count( $v ) ) {
                              unset( $eachFieldGroup[$k] );
                            }
                          }
                        }
                        return ! $empty;
                      },
                      ARRAY_FILTER_USE_BOTH
                    );
                  }

                }


              }
            }

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

           // if( ! array_key_exists( $currentTranslation, $field['translations'] ) ) :
           //
           // endif;

            ?>

              <div class="field-container">

               <?php

               $container_classes = "field-inputs ";
               $container_classes .= 'field-' . $field['field_type'];
               $container_classes .= $field['repeatable'] ? " field-repeatable-inputs" : "";

               ?>

               <div class="<?php echo $container_classes; ?>">

                  <?php

                  if( is_array($field['translations']) ) :


                  foreach( $field['translations'] as $translationKey => $translation ) :

                    $fieldName = $field['field_name'] . "_" . $translationKey;

                    $value = get_post_meta( $post->ID, $fieldName, true);

                    $valueArray = array();
                    ?>

                    <div class="field-translated" lang="<?php echo $translationKey; ?>">

                      <h4 class="label-translated">
                         <?php echo $translation['field_label']; ?>
                       </h4>

                       <p class="description-translated">
                         <?php echo $translation['description']; ?>
                       </p>

                      <?php


                      if( ! $value ) {
                         // If field is empty, show an empty form component:
                         array_push( $valueArray, NULL );

                      } else {
                         // if this field's value is an array:

                         if( is_array( $value ) ) {


                           if( ! isAssoc($value) ) {

                              foreach( $value as $one_value ) {
                                 // don't display any null or empty values
                                 if( $one_value && $one_value != "" ) {
                                    array_push( $valueArray, $one_value );
                                 }
                              }

                          } else {

                            if( is_array($value) ) {
                              foreach( $value as $eachValue ) {
                                array_push( $valueArray, $eachValue );
                              }
                            } else {
                              array_push( $valueArray, $value );
                            }

                          }

                          // add an empty one for repeatables
                          if( $field['repeatable'] ) {
                            array_push( $valueArray, NULL );
                          }

                         } else {

                            // don't display any null or empty values
                            if($value && $value != "") {
                               array_push( $valueArray, $value );
                            }


                         }
                      }

                      if( $field['repeatable'] ) :
                         ?>

                         <div class="repeatable-model hidden" lang="<?php echo $translationKey; ?>">
                           <div class="input-container" data-type="<?php echo $field['field_type']; ?>">
                               <?php
                               // $i = $field['field_type'] == 'field_group' ? -1 : NULL;
                               echo $this->field_creator->create_field( $field, NULL, $translationKey );
                               ?>
                            </div>
                         </div>

                       <?php endif;

                       $i = 0;
                      foreach ($valueArray as $arrayValue ) :

                        if( is_array($arrayValue) ) : ?>
                           <div class="input-container" data-type="<?php echo $field['field_type']; ?>" data-i="<?php echo $i; ?>">
                              <?php
                              echo $this->field_creator->create_field( $field, $arrayValue, $translationKey, $i );
                              ?>
                           </div>
                            <?php
                            $i++;
                        else:

                          ?>
                            <div class="input-container" data-type="<?php echo $field['field_type']; ?>" data-i="<?php echo $i; ?>">
                              <?php
                              echo $this->field_creator->create_field( $field, $arrayValue, $translationKey, $i );
                              ?>
                            </div>
                          <?php
                        endif;
                        // $i++;
                     endforeach;
                     ?>

                    </div>
                    <!-- .field-translated -->

                  <?php endforeach; ?>

                  <div class="input-container hidden">
                    <?php
                    if( is_array($field['translations']) ) {
                      echo $this->field_creator->create_field( $field, $field_value );
                    }
                    ?>
                  </div>



              <?php else:

                ?>

                <h4 class="label">
                   <?php echo $field['field_label']; ?>
                 </h4>

                 <p class="description">
                   <?php echo $field['description']; ?>
                 </p>

                 <?php

                $value = get_post_meta( $post->ID, $fieldName, true);

                $valueArray = array();

                if( ! $value ) {
                   // If field is empty, show an empty form component:
                   array_push( $valueArray, NULL );

                } else {
                   // if this field's value is an array:
                   if( is_array($value) ) {

                   // var_dump($value);

                     if( ! isAssoc($value) ) {

                        foreach( $value as $one_value ) {
                           // don't display any null or empty values
                           if( $one_value && $one_value != "" ) {
                              array_push( $valueArray, $one_value );
                           }
                        }

                      } else {

                        if( is_array($value) ) {

                          foreach ($value as $eachValue) {
                            array_push( $valueArray, $eachValue );
                          }

                        } else {
                          array_push( $valueArray, $value );
                        }

                      }

                      if( $field['repeatable'] ) {
                        array_push( $valueArray, NULL );
                      }

                      // add an empty one for repeatables

                   } else {

                      // don't display any null or empty values
                      if($value && $value != "") {
                         array_push( $valueArray, $value );
                      }


                   }
                }

                if( $field['repeatable'] ) :
                   ?>

                   <div class="repeatable-model hidden">
                      <div class="input-container" data-type="<?php echo $field['field_type']; ?>">
                         <?php
                         echo $this->field_creator->create_field( $field );
                         ?>
                      </div>
                   </div>

                 <?php endif;

                 $i = 0;
                foreach ($valueArray as $field_value ) :

                   ?>
                   <div class="input-container" data-type="<?php echo $field['field_type']; ?>" data-i="<?php echo $i; ?>">
                      <?php
                      echo $this->field_creator->create_field( $field, $field_value, NULL, $i );
                      ?>
                   </div>


                <?php $i++; endforeach; ?>



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




// util

function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}


function clearEmptyAssoc(array $arr)
{
    $new_arr = $arr;

    foreach( $arr as $arr_k => $arr_v ) {
      if( $arr_v == '' ) {
        unset( $new_arr[ $arr_k] );
      }
    }

    return $new_arr;

}

function clearEmptyArrays(array $arr)
{

  $new_arr = $arr;

  foreach( $arr as $arr_k => $arr_v ) {
    if( is_array($arr_v) ) {
      if( count($arr_v) <= 0 ) {
        unset( $new_arr[ $arr_k] );
      }
    }
  }

  return $new_arr;

}


function cleanArray(array $arr)
{
  if( is_array( $arr ) ) {

    $new_arr = $arr;

    $new_arr = clearEmptyAssoc($new_arr);
    // $new_arr = clearEmptyArrays($new_arr);

    foreach( $new_arr as $arr_k => $arr_v ) {
      if( is_array($arr_v) ) {
        $new_arr[$arr_k] = cleanArray($arr_v);
      }
    }


    return $new_arr;

  }
}


?>
