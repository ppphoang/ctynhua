<?php
/**
 * The custom portfolio post type single post template
 */
 
 /** Force full width content layout */
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

/** Remove the breadcrumb navigation */
remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

/** Remove the post info function */
remove_action( 'genesis_before_post_title', 'genesis_post_info' );

/** Remove the author box on single posts */
remove_action( 'genesis_after_post', 'genesis_do_author_box_single' );

/** Remove the post meta function */
remove_action( 'genesis_after_post_content', 'genesis_post_meta' );

/** Remove the comments template */
remove_action( 'genesis_after_post', 'genesis_get_comments_template' );

genesis();