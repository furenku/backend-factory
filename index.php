<?php
/*
Plugin Name: Backend Factory for Wordpress
Author: kernspaltung!
Description: Backend Configuration tool for Developers
*/

global $cpts;

include 'metaboxes/dynamic_metaboxes.php';

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

         $labels = $this -> generate_labels( $singular, $plural );

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


   function generate_labels( $singular, $plural ) {

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

      return $labels;

   }

}


add_action( 'init', 'backend_factory_init' );

function backend_factory_init() {

   $backendFactory = new BackendFactory;

   $backendFactory->init();

   include_once 'tests/bandas-personas.php';

   foreach( $cpts as $cpt ) {
      $backendFactory -> add_cpt( $cpt );
   }

   $backendFactory -> register_cpts();

}


?>
