<?php
/**
 * Popular Reactions Widget
 *
 * @package My_News
 */

/**
 * Popular Reactions widget class
 */
class MyNews_Popular_Reactions_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'mynews_popular_reactions', // Base ID
            esc_html__('MyNews: Popular Reactions', 'mynews'), // Name
            array('description' => esc_html__('Display posts with most reactions', 'mynews')) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : esc_html__('Most Reactions', 'mynews');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $reaction_type = !empty($instance['reaction_type']) ? $instance['reaction_type'] : 'all';

        echo $args['before_title'] . esc_html($title) . $args['after_title'];
        
        // Get posts with reactions
        $popular_posts = $this->get_posts_with_reactions($number, $reaction_type);
        
        if (!empty($popular_posts)) {
            echo '<ul class="mynews-popular-reactions-list">';
            
            foreach ($popular_posts as $post) {
                $reaction_count = $post['reaction_count'];
                $reaction_type_display = ($reaction_type !== 'all') ? $this->get_reaction_emoji($reaction_type) : '';
                
                echo '<li class="mynews-popular-reaction-item">';
                echo '<a href="' . esc_url(get_permalink($post['ID'])) . '">';
                
                if (has_post_thumbnail($post['ID'])) {
                    echo '<div class="popular-reaction-thumbnail">';
                    echo get_the_post_thumbnail($post['ID'], 'thumbnail', array('class' => 'img-fluid'));
                    echo '</div>';
                }
                
                echo '<div class="popular-reaction-content">';
                echo '<h5 class="popular-reaction-title">' . esc_html(get_the_title($post['ID'])) . '</h5>';
                
                echo '<div class="popular-reaction-meta">';
                echo '<span class="reaction-count">';
                if ($reaction_type_display) {
                    echo $reaction_type_display . ' ';
                }
                echo esc_html($reaction_count) . ' ' . _n('reaction', 'reactions', $reaction_count, 'mynews');
                echo '</span>';
                echo '</div>'; // .popular-reaction-meta
                
                echo '</div>'; // .popular-reaction-content
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
        } else {
            echo '<p>' . esc_html__('No posts with reactions found.', 'mynews') . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Most Reactions', 'mynews');
        $number = !empty($instance['number']) ? absint($instance['number']) : 5;
        $reaction_type = !empty($instance['reaction_type']) ? $instance['reaction_type'] : 'all';
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'mynews'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('number')); ?>"><?php esc_html_e('Number of posts to show:', 'mynews'); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('number')); ?>" name="<?php echo esc_attr($this->get_field_name('number')); ?>" type="number" step="1" min="1" value="<?php echo esc_attr($number); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('reaction_type')); ?>"><?php esc_html_e('Reaction type:', 'mynews'); ?></label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('reaction_type')); ?>" name="<?php echo esc_attr($this->get_field_name('reaction_type')); ?>">
                <option value="all" <?php selected($reaction_type, 'all'); ?>><?php esc_html_e('All reactions', 'mynews'); ?></option>
                <option value="like" <?php selected($reaction_type, 'like'); ?>><?php esc_html_e('👍 Like', 'mynews'); ?></option>
                <option value="love" <?php selected($reaction_type, 'love'); ?>><?php esc_html_e('❤️ Love', 'mynews'); ?></option>
                <option value="haha" <?php selected($reaction_type, 'haha'); ?>><?php esc_html_e('😂 Haha', 'mynews'); ?></option>
                <option value="wow" <?php selected($reaction_type, 'wow'); ?>><?php esc_html_e('😮 Wow', 'mynews'); ?></option>
                <option value="sad" <?php selected($reaction_type, 'sad'); ?>><?php esc_html_e('😢 Sad', 'mynews'); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['number'] = (!empty($new_instance['number'])) ? absint($new_instance['number']) : 5;
        $instance['reaction_type'] = (!empty($new_instance['reaction_type'])) ? sanitize_text_field($new_instance['reaction_type']) : 'all';

        return $instance;
    }

    /**
     * Get posts with the most reactions
     *
     * @param int $number Number of posts to get
     * @param string $reaction_type Type of reaction to filter by, or 'all'
     * @return array Array of posts with reaction counts
     */
    private function get_posts_with_reactions($number, $reaction_type) {
        global $wpdb;
        
        // Get all posts with reaction meta
        $meta_key = '_mynews_post_reactions';
        $posts_with_reactions = $wpdb->get_results(
            "SELECT post_id, meta_value FROM $wpdb->postmeta 
            WHERE meta_key = '$meta_key' 
            ORDER BY post_id DESC", 
            ARRAY_A
        );
        
        if (empty($posts_with_reactions)) {
            return array();
        }
        
        // Process the results
        $processed_posts = array();
        foreach ($posts_with_reactions as $post_data) {
            $post_id = $post_data['post_id'];
            $reactions = maybe_unserialize($post_data['meta_value']);
            
            // Skip if not an array or no reactions
            if (!is_array($reactions) || empty($reactions)) {
                continue;
            }
            
            // Get the post if valid
            $post = get_post($post_id);
            if (!$post || $post->post_status !== 'publish' || $post->post_type !== 'post') {
                continue;
            }
            
            // Calculate total reaction count or specific reaction count
            $count = 0;
            if ($reaction_type === 'all') {
                foreach ($reactions as $reaction_count) {
                    $count += intval($reaction_count);
                }
            } else {
                $count = isset($reactions[$reaction_type]) ? intval($reactions[$reaction_type]) : 0;
            }
            
            // Only add if there are reactions
            if ($count > 0) {
                $processed_posts[] = array(
                    'ID' => $post_id,
                    'reaction_count' => $count
                );
            }
        }
        
        // Sort by reaction count
        usort($processed_posts, function($a, $b) {
            return $b['reaction_count'] - $a['reaction_count'];
        });
        
        // Limit to requested number
        return array_slice($processed_posts, 0, $number);
    }
    
    /**
     * Get emoji for a reaction type
     *
     * @param string $type Reaction type
     * @return string Emoji
     */
    private function get_reaction_emoji($type) {
        $emojis = array(
            'like' => '👍',
            'love' => '❤️',
            'haha' => '😂',
            'wow' => '😮',
            'sad' => '😢'
        );
        
        return isset($emojis[$type]) ? $emojis[$type] : '';
    }
}

/**
 * Register the Popular Reactions Widget
 */
function mynews_register_popular_reactions_widget() {
    register_widget('MyNews_Popular_Reactions_Widget');
}
add_action('widgets_init', 'mynews_register_popular_reactions_widget');
