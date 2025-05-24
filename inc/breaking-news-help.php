<?php
/**
 * Breaking News Help and Documentation
 *
 * @package My_News
 */

/**
 * Add help tab to Breaking News edit screen
 */
function mynews_breaking_news_add_help_tab() {
    $screen = get_current_screen();
    
    // Only add to breaking news post type
    if (!$screen || $screen->post_type !== 'breaking_news') {
        return;
    }
    
    $screen->add_help_tab(array(
        'id'      => 'mynews_breaking_news_help',
        'title'   => __('Breaking News Help', 'mynews'),
        'content' => '<h2>' . __('Using Breaking News', 'mynews') . '</h2>' .
                     '<p>' . __('Breaking News items appear in a ticker at the top of your homepage. You can create standalone breaking news items or link to existing posts.', 'mynews') . '</p>' .
                     '<h3>' . __('Options', 'mynews') . '</h3>' .
                     '<ul>' .
                     '<li><strong>' . __('Title:', 'mynews') . '</strong> ' . __('This is the text that will appear in the breaking news ticker.', 'mynews') . '</li>' .
                     '<li><strong>' . __('Urgency Level:', 'mynews') . '</strong> ' . __('Select Normal, Important, or Urgent. This affects the styling of the ticker item.', 'mynews') . '</li>' .
                     '<li><strong>' . __('Expiry Date:', 'mynews') . '</strong> ' . __('Optionally set a date after which this item will no longer appear in the ticker.', 'mynews') . '</li>' .
                     '<li><strong>' . __('Link to Existing Post:', 'mynews') . '</strong> ' . __('You can search for and select an existing post to link to. The ticker will link to that post but use the breaking news title.', 'mynews') . '</li>' .
                     '</ul>'
    ));
    
    $screen->add_help_tab(array(
        'id'      => 'mynews_breaking_news_examples',
        'title'   => __('Examples', 'mynews'),
        'content' => '<h2>' . __('Breaking News Examples', 'mynews') . '</h2>' .
                     '<h3>' . __('Standalone Breaking News', 'mynews') . '</h3>' .
                     '<p>' . __('Create a breaking news item with its own content:', 'mynews') . '</p>' .
                     '<ol>' .
                     '<li>' . __('Add a new Breaking News item', 'mynews') . '</li>' .
                     '<li>' . __('Enter a title like "Flash Flood Warning"', 'mynews') . '</li>' .
                     '<li>' . __('Add content with details about the flood warning', 'mynews') . '</li>' .
                     '<li>' . __('Set urgency level to "Urgent"', 'mynews') . '</li>' .
                     '<li>' . __('Set expiry date to when the warning ends', 'mynews') . '</li>' .
                     '<li>' . __('Publish', 'mynews') . '</li>' .
                     '</ol>' .
                     '<h3>' . __('Link to Existing Post', 'mynews') . '</h3>' .
                     '<p>' . __('Create a breaking news item that links to an existing post:', 'mynews') . '</p>' .
                     '<ol>' .
                     '<li>' . __('Add a new Breaking News item', 'mynews') . '</li>' .
                     '<li>' . __('Enter a title like "President Announces New Policy"', 'mynews') . '</li>' .
                     '<li>' . __('Under "Select Existing Post", search for your detailed policy article', 'mynews') . '</li>' .
                     '<li>' . __('Select the post from the search results', 'mynews') . '</li>' .
                     '<li>' . __('Set urgency level to "Important"', 'mynews') . '</li>' .
                     '<li>' . __('Publish', 'mynews') . '</li>' .
                     '</ol>'
    ));
}
add_action('current_screen', 'mynews_breaking_news_add_help_tab');

/**
 * Add admin notice for Breaking News on first use
 */
function mynews_breaking_news_admin_notices() {
    $screen = get_current_screen();
    
    if (!$screen || ($screen->post_type !== 'breaking_news' && $screen->post_type !== 'post')) {
        return;
    }
    
    // Only show once per user
    $user_id = get_current_user_id();
    if (get_user_meta($user_id, 'mynews_breaking_news_notice_dismissed', true)) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible mynews-breaking-news-notice">
        <h3><?php _e('Breaking News Feature', 'mynews'); ?></h3>
        
        <?php if ($screen->post_type === 'breaking_news') : ?>
            <p><?php _e('You can use Breaking News in two ways:', 'mynews'); ?></p>
            <ol>
                <li><?php _e('Create standalone breaking news items (you\'re doing this now)', 'mynews'); ?></li>
                <li><?php _e('Mark regular posts as breaking news (do this from the post editor)', 'mynews'); ?></li>
            </ol>
            <p><?php _e('Breaking news items will appear in a ticker at the top of your homepage. You can customize the ticker appearance in the Theme Customizer.', 'mynews'); ?></p>
        <?php else : ?>
            <p><?php _e('You can mark regular posts as breaking news using the "Breaking News Options" meta box.', 'mynews'); ?></p>
            <p><?php _e('Breaking news posts will appear in the ticker at the top of your homepage. You can also create dedicated breaking news items under the Breaking News menu.', 'mynews'); ?></p>
        <?php endif; ?>
        
        <p><a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=mynews_breaking_news_section')); ?>" class="button"><?php _e('Customize Breaking News Ticker', 'mynews'); ?></a></p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.mynews-breaking-news-notice .notice-dismiss', function() {
            $.ajax({
                url: ajaxurl,
                data: {
                    action: 'mynews_dismiss_breaking_news_notice',
                    security: '<?php echo wp_create_nonce("mynews-dismiss-notice-nonce"); ?>'
                }
            });
        });
    });
    </script>
    <?php
}
add_action('admin_notices', 'mynews_breaking_news_admin_notices');

/**
 * AJAX handler to dismiss the breaking news notice
 */
function mynews_dismiss_breaking_news_notice() {
    check_ajax_referer('mynews-dismiss-notice-nonce', 'security');
    
    $user_id = get_current_user_id();
    update_user_meta($user_id, 'mynews_breaking_news_notice_dismissed', true);
    
    wp_die();
}
add_action('wp_ajax_mynews_dismiss_breaking_news_notice', 'mynews_dismiss_breaking_news_notice');
