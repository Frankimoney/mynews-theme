<?php
/**
 * Template part for displaying ad containers
 *
 * @package My_News
 */

// Don't show ads if disabled globally
if (!get_theme_mod('mynews_enable_ads', true)) {
    return;
}

// Don't show ads to admin users if that setting is enabled
if (get_theme_mod('mynews_hide_ads_admin', false) && current_user_can('manage_options')) {
    return;
}

// Get placement ID and ensure it exists
$placement_id = isset($args['placement']) ? $args['placement'] : '';
if (empty($placement_id)) {
    return;
}

// Get placement-specific visibility setting
$placement_setting = 'mynews_enable_' . str_replace('-', '_', $placement_id) . '_ad';
if (!get_theme_mod($placement_setting, true)) {
    return;
}

// Get container classes
$classes = array('mynews-ad-container', 'ad-' . $placement_id);
if (!empty($args['classes'])) {
    $classes = array_merge($classes, (array) $args['classes']);
}

// Check for sticky sidebar ad
if ($placement_id === 'sidebar-top' && get_theme_mod('mynews_enable_sticky_sidebar_ad', false)) {
    $classes[] = 'ad-sticky';
}

// Check if this is a video ad placement
$is_video_ad = false;
if (get_theme_mod('mynews_enable_video_ads', false)) {
    $video_position = get_theme_mod('mynews_video_ad_position', 'after-content');
    $video_position_normalized = str_replace('-', '_', $video_position);
    $current_placement_normalized = str_replace('-', '_', $placement_id);
    
    if ($video_position_normalized === $current_placement_normalized) {
        $is_video_ad = true;
        $classes[] = 'video-ad-container';
    }
}

// Check for in-feed ad
if (isset($args['infeed']) && $args['infeed']) {
    $classes[] = 'ad-infeed';
}

// Get container ID
$container_id = !empty($args['id']) ? $args['id'] : 'mynews-ad-' . $placement_id;

// Get ad title - used for accessibility and can be hidden with CSS
$ad_title = !empty($args['title']) ? $args['title'] : __('Advertisement', 'mynews');
?>

<div id="<?php echo esc_attr($container_id); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
    <?php if (get_theme_mod('mynews_show_ad_labels', true)) : ?>
    <div class="ad-label">
        <span><?php echo esc_html($ad_title); ?></span>
    </div>
    <?php endif; ?>
      <div class="ad-content">
        <?php 
        // Dynamic sidebar renders here
        if (is_active_sidebar('ad-' . $placement_id)) {
            // Check if this is a video ad placement
            if ($is_video_ad) {
                $autoplay = get_theme_mod('mynews_video_autoplay', false) ? 'true' : 'false';
                echo '<div class="video-ad-wrapper" data-autoplay="' . esc_attr($autoplay) . '">';
            }
            
            dynamic_sidebar('ad-' . $placement_id);
            
            if ($is_video_ad) {
                echo '</div>';
            }
        } else {
            // Placeholder for admin users
            if (current_user_can('manage_options')) {
                echo '<div class="ad-placeholder">';
                
                if ($is_video_ad) {
                    echo '<p>' . sprintf(__('This is the "%s" video ad placement. Add a Custom HTML widget to the "%s Ad" widget area and insert your video ad code here.', 'mynews'), 
                        ucwords(str_replace('-', ' ', $placement_id)),
                        ucwords(str_replace('-', ' ', $placement_id))) . '</p>';
                } elseif (isset($args['infeed']) && $args['infeed']) {
                    echo '<p>' . __('In-Feed Ad Placement. Add widgets to the "In-Feed Ad" widget area to display ads here.', 'mynews') . '</p>';
                } else {
                    echo '<p>' . sprintf(__('This is the "%s" ad placement. Add widgets to the "%s Ad" widget area to display ads here.', 'mynews'), 
                        ucwords(str_replace('-', ' ', $placement_id)),
                        ucwords(str_replace('-', ' ', $placement_id))) . '</p>';
                }
                
                echo '</div>';
            }
        }
        ?>
    </div>
    
    <?php if ($is_video_ad && current_user_can('manage_options') && !is_active_sidebar('ad-' . $placement_id)): ?>
    <div class="video-ad-instructions">
        <details>
            <summary><?php _e('Video Ad Setup Instructions', 'mynews'); ?></summary>
            <div class="instruction-content">
                <p><?php _e('To set up video ads:', 'mynews'); ?></p>
                <ol>
                    <li><?php _e('Go to Appearance > Widgets', 'mynews'); ?></li>
                    <li><?php printf(__('Add a Custom HTML widget to the "%s Ad" widget area', 'mynews'), ucwords(str_replace('-', ' ', $placement_id))); ?></li>
                    <li><?php _e('Paste your video ad code (VAST, VPAID, or plain video embed)', 'mynews'); ?></li>
                    <li><?php _e('Save the widget', 'mynews'); ?></li>
                </ol>
                <p><?php _e('For best results, use responsive video player code.', 'mynews'); ?></p>
            </div>
        </details>
    </div>
    <?php endif; ?>
</div>
