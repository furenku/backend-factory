<?php

function standard_metabox_html( $post,  $callback_args ) {

   $args = $callback_args['args'];
   $metabox = $args['metabox'];

   wp_nonce_field(basename(__FILE__), $metabox['name']."-metabox-nonce");

   ?>

   <p>
      <?php echo $metabox['description']; ?>
   </p>
   <div>
      <?php


      foreach($metabox['fields'] as $field ) :

         $field_name = $field['field_name'];

         echo '<div class="field">';


         // echo '<div class="field_label">';
         //
         // echo '<label for="'. $field['field_name'].'">'. $field['field_label'] .'</label>';
         //
         // echo '</div>';


         echo '<div class="field_inputs">';




         if( $field['field_type'] == "related_post" ) {

            foreach ($field['related_post_types'] as $related_post_type ) {

               $field_name = $metabox['post_type'];

               $related_post_type_field_name =  $field_name . '-' . $related_post_type;

               $posts = get_posts( array( 'post_type' => $related_post_type ) );

               if( $field['repeatable'] ) {

                  related_post_selector( $related_post_type_field_name, $posts, 0, true, true );

               }

               $related_posts = get_post_meta(
               $post->ID,
               $related_post_type_field_name,
               true );

               if(is_array($related_posts)) {
                  // if(count($related_posts)>0) {
                     foreach( $related_posts as $related_post ) {
                        if( $related_post != "0" )
                        related_post_selector( $related_post_type_field_name, $posts, $related_post, $field['repeatable'] );
                     }
                  // } else {
                  //    if( ! $field['repeatable'] )
                  //       related_post_selector( $related_post_type_field_name, $posts, 0, $field['repeatable'] );
                  // }
               } else {
                  if( ! $field['repeatable'] ) {

                     related_post_selector( $related_post_type_field_name, $posts, (int)$related_posts, $field['repeatable'] );
                  }
               }

               if( $field['repeatable'] ) {
                  related_post_selector( $related_post_type_field_name, $posts, $field['repeatable']  );
               }

            }

         }


         if( $field['field_type'] == "text" ) {

            $date = get_post_meta( $post->ID, $field['field_name'], true);
            ?>

            <div class="columns">
               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>
               <div class="columns p4"><input type="text" name="<?php echo $field['field_name']; ?>" value="<?php echo $date; ?>"></div>
            </div>

            <?php

         }

         if( $field['field_type'] == "date" ) {

            $date = get_post_meta( $post->ID, $field['field_name'], true);
            ?>

            <div class="columns">
               <h4>Fecha</h4>
               <div class="columns p4"><input type="datetime" name="<?php echo $field['field_name']; ?>" value="<?php echo $date; ?>"></div>
            </div>

            <?php

         }

         if( $field['field_type'] == "time" ) {

            $date = get_post_meta( $post->ID, $field['field_name'], true);
            ?>

            <div class="columns">
               <h4>Hora</h4>
               <div class="columns p4"><input type="datetime" name="<?php echo $field['field_name']; ?>" value="<?php echo $date; ?>"></div>
            </div>

            <?php

         }
         if( $field['field_type'] == "integer" ) {

            $value = get_post_meta( $post->ID, $field['field_name'], true);
            ?>

            <div class="columns">
               <h4><?php echo $field['field_label']; ?></h4>

               <p>
                  <?php echo $field['description']; ?>
               </p>

               <div class="columns p4">
                  <input type="datetime" name="<?php echo $field['field_name']; ?>" value="<?php echo $value; ?>">
               </div>

            </div>

            <?php

         }


         if( $field['field_type'] == "datebooking" ) {

            ?>

            <div class="columns">
               <?php

               $fechas = get_post_meta( $post_id, 'date-booking?', true );

               ?>
            </div>
            <div class="repeatable columns">

               <div>
                  <label for="start_date">
                     Fecha de Inicio
                  </label>
                  <input type="text" name="start_date" class="start_date columns date start" />
               </div>


               <div>
                  <label for="end_date">
                     Fecha de Final
                  </label>
                  <input type="text" name="end_date" class="end_date columns date end" />
               </div>


               <div>
                  <label for="schedule">
                     Horarios
                  </label>
                  <input type="text" name="schedule" class="schedule columns" value="">
               </div>
            </div>
            <?php

         }

         if( $field['repeatable'] ) {
            ?>

            <div class="add_repeatable button">Añadir otro</div>

            <?php
         }

         echo '</div>';

         echo '</div>';

      endforeach; ?>

   </div>

   <script>
   jQuery(document).ready(function($){

      $('.add_repeatable').click(function(){
         $(this).parent().find('.repeatable.hidden').clone().detach().removeClass('hidden').appendTo( '.repeatables' );
         $(this).parent().find('.delete_this.hidden').clone().detach().removeClass('hidden').appendTo( '.repeatables' );
      })

      $('.delete_this').click(function(){
         $(this).parent().remove();
      })

   })
   </script>

   <?php
}


function related_post_selector( $name, $posts, $id=0, $repeatable = false, $hidden=false ) {


   $name .= $repeatable ? '[]' : '';

   ?>
   <div class="<?php echo $repeatable ? 'repeatable' : ''; echo " "; echo $hidden ? 'hidden':''; ?>">

      <select class="columns" name="<?php echo $name; ?>">
         <option value="0" <?php echo ! $id ? 'selected' : ''; ?>></option>
         <?php
         foreach( $posts as $post ) : ?>

         <option value="<?php echo $post->ID; ?>" <?php echo $id==$post->ID ? 'selected="true"' : ''; ?>>
            <?php echo $post->post_title; ?>
         </option>

         <?php
      endforeach;
      ?>

   </select>

   <button class="delete_this button<?php echo $hidden ? ' hidden':''; ?>">x</button>

</div>


<br>

<?php
}
