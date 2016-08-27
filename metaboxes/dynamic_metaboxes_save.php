<?php

function save_dynamic_metaboxes($post_id, $post, $update)
{
   //
   //  $slug = "editorial";
   //  if($slug != $post->post_type)
   //      return $post_id;


   global $metaboxes;

   if(!current_user_can("edit_post", $post_id))
   return $post_id;

   if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
   return $post_id;

   foreach( $metaboxes as $metabox ) {
      if( $metabox['post_type'] == $post->post_type )
      if (!isset($_POST[ $metabox['name']."-metabox-nonce" ]) || !wp_verify_nonce($_POST[ $metabox['name']."-metabox-nonce" ], basename(__FILE__)))
      return $post_id;


      foreach($metabox['fields'] as $field) {


         if(isset($_POST[ $field['field_name'] ]))
         {

            $field_value = $_POST[ $field['field_name'] ];

            $field_name = $field['field_name'];

            if( $field['field_type'] == "datebooking" ) {

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

            }


            if( $field['field_type'] == "related_post" ) {


               $related_post_ids = $field_value;

               foreach( $related_post_ids as $related_post_id ) {

                  $related_post_type = $field['related_post_types'];
                  $related_post_type = $related_post_type[0];

                  $field_name = $metabox['post_type'] . '-' .  $related_post_type;
                  $field_value = $_POST[ '$field_name' ];

                  // checar si hay arreglo de referencias a posts 1 en post 2 recien asignado
                  $posts = get_post_meta(
                     $related_post_id,
                     $field_name,
                     true
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
                     $field_name,
                     array_unique($posts)
                  );

               }

            }

            $error = false;

             // Do stuff.
            $woops=false;
             if ($woops) {
                 $error = new WP_Error($code, $msg);
             }

             if ($error) {
               $_SESSION['backend-factory-errors'] = $error->get_error_message();
             }


            update_post_meta(
               $post_id,
               $field_name,
               $field_value

            );

         }

      }

   }

}

add_action("save_post", "save_dynamic_metaboxes" );



add_action("admin_notices","wp_errors");

function wp_errors() {
   if ( array_key_exists( 'backend-factory-errors', $_SESSION ) ) {?>
       <div class="error">
           <p><?php echo $_SESSION['backend-factory-errors']; ?></p>
       </div><?php

       unset( $_SESSION['backend-factory-errors'] );
   }
}
