<?php

/**
 * Plugin Name:       Product Navigation
 * Plugin URI:        https://github.com/Norimx/product-navigation/
 * Description:       This is a very basic plugin that adds Next/Prev navigation to Woocoommerce products.
 * Version:           1.0.0
 * Author:            Nori
 * Author URI:        https://github.com/Norimx/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       product-navigation
 */

function my_previous_post_where() {
	global $post, $wpdb;
	return $wpdb->prepare( "WHERE p.menu_order < %s AND p.post_type = %s AND p.post_status = 'publish'", $post->menu_order, $post->post_type);
}
add_filter( 'get_previous_post_where', 'my_previous_post_where' );
function my_next_post_where() {
	global $post, $wpdb;
	return $wpdb->prepare( "WHERE p.menu_order > %s AND p.post_type = %s AND p.post_status = 'publish'", $post->menu_order, $post->post_type);
}
add_filter( 'get_next_post_where', 'my_next_post_where' );
function my_previous_post_sort() {
	return "ORDER BY p.menu_order desc LIMIT 1";
}
add_filter( 'get_previous_post_sort', 'my_previous_post_sort' );
function my_next_post_sort() {
	return "ORDER BY p.menu_order asc LIMIT 1";
}
add_filter( 'get_next_post_sort', 'my_next_post_sort' );


add_action('woocommerce_after_single_product', 'insert_nexprev_cat_names');

    function insert_nexprev_cat_names() {

    if ( ! is_product() ) {
        return;
    }
    // Do nothing if there's nowhere to navigate.
    $previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( $same_cat, '', true );
    $next     = get_adjacent_post( $same_cat, '', false );

        // Do the magic if there is somewhere to navigate

    if ( ! $next && ! $previous ) {
        return;
    }

      echo '<nav class="product-navigator product-navigation ajaxify" role="navigation">';


            if ( is_attachment() ) :
            previous_post_link( '%link', __( '<span id="older-nav">Go back</span>' ) );
        else :
            $prev_img = get_the_post_thumbnail( $previous->ID, 'thumbnail' );
            $next_img = get_the_post_thumbnail( $next->ID, 'thumbnail' );
            $prev_img = $prev_img ? '<span class="nav-image">' . $prev_img . '</span>' : '';
            $next_img = $next_img ? '<span class="nav-image">' . $next_img . '</span>' : '';
            previous_post_link( '%link', '<span id="older-nav">' . $prev_img . '<span class="outter-title"><span class="entry-title">' . get_the_title( $previous->ID ) . '</span></span></span>', $same_cat );
            next_post_link( '%link', '<span id="newer-nav">' . $next_img . '<span class="outter-title"><span class="entry-title">' . get_the_title( $next->ID ) . '</span></span></span>', $same_cat );
        endif;
      echo '</nav>';
}
