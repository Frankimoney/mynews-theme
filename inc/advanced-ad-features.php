<?php
/**
 * Advanced Ad Features - In-feed Ads, Sticky Ads, and Video Ads
 *
 * @package My_News
 */

/**
 * Register widget area for in-feed ads
 */
function mynews_register_advanced_ad_widgets() {
    register_sidebar(
        array(
            'name'          => esc_html__( 'In-Feed Ad', 'mynews' ),
            'id'            => 'ad-infeed',
            'description'   => esc_html__( 'Add ad code here for in-feed placement on archive, category, and home pages. Appears between posts in the feed.', 'mynews' ),
            'before_widget' => '<div id="%1$s" class="ad-widget ad-widget-infeed %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="visually-hidden">',
            'after_title'   => '</h3>',
        )
    );
}
add_action( 'widgets_init', 'mynews_register_advanced_ad_widgets' );

/**
 * Insert in-feed ads between posts in archive pages
 *
 * @param string $template_part_content The template content
 * @param string $template_part The template part name
 * @param array $args Template args
 * @return string Modified template content
 */
function mynews_insert_infeed_ads( $template_part_content, $template_part, $args ) {
    // Check if we're in the main query and displaying archive or home
    if ( ! is_main_query() || ! ( is_archive() || is_home() || is_search() ) ) {
        return $template_part_content;
    }
    
    // Check if in-feed ads are enabled
    if ( ! get_theme_mod( 'mynews_enable_infeed_ads', false ) ) {
        return $template_part_content;
    }
    
    // Check if we're rendering a content template
    if ( strpos( $template_part, 'content' ) === false || strpos( $template_part, 'none' ) !== false ) {
        return $template_part_content;
    }
    
    // Get in-feed position from customizer
    $position = get_theme_mod( 'mynews_infeed_position', 3 );
    
    // Get the current post number
    global $wp_query;
    $current_post = $wp_query->current_post;
    
    // Calculate if we should place an ad
    $repeat = get_theme_mod( 'mynews_infeed_repeat', true );
    $should_show_ad = false;
    
    if ( $current_post + 1 === $position ) {
        $should_show_ad = true;
    } elseif ( $repeat && $current_post + 1 > $position && ( ( $current_post + 1 - $position ) % $position === 0 ) ) {
        $should_show_ad = true;
    }
    
    // Insert an ad if needed
    if ( $should_show_ad && is_active_sidebar( 'ad-infeed' ) ) {
        ob_start();
        get_template_part( 'template-parts/ad-container', null, array(
            'placement' => 'infeed',
            'infeed' => true,
            'title' => esc_html__( 'Advertisement', 'mynews' ),
            'id' => 'mynews-ad-infeed-' . $current_post,
        ) );
        $ad = ob_get_clean();
        
        // Append ad after post content
        $template_part_content .= $ad;
    }
    
    return $template_part_content;
}
add_filter( 'template_part_content', 'mynews_insert_infeed_ads', 10, 3 );

/**
 * Initialize video ads functionality
 */
function mynews_video_ads_init() {
    // Only load scripts if video ads are enabled
    if ( get_theme_mod( 'mynews_enable_video_ads', false ) ) {
        wp_enqueue_script( 'mynews-video-ads', get_template_directory_uri() . '/assets/js/video-ad-handler.js', array( 'jquery' ), MYNEWS_VERSION, true );
        
        // Pass localized data to script
        wp_localize_script( 'mynews-video-ads', 'myNewsVideoAds', array(
            'autoplay' => get_theme_mod( 'mynews_video_autoplay', false ),
            'position' => get_theme_mod( 'mynews_video_ad_position', 'after-content' ),
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'mynews_video_ads_init' );
