<?php
/**
 * The template for displaying archive pages
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
                <header class="page-header mb-4">
                    <?php
                    the_archive_title('<h1 class="page-title">', '</h1>');
                    the_archive_description('<div class="archive-description lead text-muted">', '</div>');
                    ?>
                </header><!-- .page-header -->                <?php if ($blog_layout === 'grid') : ?>
                    <div class="row g-4">
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
                        endwhile;
                        ?>
                    </div>
                <?php else : ?>
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
                            
                        endwhile;
                        ?>
                    </div>
                <?php endif; ?>                <?php 
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
