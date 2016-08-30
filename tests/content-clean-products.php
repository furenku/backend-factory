<?php
clean( array("product"), array("product_cat") );



function clean( $post_types, $taxonomies ) {
   // clean posts

   foreach( $post_types  as $post_type ) :

      $q = new WP_Query( array( 'post_type' => $post_type, 'posts_per_page' => -1 ) );
      if( $q -> have_posts() ) {
         while ( $q -> have_posts() ) {
            $q -> the_post();

            wp_delete_post( get_the_ID(), true );
            
         }
      }
   endforeach;

   foreach( $taxonomies  as $taxonomy ) :

      $terms = get_terms($taxonomy, array(
         'hide_empty' => false,
      ) );
      $count = count($terms);

      foreach ( $terms as $term ) :
         wp_delete_term( $term->term_id, $taxonomy );
      endforeach;

   endforeach;

}


?>
