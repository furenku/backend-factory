# backend-factory
## Wordpress Plugin
###by kernspaltung

##Backend Configuration tool for WP Developers

+ Simple Custom Post Type Creation API
+ Automatic Fields and Metaboxes
+ Custom Post Type Relationships

```
// CPT registration
$cpt = array(
   'post_type' => 'thing',
   'singular' => "Thing",
   'plural' => "Things"
);

$backendFactory -> addCPT( $cpt );

$backendFactory -> register_cpts();
```
