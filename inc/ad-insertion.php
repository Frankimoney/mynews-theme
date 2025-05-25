<?php
/**
 * Functions for inserting ads into post content
 *
 * @package My_News
 */

/**
 * Insert ad after nth paragraph of single post content
 *
 * @param string $content Post content
 * @return string Modified content with ad
 */
function mynews_insert_post_ads( $content ) {
    // Only on single posts with substantial content
    if ( ! is_single() || is_admin() || ! is_main_query() ) {
        return $content;
    }

    // Check global ad toggle setting
    if ( ! get_theme_mod( 'mynews_enable_ads', true ) ) {
        return $content;
    }

    // Don't show ads to admins if that setting is enabled
    if ( get_theme_mod( 'mynews_hide_ads_admin', false ) && current_user_can( 'manage_options' ) ) {
        return $content;
    }

    // Check mid content ad visibility setting
    if ( ! get_theme_mod( 'mynews_enable_mid_content_ad', true ) ) {
        return $content;
    }

    // Get paragraph count from customizer
    $paragraphs_before = get_theme_mod( 'mynews_paragraph_count_before_ad', 4 );
    
    // Split content into paragraphs
    $paragraphs = explode( '</p>', $content );
    
    // If less paragraphs than target, don't insert
    if ( count( $paragraphs ) < $paragraphs_before + 1 ) {
        return $content;
    }

    // Get mid content ad
    ob_start();
    get_template_part( 'template-parts/ad-container', null, array(
        'placement' => 'mid-content',
        'title' => esc_html__( 'Advertisement', 'mynews' ),
        'classes' => 'ad-in-content',
    ) );
    $ad = ob_get_clean();

    // Insert ad after desired paragraph
    $content_with_ads = '';
    
    for ( $i = 0; $i < count( $paragraphs ); $i++ ) {
        $content_with_ads .= $paragraphs[$i];
        
        if ( $i < count( $paragraphs ) - 1 ) {
            $content_with_ads .= '</p>';
        }

        // Add ad after the specified paragraph
        if ( $i === $paragraphs_before - 1 ) {
            $content_with_ads .= $ad;
        }
    }

    return $content_with_ads;
}
add_filter( 'the_content', 'mynews_insert_post_ads', 20 );
