<?php
/**
 * Template part for displaying read next article
 * 
 * This shows a single featured article that the user should read next
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

// Get the current post ID
$current_post_id = get_the_ID();

// Get the current post's category
$categories = get_the_category($current_post_id);
$category_id = !empty($categories) ? $categories[0]->term_id : 0;

// First try to get a popular post from the same category
$args = array(
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'post__not_in' => array($current_post_id),
    'orderby' => 'meta_value_num', // Order by post views
    'meta_key' => 'post_views_count', // The meta key for post views
    'order' => 'DESC',
);

// If we have a category, add it to the query
if ($category_id) {
    $args['cat'] = $category_id;
}

// Get the post
$next_post_query = new WP_Query($args);

// If no popular post in the same category, try getting the most recent post in the same category
if (!$next_post_query->have_posts()) {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'post__not_in' => array($current_post_id),
        'cat' => $category_id,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    $next_post_query = new WP_Query($args);
}

// If still no post, get the most popular post from any category
if (!$next_post_query->have_posts()) {
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'post__not_in' => array($current_post_id),
        'orderby' => 'meta_value_num',
        'meta_key' => 'post_views_count',
        'order' => 'DESC'
    );
    $next_post_query = new WP_Query($args);
}

// If we have a next post to show
if ($next_post_query->have_posts()) :
    $next_post_query->the_post();
?>
<div class="read-next my-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 fs-5">
                <i class="bi bi-arrow-right-circle me-2"></i>
                <?php esc_html_e('Read Next', 'mynews'); ?>
            </h3>
        </div>
        <div class="row g-0">
            <?php if (has_post_thumbnail()) : ?>
            <div class="col-md-4">
                <a href="<?php the_permalink(); ?>" class="d-block h-100">
                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid rounded-start h-100 object-fit-cover')); ?>
                </a>
            </div>
            <div class="col-md-8">
            <?php else : ?>
            <div class="col-12">
            <?php endif; ?>
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="<?php the_permalink(); ?>" class="text-decoration-none">
                            <?php the_title(); ?>
                        </a>
                    </h4>
                    <div class="card-text mb-3">
                        <?php echo wp_trim_words(get_the_excerpt(), 30); ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="post-meta text-muted small">
                            <span><i class="bi bi-calendar3"></i> <?php echo get_the_date(); ?></span>
                            <?php if (function_exists('mynews_get_post_views')) : ?>
                                <span class="ms-2"><i class="bi bi-eye"></i> <?php echo number_format(mynews_get_post_views(get_the_ID())); ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="btn btn-sm btn-primary">
                            <?php esc_html_e('Read Article', 'mynews'); ?>
                            <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Reset post data
wp_reset_postdata();
endif;
?>