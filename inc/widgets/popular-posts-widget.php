<?php
/**
 * Popular and Trending Posts Widget
 *
 * @package My_News
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Popular Posts Widget Class
 */
class Mynews_Popular_Posts_Widget extends WP_Widget {

    /**
     * Sets up the widget
     */
    public function __construct() {
        parent::__construct(
            'mynews_popular_posts',                                  // Base ID
            esc_html__('MyNews: Popular & Trending Posts', 'mynews'), // Name
            array(
                'description' => esc_html__('Displays popular or trending posts based on views or comments.', 'mynews'),
                'classname'   => 'mynews-popular-posts-widget',
            )
        );
    }

    /**
     * Front-end display of the widget
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Popular Posts', 'mynews');
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);
        
        $post_count = !empty($instance['post_count']) ? absint($instance['post_count']) : 5;
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'popular';
        $time_range = !empty($instance['time_range']) ? $instance['time_range'] : 'all';
        $show_thumbnail = !empty($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_date = !empty($instance['show_date']) ? (bool) $instance['show_date'] : true;
        $show_excerpt = !empty($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        
        // Build query args based on widget settings
        $query_args = array(
            'posts_per_page'      => $post_count,
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );
        
        // Set time range if specified
        if ($time_range !== 'all') {
            $query_args['date_query'] = array(
                'after' => $this->get_date_query($time_range),
            );
        }
        
        // Set sorting based on display type
        if ($display_type === 'trending' || $display_type === 'commented') {
            // Sort by comment count for trending posts
            $query_args['orderby'] = 'comment_count';
            $query_args['order'] = 'DESC';
        } else {
            // Sort by view count for popular posts
            $query_args['meta_key'] = 'mynews_post_views_count';
            $query_args['orderby'] = 'meta_value_num';
            $query_args['order'] = 'DESC';
        }
        
        // Get posts
        $popular_posts = new WP_Query($query_args);
        
        echo $args['before_widget'];
        
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        if ($popular_posts->have_posts()) :
            // Widget HTML output
            ?>
            <div class="mynews-popular-posts">
                <ul class="mynews-posts-list">
                    <?php while ($popular_posts->have_posts()) : $popular_posts->the_post(); ?>
                        <li class="mynews-post-item">
                            <?php if ($show_thumbnail && has_post_thumbnail()) : ?>
                                <div class="mynews-post-thumb">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php the_post_thumbnail('mynews-thumbnail'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mynews-post-content">
                                <h4 class="mynews-post-title">
                                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h4>
                                
                                <?php if ($show_date || $display_type === 'popular' || $display_type === 'trending') : ?>
                                    <div class="mynews-post-meta">
                                        <?php if ($show_date) : ?>
                                            <span class="mynews-post-date">
                                                <i class="bi bi-calendar"></i>
                                                <?php echo get_the_date(); ?>
                                            </span>
                                        <?php endif; ?>
                                        
                                        <?php if ($display_type === 'popular') : ?>
                                            <span class="mynews-post-views">
                                                <i class="bi bi-eye"></i>
                                                <?php printf(
                                                    _n('%s view', '%s views', mynews_get_post_views(get_the_ID()), 'mynews'),
                                                    number_format(mynews_get_post_views(get_the_ID()))
                                                ); ?>
                                            </span>
                                        <?php elseif ($display_type === 'trending' || $display_type === 'commented') : ?>
                                            <span class="mynews-post-comments">
                                                <i class="bi bi-chat-dots"></i>
                                                <?php printf(
                                                    _n('%s comment', '%s comments', get_comments_number(), 'mynews'),
                                                    number_format(get_comments_number())
                                                ); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($show_excerpt) : ?>
                                    <div class="mynews-post-excerpt">
                                        <?php echo wp_trim_words(get_the_excerpt(), 15); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
            </div>
            <?php
            wp_reset_postdata();
        else :
            echo '<p>' . esc_html__('No posts found.', 'mynews') . '</p>';
        endif;
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Popular Posts', 'mynews');
        $post_count = !empty($instance['post_count']) ? absint($instance['post_count']) : 5;
        $display_type = !empty($instance['display_type']) ? $instance['display_type'] : 'popular';
        $time_range = !empty($instance['time_range']) ? $instance['time_range'] : 'all';
        $show_thumbnail = isset($instance['show_thumbnail']) ? (bool) $instance['show_thumbnail'] : true;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : true;
        $show_excerpt = isset($instance['show_excerpt']) ? (bool) $instance['show_excerpt'] : false;
        ?>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'mynews'); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" 
                   value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('display_type')); ?>">
                <?php esc_html_e('Display Type:', 'mynews'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('display_type')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('display_type')); ?>">
                <option value="popular" <?php selected($display_type, 'popular'); ?>>
                    <?php esc_html_e('Popular (by Views)', 'mynews'); ?>
                </option>
                <option value="commented" <?php selected($display_type, 'commented'); ?>>
                    <?php esc_html_e('Most Commented', 'mynews'); ?>
                </option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('time_range')); ?>">
                <?php esc_html_e('Time Range:', 'mynews'); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr($this->get_field_id('time_range')); ?>" 
                    name="<?php echo esc_attr($this->get_field_name('time_range')); ?>">
                <option value="all" <?php selected($time_range, 'all'); ?>>
                    <?php esc_html_e('All Time', 'mynews'); ?>
                </option>
                <option value="daily" <?php selected($time_range, 'daily'); ?>>
                    <?php esc_html_e('Today', 'mynews'); ?>
                </option>
                <option value="weekly" <?php selected($time_range, 'weekly'); ?>>
                    <?php esc_html_e('This Week', 'mynews'); ?>
                </option>
                <option value="monthly" <?php selected($time_range, 'monthly'); ?>>
                    <?php esc_html_e('This Month', 'mynews'); ?>
                </option>
            </select>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('post_count')); ?>">
                <?php esc_html_e('Number of posts to show:', 'mynews'); ?>
            </label>
            <input class="tiny-text" id="<?php echo esc_attr($this->get_field_id('post_count')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('post_count')); ?>" type="number" 
                   step="1" min="1" value="<?php echo esc_attr($post_count); ?>" size="3">
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_thumbnail); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_thumbnail')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_thumbnail')); ?>">
                <?php esc_html_e('Display post thumbnail', 'mynews'); ?>
            </label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_date); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_date')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_date')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_date')); ?>">
                <?php esc_html_e('Display post date', 'mynews'); ?>
            </label>
        </p>
        
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_excerpt); ?> 
                   id="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>" 
                   name="<?php echo esc_attr($this->get_field_name('show_excerpt')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_excerpt')); ?>">
                <?php esc_html_e('Display post excerpt', 'mynews'); ?>
            </label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['post_count'] = (!empty($new_instance['post_count'])) ? absint($new_instance['post_count']) : 5;
        $instance['display_type'] = (!empty($new_instance['display_type'])) ? sanitize_text_field($new_instance['display_type']) : 'popular';
        $instance['time_range'] = (!empty($new_instance['time_range'])) ? sanitize_text_field($new_instance['time_range']) : 'all';
        $instance['show_thumbnail'] = (!empty($new_instance['show_thumbnail'])) ? 1 : 0;
        $instance['show_date'] = (!empty($new_instance['show_date'])) ? 1 : 0;
        $instance['show_excerpt'] = (!empty($new_instance['show_excerpt'])) ? 1 : 0;
        
        return $instance;
    }

    /**
     * Get date query based on time range
     *
     * @param string $range Time range.
     * @return string Date string for date query.
     */
    private function get_date_query($range) {
        $date = '';
        
        switch ($range) {
            case 'daily':
                $date = '1 day ago';
                break;
            case 'weekly':
                $date = '1 week ago';
                break;
            case 'monthly':
                $date = '1 month ago';
                break;
            default:
                $date = '';
                break;
        }
        
        return $date;
    }
}
