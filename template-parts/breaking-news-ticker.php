<?php
/**
 * Template part for displaying the breaking news ticker
 *
 * @package My_News
 */

// Query breaking news posts
$today = current_time('Y-m-d');

// Create meta query for expiry dates
$expiry_meta_query = array(
    'relation' => 'OR',
    // Posts with no expiry date
    array(
        'key' => '_breaking_news_expiry',
        'value' => '',
        'compare' => '='
    ),
    // Posts with expiry date in the future
    array(
        'key' => '_breaking_news_expiry',
        'value' => $today,
        'compare' => '>=',
        'type' => 'DATE'
    )
);

// Get breaking news from custom post type
$breaking_post_type_args = array(
    'post_type' => 'breaking_news',
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => $expiry_meta_query
);
$breaking_post_type = new WP_Query($breaking_post_type_args);

// Get regular posts marked as breaking news
$breaking_regular_posts_args = array(
    'post_type' => 'post',
    'posts_per_page' => 10,
    'orderby' => 'date',
    'order' => 'DESC',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => '_is_breaking_news',
            'value' => '1',
            'compare' => '='
        ),
        $expiry_meta_query
    )
);
$breaking_regular_posts = new WP_Query($breaking_regular_posts_args);

// Merge the results into a single array of posts
$breaking_items = array();

// Add custom post type items
if ($breaking_post_type->have_posts()) {
    while ($breaking_post_type->have_posts()) {
        $breaking_post_type->the_post();
        $post_id = get_the_ID();
        $linked_post_id = get_post_meta($post_id, '_linked_post_id', true);
        
        // If this breaking news is linked to an existing post, get that post's details
        if ($linked_post_id) {
            $linked_post = get_post($linked_post_id);
            if ($linked_post && $linked_post->post_status === 'publish') {
                $breaking_items[] = array(
                    'ID' => $post_id,
                    'title' => get_the_title($post_id), // Use the breaking news title
                    'permalink' => get_permalink($linked_post_id), // Link to the original post
                    'date' => get_the_date('U', $post_id),
                    'urgency' => get_post_meta($post_id, '_breaking_news_urgency', true) ?: 'normal',
                    'linked_post' => true
                );
            } else {
                // If the linked post doesn't exist or is not published, use the breaking news post itself
                $breaking_items[] = array(
                    'ID' => $post_id,
                    'title' => get_the_title(),
                    'permalink' => get_permalink(),
                    'date' => get_the_date('U'),
                    'urgency' => get_post_meta($post_id, '_breaking_news_urgency', true) ?: 'normal'
                );
            }
        } else {
            // Regular breaking news (not linked to a post)
            $breaking_items[] = array(
                'ID' => $post_id,
                'title' => get_the_title(),
                'permalink' => get_permalink(),
                'date' => get_the_date('U'),
                'urgency' => get_post_meta($post_id, '_breaking_news_urgency', true) ?: 'normal'
            );
        }
    }
    wp_reset_postdata();
}

// Add regular posts marked as breaking news
if ($breaking_regular_posts->have_posts()) {
    while ($breaking_regular_posts->have_posts()) {
        $breaking_regular_posts->the_post();
        $post_id = get_the_ID();
        $breaking_items[] = array(
            'ID' => $post_id,
            'title' => get_the_title(),
            'permalink' => get_permalink(),
            'date' => get_the_date('U'),
            'urgency' => get_post_meta($post_id, '_breaking_news_urgency', true) ?: 'normal',
            'is_from_post' => true
        );
    }
    wp_reset_postdata();
}

// Sort items by date (newest first)
usort($breaking_items, function($a, $b) {
    return $b['date'] - $a['date'];
});

// Filter items based on display setting
$display_option = get_theme_mod('mynews_breaking_news_display', 'all');

if ($display_option === 'custom_only') {
    // Filter to show only custom breaking news
    $breaking_items = array_filter($breaking_items, function($item) {
        return !isset($item['is_from_post']) || !$item['is_from_post'];
    });
} elseif ($display_option === 'posts_only') {
    // Filter to show only posts marked as breaking
    $breaking_items = array_filter($breaking_items, function($item) {
        return isset($item['is_from_post']) && $item['is_from_post'];
    });
}

// Get maximum items from customizer setting
$max_items = get_theme_mod('mynews_breaking_news_max_items', 10);
$breaking_items = array_slice($breaking_items, 0, $max_items);

// Debug messages removed

// Only display ticker if we have breaking news items
if (!empty($breaking_items)) :
    $dark_mode = get_theme_mod('mynews_enable_dark_mode', false);
    $ticker_class = $dark_mode ? 'mynews-breaking-news mynews-breaking-news-dark' : 'mynews-breaking-news';
    $ticker_bg_color = get_theme_mod('mynews_breaking_news_bg_color', '#f8f9fa');
    $label_bg_color = get_theme_mod('mynews_breaking_news_label_color', '#0d6efd');
    $ticker_label = get_theme_mod('mynews_breaking_news_label', __('Breaking', 'mynews'));    // Get customizer settings
    $ticker_speed = get_theme_mod('mynews_breaking_news_speed', 5000);
    $display_mode = get_theme_mod('mynews_breaking_news_display_mode', 'scroll');
    $scrolling_enabled = get_theme_mod('mynews_breaking_news_scrolling', true);
    $scrolling_duration = get_theme_mod('mynews_breaking_news_scroll_duration', 20000);
    $font_size = get_theme_mod('mynews_breaking_news_font_size', '0.95');
    
    // Add inline script to set the ticker settings
    wp_add_inline_script('mynews-breaking-news-ticker', 
        'var myNewsTickerSettings = { 
            speed: ' . absint($ticker_speed) . ',
            displayMode: "' . esc_js($display_mode) . '",
            scrolling: ' . ($scrolling_enabled && $display_mode === 'scroll' ? 'true' : 'false') . ',
            scrollDuration: ' . absint($scrolling_duration) . ',
            fontSize: "' . esc_js($font_size) . 'rem"
        };', 
        'before'
    );
?>
<div class="<?php echo esc_attr($ticker_class); ?> scrolling-ticker" style="background-color: <?php echo esc_attr($ticker_bg_color); ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="mynews-breaking-wrapper d-flex align-items-center">
                    <div class="mynews-breaking-label" style="background-color: <?php echo esc_attr($label_bg_color); ?>">
                        <?php echo esc_html($ticker_label); ?>
                    </div>
                    
                    <div class="mynews-ticker-content">
                        <?php 
                        $first_item = true;
                        foreach ($breaking_items as $item) : 
                            $active_class = $first_item ? 'active' : '';
                            $first_item = false;
                        ?>
                            <div class="mynews-ticker-item <?php echo esc_attr($item['urgency']); ?> <?php echo esc_attr($active_class); ?>">
                                <a href="<?php echo esc_url($item['permalink']); ?>">
                                    <?php echo esc_html($item['title']); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="mynews-ticker-controls">
                        <div class="mynews-ticker-control mynews-ticker-prev">
                            <i class="fas fa-angle-left"></i>
                        </div>
                        <div class="mynews-ticker-control mynews-ticker-pause">
                            <i class="fas fa-pause"></i>
                        </div>
                        <div class="mynews-ticker-control mynews-ticker-next">
                            <i class="fas fa-angle-right"></i>
                        </div>                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
