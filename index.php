<?php
/*
Plugin Name: Backend Factory for Wordpress
Author: kernspaltung!
Description: Backend Configuration tool for Developers
*/

global $cpts, $metaboxes;

include 'metaboxes/dynamic_metaboxes.php';

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
   die;
}


class BackendFactory {

   var $cpts;

   function init() {
      $this->cpts = array();
   }


   // CPTs (Custom Post Types):

   function add_cpt( $cpt = NULL ) {
      if( $cpt ) {
         array_push( $this -> cpts, $cpt );
      }
   }

   function register_cpts() {

      foreach( $this -> cpts as $cpt ) {

         $slug = $cpt['post_type'];
         $singular = $cpt['singular'];
         $plural = $cpt['plural'];

         $labels = array(
            'name'               => _x( $plural, 'post type general name', 'backend-factory' ),
            'singular_name'      => _x( $singular, 'post type singular name', 'backend-factory' ),
            'menu_name'          => _x( $plural, 'admin menu', 'backend-factory' ),
            'name_admin_bar'     => _x( $singular, 'add new on admin bar', 'backend-factory' ),
            'add_new'            => _x( 'Add New', $singular, 'backend-factory' ),
            'add_new_item'       => __( 'Add New ' . $singular, 'backend-factory' ),
            'new_item'           => __( 'New ' . $singular, 'backend-factory' ),
            'edit_item'          => __( 'Edit ' . $singular, 'backend-factory' ),
            'view_item'          => __( 'View ' . $singular, 'backend-factory' ),
            'all_items'          => __( 'All ' . $plural, 'backend-factory' ),
            'search_items'       => __( 'Search ' . $plural, 'backend-factory' ),
            'parent_item_colon'  => __( 'Parent ' . $plural . ':', 'backend-factory' ),
            'not_found'          => __( 'No ' . $plural . ' found.', 'backend-factory' ),
            'not_found_in_trash' => __( 'No ' . $plural . ' found in Trash.', 'backend-factory' )
         );

         $args = array(
            'labels'             => $labels,
            'description'        => __( 'Description.', 'backend-factory' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => $slug ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => $cpt['hierarchical'],
            'menu_position'      => null,
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
         );

         register_post_type( $slug, $args );

      }

   }

}



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

      if(!current_user_can("edit_post", $post_id))
      return $post_id;


      if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
      return $post_id;
      if( is_array( $this->metaboxes ) );

$errors = "debug: ";

      foreach( $this->metaboxes as $metabox ) {


         if( $metabox['post_type'] == $post->post_type || !isset($_POST[ $metabox['name']."-metabox-nonce" ]) || ! wp_verify_nonce($_POST[ $metabox['name']."-metabox-nonce" ], basename(__FILE__)))
            return $post_id;


         foreach($metabox['fields'] as $field) {


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

         }
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

add_action( 'init', 'backend_factory_init' );

function backend_factory_init() {

   $backendFactory = new BackendFactory;

   $backendFactory->init();

   $dynamic_metaboxes = new DynamicMetaboxes;

   $dynamic_metaboxes -> init();


   include_once 'tests/cpt-eventos-lugares.php';
   include_once 'tests/metaboxes-evento.php';

   foreach( $cpts as $cpt ) {
      $backendFactory -> add_cpt( $cpt );
   }

   foreach( $metaboxes as $metabox ) {
      $dynamic_metaboxes -> add_metabox( $metabox );
   }

   $backendFactory -> register_cpts();



   if ( !session_id() ) {
      session_start();
   }

   // $backendFactory -> register_metaboxes();
}





add_action("admin_notices","wp_errors");

function wp_errors() {
   if ( array_key_exists( 'backend-factory-errors', $_SESSION ) ) {?>
       <div class="error">
           <p><?php echo $_SESSION['backend-factory-errors']; ?></p>
       </div><?php

       unset( $_SESSION['backend-factory-errors'] );
   }
}

?>
