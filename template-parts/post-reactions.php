<?php
/**
 * Template part for displaying post reactions
 *
 * @package My_News
 */

// Default reactions
$reactions = array(
    'like' => array(
        'emoji' => '👍',
        'label' => __('Like', 'mynews'),
        'count' => 0
    ),
    'love' => array(
        'emoji' => '❤️',
        'label' => __('Love', 'mynews'),
        'count' => 0
    ),
    'haha' => array(
        'emoji' => '😂',
        'label' => __('Haha', 'mynews'),
        'count' => 0
    ),
    'wow' => array(
        'emoji' => '😮',
        'label' => __('Wow', 'mynews'),
        'count' => 0
    ),
    'sad' => array(
        'emoji' => '😢',
        'label' => __('Sad', 'mynews'),
        'count' => 0
    )
);

// Get post ID - check for custom post ID from shortcode first
$post_id = get_query_var('mynews_reaction_post_id', get_the_ID());
if (!$post_id || !get_post($post_id)) {
    return; // Exit if no valid post ID
}

// Get stored reactions from post meta or use function if available
if (function_exists('mynews_get_post_reactions')) {
    $stored_reactions = mynews_get_post_reactions($post_id);
    foreach ($reactions as $key => $data) {
        if (isset($stored_reactions[$key])) {
            $reactions[$key]['count'] = intval($stored_reactions[$key]);
        }
    }
} else {
    // Fallback to direct meta access
    $stored_reactions = get_post_meta($post_id, '_mynews_post_reactions', true);
    if (!empty($stored_reactions) && is_array($stored_reactions)) {
        foreach ($stored_reactions as $key => $count) {
            if (isset($reactions[$key])) {
                $reactions[$key]['count'] = intval($count);
            }
        }
    }
}

// Check if user has already reacted
$user_reaction = '';
if (is_user_logged_in()) {
    $user_id = get_current_user_id();
    if (function_exists('mynews_get_user_reaction')) {
        $user_reaction = mynews_get_user_reaction($user_id, $post_id);
    } else {
        // Fallback to direct meta access
        $user_reactions = get_user_meta($user_id, '_mynews_user_reactions', true);
        if (!empty($user_reactions) && isset($user_reactions[$post_id])) {
            $user_reaction = $user_reactions[$post_id];
        }
    }
}

// Get total reaction count for possible conditional display
$total_reactions = 0;
foreach ($reactions as $reaction) {
    $total_reactions += $reaction['count'];
}
?>

<div class="mynews-post-reactions" data-post-id="<?php echo esc_attr($post_id); ?>">
    <h4><?php _e('How did this make you feel?', 'mynews'); ?></h4>
    <div class="mynews-reaction-buttons">
        <?php foreach ($reactions as $key => $reaction) : ?>
            <button 
                class="mynews-reaction-btn <?php echo ($user_reaction === $key) ? 'reacted' : ''; ?>" 
                data-reaction="<?php echo esc_attr($key); ?>"
                aria-label="<?php echo esc_attr($reaction['label']); ?>"
                title="<?php echo esc_attr($reaction['label']); ?>"
            >
                <span class="mynews-reaction-emoji"><?php echo $reaction['emoji']; ?></span>
                <span class="mynews-reaction-label"><?php echo esc_html($reaction['label']); ?></span>
                <span class="mynews-reaction-count"><?php echo esc_html($reaction['count']); ?></span>
            </button>
        <?php endforeach; ?>
    </div>
    <?php if (!is_user_logged_in() && $total_reactions > 0) : ?>
        <div class="mynews-reaction-login-prompt">
            <small><?php printf(
                __('Sign in to add your reaction. <a href="%s">Log in</a>', 'mynews'),
                esc_url(wp_login_url(get_permalink()))
            ); ?></small>
        </div>
    <?php endif; ?>
</div>
