<?php

function film_roll() {
  $html = '<div id="film_roll">';

  $args = array(
    'post_type' => 'any',
    'post_status' => 'publish',
    'meta_key' => 'featured',
    'orderby' => 'menu_order',
    'order' => 'ASC'
  );

  $my_query = new WP_Query($args);

  if ( $my_query->have_posts() ) { 
    while ( $my_query->have_posts() ) { 
      $my_query->the_post();
      global $post;
      //print_r($post);
      if ( has_post_thumbnail( $post->ID ) &&
        ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'slider' ) )) {
          $html .= '<div><a href="' . get_permalink() . '" title="' . get_the_title() . '"><img src="'. $image[0] .'" width="'. $image[1] . '" height="'. $image[2] .'" /></a></div>';
        }
    }
  }
  wp_reset_postdata();

  $html .= '</div>';
  return $html;
}

function add_custom_post_types() {
  register_post_type('person', array(
    'label' => 'People',
    'labels' => array(
      'name' => 'People',
      'singular_name' => 'Person',
      'add_new_item' => 'Add Person',
      'edit_item' => 'Edit Person',
      'new_item' => 'New Person',
      'view_item' => 'View Person',
      'search_items' => 'Search People'
    ),
    'public' => true,
    'supports' => array('title', 'editor', 'page-attributes', 'custom-fields')
  ));
}

function create_person( $attributes ) {
  $args = shortcode_atts( array(
    'post_type' => 'person',
    'name' => '',
  ), $attributes );

  if (!empty($attributes['name'])) {
    $the_query = new WP_Query( $args );

    $the_query->the_post();

    $image_key_values = get_post_custom_values('picture');
    $image_url = $image_key_values[0];

    $html = '<div class="person"><h4><strong>
      <a href="'.$image_url.'">
      <img class="alignleft size-full person_photo" src="'.$image_url.'" alt="" />
      </a>'.get_the_title().'</strong></h4>';
    $html .= '<p>'.get_the_content().'</p></div>';

    wp_reset_query();
  }

  return $html;
}

function googleMaps_shortcode($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '640',
      "height" => '480',
      "src" => ''
   ), $atts));
   return '<iframe width="'.esc_attr($width).'" height="'.esc_attr($height).'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="'.esc_attr($src).'&amp;output=embed"></iframe>';
}
add_shortcode("googlemap", "googleMaps_shortcode");

define ('SLIDER_IMAGE_WIDTH', 600);
define ('SLIDER_IMAGE_HEIGHT', 198);
add_image_size('slider', SLIDER_IMAGE_WIDTH, SLIDER_IMAGE_HEIGHT);
add_shortcode( 'person', 'create_person' );
wp_enqueue_script('filmroll', get_bloginfo('stylesheet_directory') . '/jquery.film_roll.min.js',array('jquery'));
wp_enqueue_script('digitallyconnected', get_bloginfo('stylesheet_directory') . '/digitally_connected.js',array('filmroll'));
add_action('init', 'add_custom_post_types');

?>
