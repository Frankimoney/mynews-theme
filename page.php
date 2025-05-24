<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

get_header();

// Get layout setting from customizer
$theme_layout = get_theme_mod('mynews_theme_layout', 'full-width');
$container_class = ($theme_layout === 'boxed') ? 'container' : 'container-fluid';

// Output schema.org structured data for this page
if (function_exists('mynews_generate_page_schema')) {
    add_action('wp_head', function() {
        echo mynews_generate_page_schema();
    });
}

// Check if this is a FAQ page and add FAQ schema
if (function_exists('mynews_generate_faq_schema')) {
    add_action('wp_head', function() {
        echo mynews_generate_faq_schema();
    });
}
?>

<main id="primary" class="site-main py-5">
    <div class="<?php echo esc_attr($container_class); ?>">
        <div class="row">            <div class="col-lg-8">
                <!-- Breadcrumb Navigation -->
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'mynews'); ?></a></li>
                        <?php 
                        // Add parent page if exists
                        $parent_id = wp_get_post_parent_id(get_the_ID());
                        if ($parent_id) : ?>
                            <li class="breadcrumb-item"><a href="<?php echo esc_url(get_permalink($parent_id)); ?>"><?php echo get_the_title($parent_id); ?></a></li>
                        <?php endif; ?>
                        <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                    </ol>
                </nav>
                
                <?php
                while (have_posts()) :
                    the_post();

                    get_template_part('template-parts/content', 'page');

                    // If comments are open or we have at least one comment, load up the comment template
                    if (comments_open() || get_comments_number()) :
                        comments_template();
                    endif;

                endwhile; // End of the loop.
                ?>
            </div><!-- .col-lg-8 -->            <div class="col-lg-4">
                <?php 
                // Display child page navigation if available
                if (function_exists('mynews_has_children') && function_exists('mynews_child_pages_nav')) {
                    global $post;
                    if (mynews_has_children() || wp_get_post_parent_id($post->ID)) {
                        echo mynews_child_pages_nav();
                    }
                }
                // Display regular sidebar
                get_sidebar(); 
                ?>
            </div><!-- .col-lg-4 -->
        </div><!-- .row -->
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();
