<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_News
 */

if ( ! is_active_sidebar( 'sidebar-1' ) && 
     ! is_active_sidebar( 'ad-sidebar-top' ) && 
     ! is_active_sidebar( 'ad-sidebar-bottom' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php 
	// Display top sidebar ad
	get_template_part( 'template-parts/ad-container', null, array(
		'placement' => 'sidebar-top',
		'title'     => esc_html__( 'Advertisement', 'mynews' ),
	) );
	
	// Display regular sidebar widgets
	dynamic_sidebar( 'sidebar-1' );
	
	// Display bottom sidebar ad
	get_template_part( 'template-parts/ad-container', null, array(
		'placement' => 'sidebar-bottom',
		'title'     => esc_html__( 'Advertisement', 'mynews' ),
	) );
	?>
</aside><!-- #secondary -->
