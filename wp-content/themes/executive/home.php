<?php

add_action( 'genesis_meta', 'executive_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function executive_home_genesis_meta() {

	if ( is_active_sidebar( 'home-slider' ) || is_active_sidebar( 'home-top' ) || is_active_sidebar( 'home-cta' ) || is_active_sidebar( 'home-middle' ) ) {

		remove_action( 'genesis_loop', 'genesis_do_loop' );
		add_action( 'genesis_loop', 'executive_home_sections' );
		add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
		add_filter( 'body_class', 'executive_body_class' );
		add_action( 'genesis_after', 'executive_slider_excerpt_position' );

		/** Add body class to home page **/		
		function executive_body_class( $classes ) {
   			$classes[] = 'executive-home';
  			return $classes;
		}

		/** Moves the slider pager if the sidebars are active and the screen is wide enough */
		function executive_slider_excerpt_position() {
				?>
				<script type="text/javascript">
					 jQuery(document).ready(function() {
						if (jQuery(".slide-excerpt").length > 0) {
							jQuery(".flex-control-nav").addClass("nav-pos-excerpt");
						}
					});
				</script>
				<?php		
		}
	}
}



function executive_home_sections() {

	if ( is_active_sidebar( 'home-slider' ) || is_active_sidebar( 'home-top' ) || is_active_sidebar( 'home-cta' ) || is_active_sidebar( 'home-middle' ) ) {

		genesis_widget_area( 'home-slider', array(
			'before' => '<div class="home-slider widget-area">',
		) );

		genesis_widget_area( 'home-top', array(
			'before' => '<div class="home-top widget-area">',
		) );

		genesis_widget_area( 'home-cta', array(
			'before' => '<div class="home-cta widget-area">',
		) );

		genesis_widget_area( 'home-middle', array(
			'before' => '<div class="home-middle widget-area">',
		) );
		   
	}

}

genesis();