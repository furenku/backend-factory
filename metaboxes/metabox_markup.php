<?php

function standard_metabox_markup( $post,  $callback_args ) {

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

         // $field_name = $field['field_type'] . '-' . $metabox['post_type'];
         $field_name = $metabox['post_type'];

         echo '<div class="field">';


         // echo '<div class="field_label">';
         //
         // echo '<label for="'. $field['field_name'].'">'. $field['field_label'] .'</label>';
         //
         // echo '</div>';


         echo '<div class="field_inputs">';




         if( $field['field_type'] == "related_post" ) {

            foreach ($field['related_post_types'] as $related_post_type ) {

               $related_post_type_field_name =  $field_name . '-' . $related_post_type;

               $posts = get_posts( array( 'post_type'=>$related_post_type ) );

               related_post_selector( $related_post_type_field_name, $posts, 0, true );

               $related_posts = get_post_meta(
               $post->ID,
               $related_post_type_field_name,
               true
            );

            if(is_array($related_posts)) {
               foreach( $related_posts as $related_post ) {
                  if( $related_post != "0" )
                  related_post_selector( $related_post_type_field_name, $posts, $related_post );
               }
            }

            related_post_selector( $related_post_type_field_name, $posts );

         }

      }


      if( $field['field_type'] == "datebooking" ) {

         ?>

         <div class="columns">
            <?php

            $fechas = get_post_meta( $post_id, 'dates', true );
            var_dump( $fechas );

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


function related_post_selector( $name, $posts, $id=0, $hidden=false ) {

   $name .= $name  . '[]';

   ?>
   <div class="repeatable <?php echo $hidden ? 'hidden':''; ?>">

      <select class="" name="<?php echo $name; ?>">
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
