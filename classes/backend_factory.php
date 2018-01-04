<?php


class BackendFactory {

   var $cpts;
   var $translations = array();

   var $defaultTranslation = "en";
   var $currentTranslation = "en";



   function __construct() {
      $this->cpts = array();
      $this->load_assets();
      $this->query_vars();
      global $BackendFactory;

      $BackendFactory = $this;

   }


   function query_vars(){
     function add_custom_query_var( $vars ){
       $vars[] = "backend_lang";
       return $vars;
     }
     add_filter( 'query_vars', 'add_custom_query_var' );
   }


   function load_assets() {

      wp_enqueue_style( "jquery", plugin_dir_url( __FILE__ ) . "../bower_components/jqueryui-datepicker/datepicker.css" );

      wp_enqueue_script( "jquery", plugin_dir_url( __FILE__ ) . "../bower_components/jquery/dist/jquery.min.js" );
      wp_enqueue_script( "jquery-ui-core", plugin_dir_url( __FILE__ ) . "../bower_components/jqueryui-datepicker/core.js", array('jquery') );
      wp_enqueue_script( "jquery-ui-datepicker", plugin_dir_url( __FILE__ ) . "../bower_components/jqueryui-datepicker/datepicker.js", array('jquery') );

      wp_enqueue_script( "backend-factory", plugin_dir_url( __FILE__ ) . "../assets/js/backend-factory.js", array('jquery-ui-datepicker') );

  }

  // translation
  function add_translation( $key=NULL, $translation = NULL ) {

    if( is_array( $translation ) ) {

      $this->translations[$key] = $translation;

    }

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
            'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
            'taxonomies'         => array( 'category', 'post_tag' )
         );

         register_post_type( $slug, $args );

      }

   }

}
