<?php
define ( 'HEAVYANALYTICS_IS_INSTALLED', 1 );
define ( 'HEAVYANALYTICS_VERSION', '0.1' );
define ( 'HEAVYANALYTICS_DB_VERSION', '1' );

require ( dirname(__FILE__) .'/heavyanalytics-classes.php' );
require ( dirname(__FILE__) .'/heavyanalytics-cssjs.php' );

/**
 * heavyanalytics_add_admin_menu()
 *
 * Add a WordPress wp-admin admin menu.
 */
function heavyanalytics_add_admin_menu() {

	if ( is_admin() != 'true' )
		return false;

	require ( dirname(__FILE__) .'/heavyanalytics-admin.php');
	
	$page_title = 'heavy-analytics';
	$menu_title = 'Heavy Analytics';
	$capability = 'manage_options';
	$menu_slug	= 'heavy-analytics';
	$function	= 'heavyanalytics_admin';
	$icon_url	= get_bloginfo('url') .'/wp-content/plugins/heavy-analytics/includes/images/heavy_admin_icon.gif';
	
	add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url );

	// $parent_slug = 'heavy-analytics';
	// $page_title  = 'heavy-analytics-posts';
	// $menu_title  = 'Posts';
	// $capability  = 'manage_options';
	// $menu_slug   = 'heavy-analytics-posts';
	// $function    = 'heavyanalytics_admin_posts';

	// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);

}
add_action( 'admin_menu', 'heavyanalytics_add_admin_menu' );

function toby_table_test() {	
	echo '<script type="text/javascript"> 
		//Load the table sorter script when the page is ready
		//<![CDATA[
		$(document).ready(function() 
			{ 
				$("#myTable").tablesorter({ 
					widgets: ["zebra"] 
				}); 
			}  
		); 
		//]]>
	</script>';
}
add_action( 'admin_print_styles', 'toby_table_test' );
?>