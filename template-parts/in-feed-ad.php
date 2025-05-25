<?php
/**
 * Template part for displaying in-feed ads
 * 
 * Used when in-feed ads are enabled in archive pages
 *
 * @package My_News
 */

// Don't show ads if disabled globally
if (!get_theme_mod('mynews_enable_ads', true) || !get_theme_mod('mynews_enable_infeed_ads', false)) {
    return;
}

// Don't show ads to admin users if that setting is enabled
if (get_theme_mod('mynews_hide_ads_admin', false) && current_user_can('manage_options')) {
    return;
}

// Get current post index
global $wp_query;
$post_index = isset($args['post_index']) ? $args['post_index'] : $wp_query->current_post;
?>

<div class="mynews-infeed-ad-wrapper">
    <div class="row">
        <div class="col-12">
            <?php
            // Display in-feed ad
            get_template_part('template-parts/ad-container', null, array(
                'placement' => 'infeed',
                'infeed' => true,
                'title' => esc_html__('Advertisement', 'mynews'),
                'id' => 'mynews-ad-infeed-' . $post_index,
                'classes' => array('ad-infeed'),
            ));
            ?>
        </div>
    </div>
</div>
