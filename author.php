<?php
/**
 * The template for displaying author archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#author-display
 *
 * @package My_News
 */

get_header();

// Get blog layout preference from customizer
$blog_layout = get_theme_mod('mynews_blog_layout', 'grid');
$posts_per_row = get_theme_mod('mynews_posts_per_row', '3');

// Calculate Bootstrap column class based on posts per row
$column_class = 'col-sm-12 col-md-6 col-lg-4';
if ($posts_per_row == '2') {
    $column_class = 'col-sm-12 col-md-6';
} elseif ($posts_per_row == '4') {
    $column_class = 'col-sm-12 col-md-6 col-lg-3';
}

// Get author data
$curauth = get_queried_object();
?>

<main id="primary" class="site-main container py-5">
    <div class="row">
        <div class="col-lg-8">
            <?php if (have_posts()) : ?>
                <header class="author-header mb-4">
                    <div class="author-box card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <?php 
                                    $avatar = get_avatar($curauth->ID, 150, '', $curauth->display_name, array('class' => 'img-fluid rounded-circle author-avatar mb-3')); 
                                    echo $avatar;
                                    ?>
                                </div>
                                <div class="col-md-9">
                                    <h1 class="author-title"><?php echo esc_html($curauth->display_name); ?></h1>
                                    
                                    <?php if (!empty($curauth->description)) : ?>
                                        <div class="author-bio">
                                            <?php echo wpautop(wp_kses_post($curauth->description)); ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="author-meta">
                                        <?php if (!empty($curauth->user_email)) : ?>
                                            <div><i class="bi bi-envelope"></i> 
                                                <a href="mailto:<?php echo esc_attr($curauth->user_email); ?>">
                                                    <?php echo esc_html($curauth->user_email); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($curauth->user_url)) : ?>
                                            <div><i class="bi bi-globe"></i> 
                                                <a href="<?php echo esc_url($curauth->user_url); ?>" target="_blank">
                                                    <?php echo esc_html($curauth->user_url); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="author-social mt-3">
                                        <?php
                                        // Display social media links if they exist
                                        $social_platforms = array(
                                            'twitter' => array('icon' => 'bi-twitter-x', 'label' => 'Twitter/X'),
                                            'facebook' => array('icon' => 'bi-facebook', 'label' => 'Facebook'),
                                            'instagram' => array('icon' => 'bi-instagram', 'label' => 'Instagram'),
                                            'linkedin' => array('icon' => 'bi-linkedin', 'label' => 'LinkedIn'),
                                            'youtube' => array('icon' => 'bi-youtube', 'label' => 'YouTube'),
                                            'github' => array('icon' => 'bi-github', 'label' => 'GitHub')
                                        );
                                        
                                        foreach ($social_platforms as $platform => $details) :
                                            $platform_url = get_the_author_meta($platform . '_profile', $curauth->ID);
                                            if (!empty($platform_url)) :
                                            ?>
                                                <a href="<?php echo esc_url($platform_url); ?>" class="btn btn-sm btn-outline-primary me-2 mb-2" target="_blank" rel="noopener noreferrer">
                                                    <i class="bi <?php echo esc_attr($details['icon']); ?>"></i>
                                                    <span class="ms-1"><?php echo esc_html($details['label']); ?></span>
                                                </a>
                                            <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <h2 class="author-posts-title mt-4">
                        <?php
                        printf(
                            /* translators: %s: Author name */
                            esc_html__('Articles by %s', 'mynews'),
                            '<span class="author-name">' . esc_html($curauth->display_name) . '</span>'
                        );
                        ?>
                        <span class="badge bg-secondary ms-2"><?php echo esc_html(count_user_posts($curauth->ID)); ?></span>
                    </h2>
                </header>
                
                <?php if ($blog_layout === 'grid') : ?>
                    <div class="row row-cols-1 row-cols-md-<?php echo esc_attr($posts_per_row); ?> g-4">
                <?php endif; ?>
                
                <?php
                /* Start the Loop */
                while (have_posts()) :
                    the_post();

                    /*
                     * Include the Post-Type-specific template for the content.
                     */
                    if ($blog_layout === 'grid') :
                        echo '<div class="' . esc_attr($column_class) . ' mb-4">';
                        get_template_part('template-parts/content-grid');
                        echo '</div>';
                    elseif ($blog_layout === 'list') :
                        get_template_part('template-parts/content-list');
                    else :
                        get_template_part('template-parts/content');
                    endif;

                endwhile;
                ?>

                <?php if ($blog_layout === 'grid') : ?>
                    </div><!-- .row -->
                <?php endif; ?>

                <?php
                // Use Bootstrap pagination
                mynews_bootstrap_pagination();

            else :
                get_template_part('template-parts/content', 'none');
            endif;
            ?>
        </div><!-- .col-lg-8 -->

        <div class="col-lg-4">
            <?php get_sidebar(); ?>
        </div><!-- .col-lg-4 -->
    </div><!-- .row -->
</main><!-- #primary -->

<?php
/**
 * Outputs Bootstrap-styled pagination for WordPress archives.
 */
if ( ! function_exists( 'mynews_bootstrap_pagination' ) ) {
    function mynews_bootstrap_pagination() {
        global $wp_query;
        $big = 999999999; // need an unlikely integer
        $pages = paginate_links( array(
            'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
            'format'    => '?paged=%#%',
            'current'   => max( 1, get_query_var( 'paged' ) ),
            'total'     => $wp_query->max_num_pages,
            'type'      => 'array',
            'prev_text' => '&laquo;',
            'next_text' => '&raquo;',
        ) );
        if ( is_array( $pages ) ) {
            echo '<nav aria-label="Page navigation"><ul class="pagination justify-content-center my-4">';
            foreach ( $pages as $page ) {
                if ( strpos( $page, 'current' ) !== false ) {
                    echo '<li class="page-item active">' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
                } else {
                    echo '<li class="page-item">' . str_replace( 'page-numbers', 'page-link', $page ) . '</li>';
                }
            }
            echo '</ul></nav>';
        }
    }
}
get_footer();
