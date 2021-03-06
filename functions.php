<?php

function film_roll() {
  $html = '<div id="film_roll">';

  $args = array(
    'post_type' => 'any',
    'post_status' => 'publish',
    'meta_key' => 'featured',
    'orderby' => 'menu_order',
    'order' => 'ASC',
    'posts_per_page' => -1
  );

  $my_query = new WP_Query($args);

  if ( $my_query->have_posts() ) { 
    while ( $my_query->have_posts() ) { 
      $my_query->the_post();
      if ( has_post_thumbnail( $my_query->post->ID ) &&
        ( $image = wp_get_attachment_image_src( get_post_thumbnail_id( $my_query->post->ID ), 'slider' ) )) {
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

    $html = '<div class="person"><h4><strong>';
    if (!empty($image_url)) {
      $html .= '<a href="'.$image_url.'">
      <img class="alignleft size-full person_photo" src="'.$image_url.'" alt="" width="250" height="250" />
      </a>';
    } 
    $html .= get_the_title().'</strong></h4>';
    $content = get_the_content();
    if (substr(trim($content), 0, 2) != '<p') {
      $content = '<p>' . $content . '</p>';
    }
      
    $html .= '<div class="person-bio">'.$content.'</div></div>';

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
function storify_shortcode( $atts ){
    extract( shortcode_atts( array(
        'user' => 'berkmancenter',
        'story' => 'oer-grantees-meeting-2012-opening-exercise-part-1'
    ), $atts ));
    return '<script src="http://storify.com/' . esc_attr($user) . '/' . esc_attr($story) . '.js"></script><noscript>[<a href="//storify.com/' . esc_attr($user) . '/' . esc_attr($story) .'" target="_blank">View the story on Storify</a>]</noscript>';
}
add_shortcode( 'storify', 'storify_shortcode' );

function dc_widgets_init() {
  register_sidebar( array(
    'name' => 'Header Widget Area',
    'id' => 'sidebar-top-1',
    'description' => 'Widgets in this area will be shown in the site header.',
  ) );
}

define ('SLIDER_IMAGE_WIDTH', 940);
define ('SLIDER_IMAGE_HEIGHT', 300);
add_image_size('slider', SLIDER_IMAGE_WIDTH, SLIDER_IMAGE_HEIGHT);
add_shortcode( 'person', 'create_person' );
wp_enqueue_script('filmroll', get_bloginfo('stylesheet_directory') . '/jquery.film_roll.min.js',array('jquery'));
wp_enqueue_script('dotdotdot', get_bloginfo('stylesheet_directory') . '/jquery.dotdotdot.min.js',array('jquery'));
wp_enqueue_script('flipclock', get_bloginfo('stylesheet_directory') . '/flipclock.min.js',array('jquery'));
wp_enqueue_script('digitallyconnected', get_bloginfo('stylesheet_directory') . '/digitally_connected.js',array('filmroll', 'dotdotdot', 'flipclock'));
add_action('init', 'add_custom_post_types');
add_action( 'widgets_init', 'dc_widgets_init' );
add_filter('widget_text', 'do_shortcode');
?>
