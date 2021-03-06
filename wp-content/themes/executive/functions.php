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
	$creds = 'Powered by <a href="http://dieter-king.tk/" title="Powered by wordpress">Wordpress</a>';
	return $creds;
}

/** Create portfolio/danh mục đầu tư custom post type */
add_action( 'init', 'executive_portfolio_post_type' );
function executive_portfolio_post_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Danh mục đầu tư', 'executive' ),
				'singular_name' => __( 'Danh mục đầu tư', 'executive' ),
			),
			'exclude_from_search' => true,
			'has_archive' => true,
			'hierarchical' => true,
			'menu_icon' => get_stylesheet_directory_uri() . '/images/icons/portfolio.png',
			'public' => true,
			'rewrite' => array( 'slug' => 'danh-muc-dau-tu' ),
			'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'comments', 'revisions', 'page-attributes', 'genesis-seo' ),
		)
	);
}

// Register Custom Post Type
function product_post_type() {

    $labels = array(
        'name'                  => _x( 'Products', 'Post Type General Name', 'executive' ),
        'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'executive' ),
        'menu_name'             => __( 'Sản phẩm', 'executive' ),
        'name_admin_bar'        => __( 'Sản phẩm', 'executive' ),
        'parent_item_colon'     => __( 'Sản phẩm cha:', 'executive' ),
        'all_items'             => __( 'Tất cả các sản phẩm', 'executive' ),
        'add_new_item'          => __( 'Thêm mới sản phẩm', 'executive' ),
        'add_new'               => __( 'Thêm sản phẩm', 'executive' ),
        'new_item'              => __( 'Sản phẩm mới', 'executive' ),
        'edit_item'             => __( 'Sửa sản phẩm', 'executive' ),
        'update_item'           => __( 'Cập nhật sản phẩm', 'executive' ),
        'view_item'             => __( 'Xem sản phẩm', 'executive' ),
        'search_items'          => __( 'Tìm kiếm sản phẩm', 'executive' ),
        'not_found'             => __( 'Không tìm thấy', 'executive' ),
        'not_found_in_trash'    => __( 'Không thấy trong thùng rác', 'executive' ),
        'items_list'            => __( 'Danh sách sản phẩm', 'executive' ),
        'items_list_navigation' => __( 'Chuyển hướng danh mục sản phẩm', 'executive' ),
        'filter_items_list'     => __( 'Lọc danh sách sản phẩm', 'executive' ),
    );
    $args = array(
        'label'                 => __( 'Product', 'executive' ),
        'description'           => __( 'Sản phẩm nhựa', 'executive' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => true,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
        'rewrite' => array( 'slug' => 'product' ),
    );
    register_post_type( 'product', $args );
}
add_action( 'init', 'product_post_type' );

// Register Custom Taxonomy
function product_catalog_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Catalogs', 'Taxonomy General Name', 'executive' ),
        'singular_name'              => _x( 'Catalog', 'Taxonomy Singular Name', 'executive' ),
        'menu_name'                  => __( 'Catalog', 'executive' ),
        'all_items'                  => __( 'All Catalogs', 'executive' ),
        'parent_item'                => __( 'Parent Catalog', 'executive' ),
        'parent_item_colon'          => __( 'Parent Catalog:', 'executive' ),
        'new_item_name'              => __( 'Catalog mới', 'executive' ),
        'add_new_item'               => __( 'Thêm Catalog mới', 'executive' ),
        'edit_item'                  => __( 'Sửa Catalog', 'executive' ),
        'update_item'                => __( 'Cập nhật Catalog', 'executive' ),
        'view_item'                  => __( 'Xem Catalog', 'executive' ),
        'separate_items_with_commas' => __( 'Separate publishers with commas', 'executive' ),
        'add_or_remove_items'        => __( 'Thêm hoặc xóa Catalog', 'executive' ),
        'choose_from_most_used'      => __( 'Choose from the most used publishers', 'executive' ),
        'popular_items'              => __( 'Catalog phổ biến', 'executive' ),
        'search_items'               => __( 'Tìm Catalog', 'executive' ),
        'not_found'                  => __( 'Không tìm thấy', 'executive' ),
        'items_list'                 => __( 'Danh sách Catalog', 'executive' ),
        'items_list_navigation'      => __( 'Catalog list navigation', 'executive' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'           => array( 'slug' => 'catalog' ),
        'query_var'         => true,
    );
    register_taxonomy( 'catalog', array( 'product' ), $args );
}
add_action( 'init', 'product_catalog_taxonomy' );

