<?php
/**
 * Post Views Tracking
 *
 * Functions to track and display post view counts
 *
 * @package My_News
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get post view count
 *
 * @param int $post_id The post ID.
 * @return int The post view count.
 */
function mynews_get_post_views($post_id) {
    $count = get_post_meta($post_id, 'mynews_post_views_count', true);
    return ($count ? $count : 0);
}

/**
 * Set post view count
 *
 * @param int $post_id The post ID.
 * @return void
 */
function mynews_set_post_views($post_id) {
    // Don't track post views in admin, by bots, or on previews
    if (is_admin() || is_preview() || is_customize_preview()) {
        return;
    }
    
    // Check if user is a bot
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $bot_patterns = array(
        'bot', 'spider', 'crawler', 'slurp', 'BingPreview'
    );
    
    foreach ($bot_patterns as $bot_pattern) {
        if (stripos($user_agent, $bot_pattern) !== false) {
            return;
        }
    }

    $count = (int) get_post_meta($post_id, 'mynews_post_views_count', true);
    update_post_meta($post_id, 'mynews_post_views_count', ($count + 1));
}

/**
 * Track post views on single posts and pages
 */
function mynews_track_post_views() {
    if (is_singular('post')) {
        mynews_set_post_views(get_the_ID());
    }
}
add_action('wp_head', 'mynews_track_post_views');

/**
 * Add post views column to admin post list
 * 
 * @param array $columns The array of columns.
 * @return array Modified array of columns.
 */
function mynews_posts_column_views($columns) {
    $columns['post_views'] = __('Views', 'mynews');
    return $columns;
}
add_filter('manage_posts_columns', 'mynews_posts_column_views');

/**
 * Display post views in admin post list
 * 
 * @param string $column The name of the column.
 * @param int $post_id The post ID.
 */
function mynews_posts_custom_column_views($column, $post_id) {
    if ($column === 'post_views') {
        echo number_format(mynews_get_post_views($post_id));
    }
}
add_action('manage_posts_custom_column', 'mynews_posts_custom_column_views', 10, 2);

/**
 * Make post views column sortable in admin
 * 
 * @param array $columns The array of sortable columns.
 * @return array Modified array of sortable columns.
 */
function mynews_sortable_post_views_column($columns) {
    $columns['post_views'] = 'post_views';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'mynews_sortable_post_views_column');

/**
 * Modify query to sort by post views
 * 
 * @param WP_Query $query The WP_Query instance.
 */
function mynews_sort_by_post_views($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');
    
    if ($orderby == 'post_views') {
        $query->set('meta_key', 'mynews_post_views_count');
        $query->set('orderby', 'meta_value_num');
    }
}
add_action('pre_get_posts', 'mynews_sort_by_post_views');

/**
 * Shortcode for displaying post views
 * 
 * Usage: [mynews_post_views] or [mynews_post_views post_id="123"]
 * 
 * @param array $atts Shortcode attributes
 * @return string Shortcode output
 */
function mynews_post_views_shortcode($atts) {
    $atts = shortcode_atts(
        array(
            'post_id' => get_the_ID(),
            'prefix' => '',
            'suffix' => '',
        ), 
        $atts, 
        'mynews_post_views'
    );
    
    // Don't display if function doesn't exist
    if (!function_exists('mynews_get_post_views')) {
        return '';
    }
    
    $view_count = mynews_get_post_views($atts['post_id']);
    
    $output = '<span class="post-views-shortcode">';
    if (!empty($atts['prefix'])) {
        $output .= '<span class="post-views-prefix">' . esc_html($atts['prefix']) . '</span> ';
    }
    $output .= '<i class="bi bi-eye"></i> ';
    $output .= sprintf(
        _n('%s view', '%s views', $view_count, 'mynews'),
        number_format($view_count)
    );
    if (!empty($atts['suffix'])) {
        $output .= ' <span class="post-views-suffix">' . esc_html($atts['suffix']) . '</span>';
    }
    $output .= '</span>';
    
    return $output;
}
add_shortcode('mynews_post_views', 'mynews_post_views_shortcode');
