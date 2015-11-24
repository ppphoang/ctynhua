<?php
/** Start the engine */
require_once( get_template_directory() . '/lib/init.php' );

/** Add theme color body class */
add_filter('body_class', 'add_category_class_single');
function add_category_class_single($classes){
 	
    $classes[] = 'executive-' . $_GET['color'];
 
    return $classes;
}

load_child_theme_textdomain( 'executive', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'executive' ) );

/** Child theme (do not remove) */
define( 'CHILD_THEME_NAME', __( 'Executive Theme', 'executive' ) );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/themes/executive' );

/** Add Viewport meta tag for mobile browsers */
add_action( 'genesis_meta', 'executive_add_viewport_meta_tag' );
function executive_add_viewport_meta_tag() {
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"/>';
}

/** Add support for custom header */
add_theme_support( 'genesis-custom-header', array(
	'width' 	=> 1140,
	'height' 	=> 100
) );

/** Add support for custom background */
add_theme_support( 'custom-background' );

/** Sets Content Width */
$content_width = apply_filters( 'content_width', 680, 680, 1020 );

/** Create additional color style options */
add_theme_support( 'genesis-style-selector', array(
	'executive-brown' 	=>	__( 'Brown', 'executive' ),
	'executive-green' 	=>	__( 'Green', 'executive' ),
	'executive-orange' 	=>	__( 'Orange', 'executive' ),
	'executive-purple' 	=>	__( 'Purple', 'executive' ),
	'executive-red' 	=>	__( 'Red', 'executive' ),
	'executive-teal' 	=>	__( 'Teal', 'executive' ),
) );

/** Unregister layout settings */
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

/** Unregister secondary sidebar */
unregister_sidebar( 'sidebar-alt' );

/** Add new image sizes */
add_image_size( 'featured', 285, 100, TRUE );
add_image_size( 'portfolio', 300, 200, TRUE );
add_image_size( 'slider', 1140, 445, TRUE );

/** Remove the site description */
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

/** Relocate the post info */
remove_action( 'genesis_before_post_content', 'genesis_post_info' );
add_action( 'genesis_before_post_title', 'genesis_post_info' );

/** Customize the post info function */
add_filter( 'genesis_post_info', 'post_info_filter' );
function post_info_filter($post_info) {
	if (!is_page()) {
	    $post_info = '
	    <div class=\'date-info\'>' .
	    	__('posted on', 'executive' ) .
		    ' [post_date format="F j, Y" before="<span class=\'date\'>" after="</span>"] ' .
		    __('by', 'executive' ) . ' [post_author_posts_link] [post_edit]
	    </div>
	    <div class="comments">
	    	[post_comments]
	    </div>';
	    return $post_info;
	}
}

/** Change the default comment callback */
add_filter( 'genesis_comment_list_args', 'executive_comment_list_args' );
function executive_comment_list_args( $args ) {
	$args['callback'] = 'executive_comment_callback';
	
	return $args;
}

/** Customize the comment section */
function executive_comment_callback( $comment, $args, $depth ) {

	$GLOBALS['comment'] = $comment; ?>

	<li <?php comment_class(); ?> id="comment-<?php comment_ID() ?>">

		<?php do_action( 'genesis_before_comment' ); ?>
		
		<div class="comment-header">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $size = $args['avatar_size'] ); ?>
				<?php printf( '<cite class="fn">%s</cite> <span class="says">%s:</span>', get_comment_author_link(), apply_filters( 'comment_author_says_text', __( 'says', 'executive' ) ) ); ?>
				<div class="comment-meta commentmetadata">
					<a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>"><?php printf( '%1$s ' . __('at', 'executive' ) . ' %2$s', get_comment_date(), get_comment_time() ); ?></a>
				<?php edit_comment_link( __( 'Edit', 'executive' ), g_ent( '&bull; ' ), '' ); ?>
				</div><!-- end .comment-meta -->
		 	</div><!-- end .comment-author -->			
		</div><!-- end .comment-header -->	

		<div class="comment-content">
			<?php if ($comment->comment_approved == '0') : ?>
				<p class="alert"><?php echo apply_filters( 'genesis_comment_awaiting_moderation', __( 'Your comment is awaiting moderation.', 'executive' ) ); ?></p>
			<?php endif; ?>

			<?php comment_text(); ?>
		</div><!-- end .comment-content -->

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div>

		<?php do_action( 'genesis_after_comment' );

	/** No ending </li> tag because of comment threading */

}

add_filter('genesis_footer_creds_text', 'footer_creds_filter');
function footer_creds_filter( $creds ) {
	$creds = 'Powered by <a href="http://visaonho.com/" title="Powered by wordpress">Wordpress</a>';
	return $creds;
}

/** Create portfolio custom post type */
add_action( 'init', 'executive_portfolio_post_type' );
function executive_portfolio_post_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'executive' ),
				'singular_name' => __( 'Portfolio', 'executive' ),
			),
			'exclude_from_search' => true,
			'has_archive' => true,
			'hierarchical' => true,
			'menu_icon' => get_stylesheet_directory_uri() . '/images/icons/portfolio.png',
			'public' => true,
			'rewrite' => array( 'slug' => 'portfolio' ),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
		)
	);
}

/** Change the number of portfolio items to be displayed (props Bill Erickson) */
add_action( 'pre_get_posts', 'executive_portfolio_items' );
function executive_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

/** Add support for 3-column footer widgets */
add_theme_support( 'genesis-footer-widgets', 3 );

/** Register widget areas **/
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> __( 'Home - Slider', 'executive' ),
	'description'	=> __( 'This is the slider section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-top',
	'name'			=> __( 'Home - Top', 'executive' ),
	'description'	=> __( 'This is the top section of the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-cta',
	'name'			=> __( 'Home - Call To Action', 'executive' ),
	'description'	=> __( 'This is the call to action section on the home page.', 'executive' ),
) );
genesis_register_sidebar( array(
	'id'			=> 'home-middle',
	'name'			=> __( 'Home - Middle', 'executive' ),
	'description'	=> __( 'This is the middle section of the home page.', 'executive' ),
) );