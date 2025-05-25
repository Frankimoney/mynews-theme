<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

get_header();

// Get blog layout preference from customizer
$blog_layout = get_theme_mod('mynews_blog_layout', 'grid');
$posts_per_row = get_theme_mod('mynews_posts_per_row', '3');

// Calculate Bootstrap column class based on posts per row
// Using more specific breakpoints for better grid control
$column_class = 'col-sm-12 col-md-6 col-lg-4';
if ($posts_per_row == '2') {
    $column_class = 'col-sm-12 col-md-6';
} elseif ($posts_per_row == '4') {
    $column_class = 'col-sm-12 col-md-6 col-lg-3';
}
?>

<main id="primary" class="site-main container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php if (have_posts()) : ?>
                <?php if (is_home() && !is_front_page()) : ?>
                    <header class="page-header mb-4">
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                    </header>
                <?php endif; ?>                <?php if ($blog_layout === 'grid') : ?>
                    <div class="row g-4 mynews-posts-grid">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();
                            echo '<div class="' . esc_attr($column_class) . ' mb-4">';
                            
                            // Check for post format and use appropriate template
                            $format = get_post_format();
                            if ($format && in_array($format, array('video', 'audio'))) {
                                get_template_part('template-parts/content', 'grid-' . $format);
                            } else {
                                get_template_part('template-parts/content', 'grid');
                            }
                            
                            echo '</div>';
                            
                            // Check if we should display an in-feed ad
                            if (get_theme_mod('mynews_enable_infeed_ads', false)) {
                                $position = get_theme_mod('mynews_infeed_position', 3);
                                $repeat = get_theme_mod('mynews_infeed_repeat', true);
                                
                                if (($wp_query->current_post + 1) === $position || 
                                    ($repeat && ($wp_query->current_post + 1) > $position && 
                                    (($wp_query->current_post + 1 - $position) % $position === 0))) {
                                    echo '<div class="col-12">';
                                    get_template_part('template-parts/in-feed-ad', null, array('post_index' => $wp_query->current_post));
                                    echo '</div>';
                                }
                            }
                        endwhile;
                        ?>
                    </div>                <?php else : ?>
                    <div class="posts-list">
                        <?php
                        /* Start the Loop */
                        while (have_posts()) :
                            the_post();
                            
                            // Check for post format and use appropriate template
                            $format = get_post_format();
                            if ($format && in_array($format, array('video', 'audio'))) {
                                get_template_part('template-parts/content', 'list-' . $format);
                            } else {
                                get_template_part('template-parts/content', 'list');
                            }
                            
                            // Check if we should display an in-feed ad
                            if (get_theme_mod('mynews_enable_infeed_ads', false)) {
                                $position = get_theme_mod('mynews_infeed_position', 3);
                                $repeat = get_theme_mod('mynews_infeed_repeat', true);
                                
                                if (($wp_query->current_post + 1) === $position || 
                                    ($repeat && ($wp_query->current_post + 1) > $position && 
                                    (($wp_query->current_post + 1 - $position) % $position === 0))) {
                                    get_template_part('template-parts/in-feed-ad', null, array('post_index' => $wp_query->current_post));
                                }
                            }
                        endwhile;
                        ?>
                    </div>
                <?php endif; ?>
                
                <?php
                // AJAX Load More Button
                global $wp_query;
                if ($wp_query->max_num_pages > 1) : ?>
                    <div class="mynews-load-more-container text-center mt-5 mb-3">
                        <button id="mynews-load-more" class="btn btn-primary" 
                                data-layout="<?php echo esc_attr($blog_layout); ?>"
                                data-posts-per-row="<?php echo esc_attr($posts_per_row); ?>">
                            <?php echo esc_html__('Load More', 'mynews'); ?>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php 
                // Keep pagination as fallback with 'mynews_use_ajax_load_more' filter
                if (!apply_filters('mynews_use_ajax_load_more', true)) {
                    // Enhanced numeric pagination
                    if (function_exists('mynews_numeric_pagination')) {
                        mynews_numeric_pagination();
                    } else {
                        // Fallback to default pagination
                        echo '<div class="pagination-container mt-5">';
                        the_posts_pagination(array(
                            'mid_size' => 2,
                            'prev_text' => '<i class="bi bi-arrow-left"></i> ' . __('Previous', 'mynews'),
                            'next_text' => __('Next', 'mynews') . ' <i class="bi bi-arrow-right"></i>',
                            'screen_reader_text' => __('Posts navigation', 'mynews'),
                            'class' => 'd-flex justify-content-center',
                        ));
                        echo '</div>';
                    }
                }
                ?>

            <?php else : ?>
                <?php get_template_part('template-parts/content', 'none'); ?>
            <?php endif; ?>
        </div><!-- .col-lg-8 -->

        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div><!-- .col-lg-4 -->
    </div><!-- .row -->
</main><!-- #primary -->

<?php
get_footer();
