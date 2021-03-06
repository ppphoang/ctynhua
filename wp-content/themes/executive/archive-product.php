<?php
/*
 *
 *
 *
 * */
function about(){
    if ( is_active_sidebar('sidebar-about')) {
        genesis_widget_area('sidebar-about');
    }
}
add_action('genesis_after_header', 'about');
// display as columns
function be_product_post_class( $classes ){

    global $wp_query;
    if( !$wp_query->is_main_query() ) {
        return $classes;
    }

    $columns = 3;
    $columns_classes = array('', '', 'one-half', 'one-third', 'one-fourth', 'one-fifth', 'one-sixth');
    $classes[] = $columns_classes[$columns];
    if ( 0 == $wp_query->current_post % $columns ){

        $classes[] = 'first';

    }
    return $classes;

}
add_filter('post_class', 'be_product_post_class');

/**
 * Add Product Image
 *
 */
function be_product_image() {
    echo wpautop( '<a href="' . get_permalink() . '">' . genesis_get_image( array( 'size' => 'medium' ) ). '</a>' );
}
add_action( 'genesis_entry_content', 'be_product_image' );
add_filter( 'genesis_pre_get_option_content_archive_thumbnail', '__return_false' );

// Move Title below Image
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
add_action( 'genesis_entry_footer', 'genesis_entry_header_markup_open', 5 );
add_action( 'genesis_entry_footer', 'genesis_entry_header_markup_close', 15 );
add_action( 'genesis_entry_footer', 'genesis_do_post_title' );

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
remove_action( 'genesis_before_post_title', 'genesis_post_info' );
remove_action( 'genesis_post_content', 'genesis_do_post_content' );
/* Add the featured image after post title */
add_action('genesis_post_title', 'executive_product_image');
function executive_product_image(){
    if( has_post_thumbnail() ){?>
        <!--html-->
        <div>
            <a href="<?php echo get_permalink(); ?>" title="<?php echo the_title_attribute();?>">
                <?php echo get_the_post_thumbnail($thumbnail->ID, array(300,200));?>
            </a><!--    "' . get_permalink() .'" title="' . the_title_attribute( 'echo=0' ) . '">-->
            <!--echo get_the_post_thumbnail($thumbnail->ID, array(300,200) );-->
        </div>
        <!--html-->
    <?php }
}
//remove_action('genesis_after_post_content', 'genesis_post_meta');

genesis();

/**
 * The custom product post type archive template
 */
/** Force full width content layout */
//add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Remove the breadcrumb navigation */
//remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

/** Remove the post info function */
//remove_action( 'genesis_before_post_title', 'genesis_post_info' );

/** Remove the post content */
//remove_action( 'genesis_post_content', 'genesis_do_post_content' );

/** Remove the post image */
//remove_action( 'genesis_post_content', 'genesis_do_post_image' );

/** Add the featured image after post title */
//add_action( 'genesis_post_title', 'executive_product_grid' );

/*function executive_product_grid() {
    if ( has_post_thumbnail() ){
        echo '<div class="product-featured-image">';
        echo '<a href="' . get_permalink() .'" title="' . the_title_attribute( 'echo=0' ) . '">';
        echo get_the_post_thumbnail($thumbnail->ID, array(300,200) );
        echo get_the_post_thumbnail($thumbnail->ID, 'thumbnail' );
        echo '</a>';
        echo '</div>';
    }
}*/

/** Remove the post meta function */
//remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

//genesis();