/** Change the number of portfolio items to be displayed (props Bill Erickson) */
add_action( 'pre_get_posts', 'executive_portfolio_items' );
function executive_portfolio_items( $query ) {

	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '12' );
	}

}

/** Add support for 3-column footer widgets */
/*add_theme_support( 'genesis-footer-widgets', 3 );*/

/** Register widget areas **/
genesis_register_sidebar( array(
	'id'			=> 'home-slider',
	'name'			=> __( 'Home - Slider', 'executive' ),
	'description'	=> __( 'This is the slider section on the home page.', 'executive' ),
) );
/** Add custom sidebar */
genesis_register_sidebar(array(
    'name'=>__('About Company sidebar','executive'),
    'id' => 'sidebar-about',
    'description' => __('This is an about sidebar','executive'),
/*    'before_widget' => '<div id="%1$s"><div class="widget %2$s">',
    'after_widget'  => "</div></div>\n",
    'before_title'  => '<h4><span>',
    'after_title'   => "</span></h4>\n"*/
));
/** Add the company text section */
//add_action( 'genesis_before_content_sidebar_wrap', 'custom_company_text' );
//add_action('genesis_before_content_sidebar_wrap','custom_company_text');
/*function custom_company_text(){
    if(!is_home())
        return;
    genesis_widget_area('company-text', array(
        'before' => '<div class="company-text widget-area">'
    ));
}*/

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
unregister_sidebar('home-cta');
unregister_sidebar('home-top');
unregister_sidebar('home-middle');

// Read more button
/*function sp_read_more_link(){
    return '... <br/><a class="more-link" href="'. the_permalink().'">[Continue Reading]</a>';
}*/
//add_filter('get_the_content_more_link','sp_read_more_link');
/* function executive_read_more(){
       return '<a class="btn btn--sm" target="_blank" href="'.get_permalink() .'"></a>';
    }
add_filter('excerpt_more', 'executive_read_more');*/

/**********************************
 *
 * Replace Header Site Title with Inline Logo
 *
 * @author AlphaBlossom / Tony Eppright
 * @link http://www.alphablossom.com/a-better-wordpress-genesis-responsive-logo-header/
 *
 * @edited by Sridhar Katakam
 * @link https://sridharkatakam.com/use-inline-logo-instead-background-image-genesis/
 *
 ************************************/
add_filter( 'genesis_seo_title', 'custom_header_inline_logo', 10, 3 );
function custom_header_inline_logo( $title, $inside, $wrap ) {

    $logo = '<img src="' . get_stylesheet_directory_uri() . '/images/logo.png" alt="' . esc_attr( get_bloginfo( 'name' ) ) . ' Homepage" width="200" height="100" />';

    $inside = sprintf( '<a href="%s">%s<span class="screen-reader-text">%s</span></a>', trailingslashit( home_url() ), $logo, get_bloginfo( 'name' ) );
   //echo genesis_get_seo_option( 'home_h1_on' );

    // Determine which wrapping tags to use
    //$wrap = genesis_is_root_page() && 'title' === genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : 'p';
    $wrap = 'h1';//return;
    // A little fallback, in case an SEO plugin is active
    //$wrap = genesis_is_root_page() && ! genesis_get_seo_option( 'home_h1_on' ) ? 'h1' : $wrap;

    // And finally, $wrap in h1 if HTML5 & semantic headings enabled
    $wrap = genesis_html5() && genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

    return sprintf( '<%1$s %2$s>%3$s</%1$s>', $wrap, genesis_attr( 'site-title' ), $inside );

}

