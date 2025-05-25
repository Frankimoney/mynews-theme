<?php
/**
 * Template part for displaying related articles
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

// Get current post categories and tags
$current_post_id = get_the_ID();
$categories = wp_get_post_categories($current_post_id);
$tags = wp_get_post_tags($current_post_id);

// Prepare arrays for category and tag IDs
$category_ids = array();
$tag_ids = array();

// Extract category IDs
if (!empty($categories)) {
    foreach ($categories as $cat) {
        $category_ids[] = $cat;
    }
}

// Extract tag IDs
if (!empty($tags)) {
    foreach ($tags as $tag) {
        $tag_ids[] = $tag->term_id;
    }
}

// Set up the query arguments
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 6, // Show 6 related articles
    'post__not_in' => array($current_post_id), // Exclude current post
    'orderby' => 'rand', // Random order for more variety
);

// If we have categories, use them for related content
if (!empty($category_ids)) {
    $args['category__in'] = $category_ids;
}

// If we have tags and no related posts by category, use them instead
$related_query = new WP_Query($args);
if ($related_query->post_count < 3 && !empty($tag_ids)) {
    // Not enough related posts by category, try tags instead
    $args['tag__in'] = $tag_ids;
    $args['category__in'] = array(); // Clear categories
    $related_query = new WP_Query($args);
}

// If still not enough related posts, get recent posts from any category
if ($related_query->post_count < 3) {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'post__not_in' => array($current_post_id),
        'orderby' => 'date', // Recent posts
        'order' => 'DESC'
    );
    $related_query = new WP_Query($args);
}

// Display related articles if we have any
if ($related_query->have_posts()) :
?>
<section class="related-articles my-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 fs-5"><?php esc_html_e('Related Articles', 'mynews'); ?></h3>
        </div>
        <div class="card-body py-4">
            <div class="row">
                <?php
                // Define column class based on number of posts
                $col_class = ($related_query->post_count >= 3) ? 'col-sm-6 col-lg-4' : 'col-sm-6';
                
                // Loop through each related post
                while ($related_query->have_posts()) :
                    $related_query->the_post();
                ?>
                <div class="<?php echo esc_attr($col_class); ?> mb-4">
                    <article class="related-post h-100">
                        <a href="<?php the_permalink(); ?>" class="d-block mb-2">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded')); ?>
                            <?php else : ?>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/placeholder.jpg" alt="<?php the_title_attribute(); ?>" class="img-fluid rounded">
                            <?php endif; ?>
                        </a>
                        <h4 class="h6 fw-bold">
                            <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        <div class="post-meta text-muted small mb-2">
                            <span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                        </div>
                    </article>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>
<?php
// Reset the post data to restore the main query
wp_reset_postdata();
endif; // End if have posts
?>