<?php

class DynamicMetaboxes {

   var $metaboxes;
   var $field_creator;

   public function __construct() {
      $this->metaboxes = array();
      add_action("add_meta_boxes", array( $this, 'register_metaboxes' ) );
      add_action("save_post", array( $this, 'save_metaboxes' ) );

      $this->field_creator = new MetaboxFieldCreator();

      // echo "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laborum praesentium odit sunt adipisci sequi, eaque tenetur quae libero!";
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
         "side",
         "default",
         array('metabox'=>$metabox)
      );
   endforeach;



}

public function save_metaboxes($post_id=0, $post=0, $update=0)
{

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

            if(isset($_POST[ $field['field_name'] ]))
            {

               $field_value = $_POST[ $field['field_name'] ];

               $field_name = $field['field_name'];

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
               if( is_array($field_value) ) {

                  foreach( $related_post_ids as $related_post_id ) {


                     $related_post_type = $field['related_post_types'];
                     $related_post_type = $related_post_type[0];

                     $field_name =  $metabox['post_type'] . '-' . $related_post_type;

                     $field_value = $_POST[ $field['field_name'] ];

                     // checar si hay arreglo de referencias a posts 1 en post 2 recien asignado
                     $posts = get_post_meta(
                     $related_post_id,
                     $related_post_type . '-' . $metabox['post_type'],
                     true
                  );
               );

               if( is_array($posts) ) {
                  if( ! in_array($post_id,$post_id))
                  array_push($posts, $post_id);
               } else {
                  // si no, crear arreglo
                  $posts = array( $post_id );
               }


               update_post_meta(
               $related_post_id,
               $related_post_type . '-' . $metabox['post_type'],
               array_unique($posts)
            );

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



   update_post_meta(
   $post_id,
   $field_name,
   $field_value
);


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

      <p>
         <?php echo $metabox['description']; ?>
      </p>

      <div class="metabox-fields">
         <?php




         foreach($metabox['fields'] as $field ) :
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

               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <div class="<?php echo $container_classes; ?>">
                  <?php
                  $value = get_post_meta( $post->ID, $field['field_name'], true);

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







public function old_metabox_html( $post,  $callback_args ) {
   $args = $callback_args['args'];
   $metabox = $args['metabox'];

   wp_nonce_field(basename(__FILE__), $metabox['name']."-metabox-nonce" );


   ?>

   <p>
      <?php echo $metabox['description']; ?>
   </p>
   <div>
      <?php


      foreach($metabox['fields'] as $field ) :

         $field_name = $field['field_name'];

         echo '<div class="field-container field-'.$field['field_type'].'">';




         $value = get_post_meta( $post->ID, $field['field_name'], true);


         if( $field['field_type'] == "related_post" ) {

            foreach ($field['related_post_types'] as $related_post_type ) {

               $field_name = $metabox['post_type'];

               $related_post_type_field_name =  $field_name . '-' . $related_post_type;

               $posts = get_posts( array( 'post_type' => $related_post_type, 'numberposts' => -1 ) );

               if( $field['repeatable'] ) {
                  $related_post_type_field_name .= '[]';
                  $field_creator -> related_post_selector( $related_post_type_field_name, $posts, 0, true, true );

               }

               $related_posts = get_post_meta(
               $post->ID,
               $related_post_type_field_name,
               true );

               ?>

               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <?php


               if(is_array($related_posts)) {
                  // if(count($related_posts)>0) {
                  foreach( $related_posts as $related_post ) {
                     if( $related_post != "0" ) {
                        $field_creator -> related_post_selector( $related_post_type_field_name, $posts, $related_post, $field['repeatable'] );
                     }
                  }
                  // } else {
                  //    if( ! $field['repeatable'] )
                  //       related_post_selector( $related_post_type_field_name, $posts, 0, $field['repeatable'] );
                  // }
               } else {
                  if( ! $field['repeatable'] ) {

                     $field_creator -> related_post_selector( $related_post_type_field_name, $posts, (int)$related_posts, $field['repeatable'] );
                  }
               }

               if( $field['repeatable'] ) {
                  $field_creator -> related_post_selector( $related_post_type_field_name, $posts, $field['repeatable']  );
               }

            }

         }



         if( $field['field_type'] == "textarea" ) {
            ?>

            <div class="columns">
               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>
               <div class="columns p4">
                  <?php if( $field['repeatable'] ) : ?>
                     <div class="repeatable hidden">
                        <textarea name="<?php echo $field['field_name']; ?>" class="repeatable hidden"><?php echo $value; ?></textarea>
                     </div>
                  <?php endif; ?>
                  <textarea name="<?php echo $field['field_name']; ?>"><?php echo $value; ?></textarea>
               </div>

            </div>

            <?php

         }

         if( $field['field_type'] == "date" ) {

            ?>

            <h4>
               <?php echo $field['field_label']; ?>
            </h4>

            <div class="columns p4">
               <?php if( $field['repeatable'] ) : ?>
                  <div class="repeatable hidden">
                     class="repeatable hidden"
                     <input type="datetime" data-target="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="datepicker">
                     <input type="datetime" id="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="hidden">
                  <?php endif; ?>
                  <input type="datetime" data-target="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="datepicker">
                  <input type="datetime" id="<?php echo $field['field_name']; ?>" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>" class="hidden">
               </div>
            </div>

            <?php

         }

         if( $field['field_type'] == "time" ) {

            ?>

            <div class="columns">
               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <div class="columns p4"><input type="datetime" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>"></div>

            </div>

            <?php

         }
         if(
         $field['field_type'] == "text"      ||
         $field['field_type'] == "email"     ||
         $field['field_type'] == "url"       ||
         $field['field_type'] == "number"    ||
         $field['field_type'] == "integer"   ||
         $field['field_type'] == "float"
         ) {
            $field_type = $field['field_type'];

            $input_field_type = $field_type;

            switch( $field_type ) {
               case "integer" :
               case "float" :
               $input_field_type = "number";
               break;
               default :
               $input_field_type = $field_type;
               break;

            }
            ?>

            <div class="field columns">

               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <p>
                  <?php echo $field['description']; ?>
               </p>

               <div class="field-inputs columns p4 <?php echo $field['repeatable'] ? 'field-repeatable-inputs' : ''; ?>">

                  <?php
                  if( $value ) {
                     echo $field_creator -> form_input( $field, $value );
                  }
                  ?>

               </div>

               <?php if( $field['repeatable'] ) { ?>

                  <button class="add_repeatable button">
                     Add Another
                  </button>

                  <?php } ?>

               </div>

               <?php

            }

            if( $field['field_type'] == "html" ) {

               ?>

               <div class="columns">

                  <h4>
                     <?php echo $field['field_label']; ?>
                  </h4>

                  <div class="columns p4">
                     <textarea name="<?php echo $field['field_name']; ?>">
                        <?php echo $value; ?>
                     </textarea>
                  </div>

               </div>

               <?php

            }

            if( $field['field_type'] == "upload" ) {
               // jQuery
               wp_enqueue_script('jquery');
               // This will enqueue the Media Uploader script
               wp_enqueue_media();

               $upload_input_id = "upload_input-" . $field['field_name'];
               $upload_button_id = "upload_button-" . $field['field_name'];
               ?>
               <div class="columns">
                  <h4>
                     <?php echo $field['field_label']; ?>
                  </h4>
                  <div class="columns">
                     <label for="<?php echo $upload_input_id; ?>">Select File</label>
                     <input id="<?php echo $upload_input_id; ?>" type="url" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>">
                     <input type="button" name="<?php echo $upload_button_id; ?>" id="<?php echo $upload_button_id; ?>" class="button-secondary" value="Upload File">
                  </div>
               </div>
               <script type="text/javascript">
               jQuery(document).ready(function($){
                  $('#<?php echo $upload_button_id; ?>').click(function(e) {
                     e.preventDefault();
                     var file = wp.media({
                        title: 'Upload Image',
                        // mutiple: true if you want to upload multiple files at once
                        multiple: false
                     }).open()
                     .on('select', function(e){

                        var uploaded_file = file.state().get('selection').first();

                        var file_url = uploaded_file.toJSON().url;

                        $('#<?php echo $upload_input_id; ?>').val(file_url);
                     });
                  });
               });
               </script>

               <?php
            }



            echo '</div>';

         endforeach; ?>

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
