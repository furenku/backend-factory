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


            if( $field['field_type'] == "datebooking" ) {

               $fechas = array();

               if(isset($_POST[ 'taller-fecha_inicio'])) {
                  $fechas[ 'taller-fecha_inicio' ] = $_POST[ 'taller-fecha_inicio'];
               }
               if(isset($_POST[ 'taller-fecha_final'])) {
                  $fechas[ 'taller-fecha_final' ] = $_POST[ 'taller-fecha_final'];
               }
               if(isset($_POST[ 'taller-horarios'])) {
                  $fechas[ 'taller-horarios' ] = $_POST[ 'taller-horarios'];
               }
               if(isset($_POST[ 'taller-lugar'])) {
                  $fechas[ 'taller-lugar' ] = $_POST[ 'taller-lugar'];
               }

               update_post_meta(
                  $post_id,
                  'fechas',
                  $fechas
               );

            }


            if( $field['field_type'] == "related_post" ) {

               $related_post_ids = $field_value;

               foreach( $related_post_ids as $related_post_id ) {

                  $related_post_type = $field['related_post_types'];
                  $related_post_type = $related_post_type[0];

                  // checar si hay arreglo de referencias a posts 1 en post 2 recien asignado
                  $posts = get_post_meta(
                     $related_post_id,
                     $related_post_type.'-'.$metabox['post-type'],
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
                     $related_post_type.'-'.$metabox['post_type'],
                     array_unique($posts)
                  );

               }

            }

            update_post_meta(
               $post_id,
               $field['field_name'],
               $field_value

            );

         }

      }

   }

}

add_action("save_post", "save_dynamic_metaboxes", 10, 3);
