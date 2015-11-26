<?php
/*
Template Name: Product
*/

//echo "123";
/**
 * The custom product post type archive template
 */
/** Force full width content layout */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Remove the breadcrumb navigation */
// remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

/** Remove the post info function */
remove_action( 'genesis_before_post_title', 'genesis_post_info' );

/** Remove the post content */
remove_action( 'genesis_post_content', 'genesis_do_post_content' );

/** Remove the post image */
remove_action( 'genesis_post_content', 'genesis_do_post_image' );

/** Add the featured image after post title */
add_action( 'genesis_post_title', 'executive_product_grid' );

function executive_product_grid() {
    if ( has_post_thumbnail() ){
        echo '<div class="product-featured-image">';
        echo '<a href="' . get_permalink() .'" title="' . the_title_attribute( 'echo=0' ) . '">';
        //echo get_the_post_thumbnail($thumbnail->ID, array(300,200) );
        echo get_the_post_thumbnail($thumbnail->ID, 'thumbnail' );
        echo '</a>';
        echo '</div>';
    }
}

/** Remove the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

genesis();
