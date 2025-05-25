<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package My_News
 */

get_header();

// Get layout setting from customizer
$theme_layout = get_theme_mod('mynews_theme_layout', 'full-width');
$container_class = ($theme_layout === 'boxed') ? 'container' : 'container-fluid';

// The reading progress bar is now handled by inline JavaScript for better reliability
// We don't need to output it here anymore

// Output schema.org structured data for this article
if (function_exists('mynews_generate_article_schema')) {
    // Determine article type based on category
    $schema_type = 'Article';
    $post_id = get_the_ID();
    
    if (has_category('news', $post_id) || has_category('headlines', $post_id)) {
        $schema_type = 'NewsArticle';
    } else {
        $schema_type = 'BlogPosting';
    }
    
    add_action('wp_head', function() use ($schema_type) {
        echo mynews_generate_article_schema(null, $schema_type);
    });
}
?>

<main id="primary" class="site-main py-5">
    <?php 
    // Display header ad placement
    get_template_part('template-parts/ad-container', null, array(
        'placement' => 'header',
        'title' => esc_html__('Advertisement', 'mynews'),
    ));
    ?>
    
    <div class="<?php echo esc_attr($container_class); ?>">
        <div class="row">
            <div class="col-lg-8">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'mynews'); ?></a></li>
                        <?php 
                        // Add category
                        $categories = get_the_category();
                        if (!empty($categories)) :
                            $category = $categories[0]; ?>
                            <li class="breadcrumb-item"><a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"><?php echo esc_html($category->name); ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo wp_trim_words(get_the_title(), 5); ?></li>
                    </ol>
                </nav>
                
                <div class="single-post-wrapper bg-white p-4 rounded shadow-sm mb-5">
                    <?php
                    while (have_posts()) :
                        the_post();
                        
                        // Check for post format
                        $format = get_post_format();
                        if ($format && in_array($format, array('video', 'audio'))) {
                            get_template_part('template-parts/content', $format);
                        } else {
                            get_template_part('template-parts/content', get_post_type());
                        }
                        
                        // Add social share buttons after post content
                        get_template_part('template-parts/post-share');
                    endwhile; // End of the loop.
                    ?>
                </div>
                
                <div class="post-components bg-white p-4 rounded shadow-sm mb-5">
                    <?php
                    // Display post reactions
                    get_template_part('template-parts/post-reactions');
                    
                    // Display author box
                    get_template_part('template-parts/author-box');
                    ?>
                </div>
                
                <div class="post-recommendations bg-white p-4 rounded shadow-sm mb-5">
                    <?php
                    // Display read next recommendation
                    get_template_part('template-parts/read-next');
                    
                    // Display related articles
                    get_template_part('template-parts/related-articles');
                    ?>
                </div>
                
                <div class="post-navigation-container bg-white p-4 rounded shadow-sm mb-5">
                    <?php
                    // Enhanced post navigation with thumbnails and categories
                    if (function_exists('mynews_post_navigation')) {
                        echo mynews_post_navigation();
                    } else {
                        // Fallback to standard navigation
                        the_post_navigation(
                            array(
                                'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'mynews') . '</span> <span class="nav-title">%title</span>',
                                'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'mynews') . '</span> <span class="nav-title">%title</span>',
                            )
                        );
                    }
                    ?>
                </div>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    echo '<div class="comments-container bg-white p-4 rounded shadow-sm mb-5">';
                    comments_template();
                    echo '</div>';
                endif;
                ?>
            </div><!-- .col-lg-8 -->

            <div class="col-lg-4">
                <?php get_sidebar(); ?>
            </div><!-- .col-lg-4 -->
        </div><!-- .row -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
