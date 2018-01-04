<?php

function standard_metabox_html( $post,  $callback_args ) {

   $args = $callback_args['args'];
   $metabox = $args['metabox'];

   wp_nonce_field(basename(__FILE__), $metabox['name']."-metabox-nonce" );


   ?>


   <h1>
     <?php// var_dump( $args['translations'] );  ?>
   </h1>

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

?>
