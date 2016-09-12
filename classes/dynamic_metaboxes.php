<?php

class DynamicMetaboxes {

   var $metaboxes;

   public function init() {
      $this->metaboxes = array();
      add_action("add_meta_boxes", array( $this, 'register_metaboxes' ) );
      add_action("save_post", array( $this, 'save_metaboxes' ) );


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

   // if( ! $post )
   //    return $post_id;
   $errors = "debug: ";
   if(!current_user_can("edit_post", $post_id))
   return $post_id;


   if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
   return $post_id;
   if( is_array( $this->metaboxes ) );

   foreach( $this->metaboxes as $metabox ) {


      // $errors .= isset($_POST[ $metabox['name']."-metabox-nonce" ]);

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

      } elseif( $field_type == "date" ) {

         // $field_value = date('Y-m-d h:i:s', strtotime( $field_value ) );
         $field_value = date('Y-m-d', strtotime( $field_value ) );

      }
      elseif( $field_type == "html" ) {

         // $field_value = date('Y-m-d h:i:s', strtotime( $field_value ) );
         $field_value = htmlentities2($field_value);

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

   <p>
      <?php echo $metabox['description']; ?>
   </p>
   <div>
      <?php


      foreach($metabox['fields'] as $field ) :

         $field_name = $field['field_name'];

         echo '<div class="field '.$field['field_type'].'">';




         $value = get_post_meta( $post->ID, $field['field_name'], true);



         if( $field['field_type'] == "related_post" ) {

            foreach ($field['related_post_types'] as $related_post_type ) {

               $field_name = $metabox['post_type'];

               $related_post_type_field_name =  $field_name . '-' . $related_post_type;

               $posts = get_posts( array( 'post_type' => $related_post_type ) );

               if( $field['repeatable'] ) {

                  $this -> related_post_selector( $related_post_type_field_name, $posts, 0, true, true );

               }

               $related_posts = get_post_meta(
               $post->ID,
               $related_post_type_field_name,
               true );

               if(is_array($related_posts)) {
                  // if(count($related_posts)>0) {
                  foreach( $related_posts as $related_post ) {
                     if( $related_post != "0" )
                     $this -> related_post_selector( $related_post_type_field_name, $posts, $related_post, $field['repeatable'] );
                  }
                  // } else {
                  //    if( ! $field['repeatable'] )
                  //       related_post_selector( $related_post_type_field_name, $posts, 0, $field['repeatable'] );
                  // }
               } else {
                  if( ! $field['repeatable'] ) {

                     $this -> related_post_selector( $related_post_type_field_name, $posts, (int)$related_posts, $field['repeatable'] );
                  }
               }

               if( $field['repeatable'] ) {
                  $this -> related_post_selector( $related_post_type_field_name, $posts, $field['repeatable']  );
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
                  <textarea name="<?php echo $field['field_name']; ?>"><?php echo $value; ?></textarea>
               </div>

            </div>

            <?php

         }

         if( $field['field_type'] == "date" ) {

            ?>

            <div class="columns">
               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <div class="columns p4">
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

            <div class="columns">

               <h4>
                  <?php echo $field['field_label']; ?>
               </h4>

               <p>
                  <?php echo $field['description']; ?>
               </p>

               <div class="columns p4">
                  <input
                  type="<?php echo $input_field_type; ?>"
                  name="<?php echo $field['field_name']; ?>"
                  value="<?php echo $value; ?>"
                  <?php echo $field_type == "float" ? 'step="0.000001"': ''; ?>
                  >
               </div>

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


public function related_post_selector( $name, $posts, $id=0, $repeatable = false, $hidden=false ) {


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


// public function testfunc() {
//    echo '<h1>Init metaboxes</h1>';
// }

}

?>
