<?php
/**
 * heavy_analytics_add_css()
 *
 * Function to enqueue css files.
 */

function heavy_analytics_add_css() {
	global $wpdb;

	if ( is_admin() == 'true' ) {
	   wp_enqueue_style( 'heavy-analytics-css', plugins_url( '/heavy-analytics/includes/css/admin.css' ) );
	   wp_enqueue_style( 'googleapis-fonts-AllertaStencil', 'http://fonts.googleapis.com/css?family=Allerta+Stencil:regular' );
	   wp_enqueue_style( 'googleapis-fonts-DroidSans', 'http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold' );
	   wp_enqueue_style( 'introjs-css', plugins_url( '/heavy-analytics/includes/css/introjs.css' ) );
    }
	
}

/**
 * heavy_analytics_add_js()
 *
 * Function to enqueue js files.
 */

function heavy_analytics_add_js() {
	global $wpdb;

	if ( is_admin() == 'true' ) {
		wp_enqueue_script( 'flotscript', plugins_url( '/heavy-analytics/includes/js/flot/jquery.flot.min.js' ), array('jquery') );
		wp_enqueue_script( 'excanvas', plugins_url( '/heavy-analytics/includes/js/flot/excanvas.min.js' ), array('jquery') );
		wp_enqueue_script( 'flotselection', plugins_url( '/heavy-analytics/includes/js/flot/jquery.flot.selection.min.js' ), array('jquery') );
		wp_enqueue_script( 'introjs', plugins_url( '/heavy-analytics/includes/js/intro.js' ) );
	}

}
add_action( 'wp_print_scripts', 'heavy_analytics_add_js', 1 );
add_action( 'admin_print_styles',  'heavy_analytics_add_css', 1 );
?>
