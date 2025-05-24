<?php
/**
 * Template part for displaying posts in grid layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 border-0 shadow-sm'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="post-thumbnail">
            <?php the_post_thumbnail('mynews-card', ['class' => 'card-img-top']); ?>
            <?php 
            // Display featured badge if post is featured
            if ( function_exists( 'mynews_is_post_featured' ) && mynews_is_post_featured() ) : ?>
                <span class="featured-badge"><?php _e('Featured', 'mynews'); ?></span>
            <?php endif; ?>
        </a>
    <?php endif; ?>
    
    <div class="card-body d-flex flex-column">
        <?php 
        // Display categories
        $categories_list = get_the_category_list('');
        if ($categories_list) {
            echo '<div class="post-categories mb-2">';
            echo $categories_list;
            echo '</div>';
        }
        ?>
        
        <header class="entry-header">
            <?php the_title('<h3 class="entry-title card-title h5"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
        </header>

        <div class="entry-meta text-muted small mb-2">
            <?php
            // Posted on
            echo '<span class="posted-on">';
            echo '<i class="bi bi-calendar3"></i> ';
            echo '<time class="entry-date published" datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
            echo '</span>';
            
            // Posted by
            echo '<span class="byline ms-3">';
            echo '<i class="bi bi-person"></i> ';
            echo '<span class="author vcard"><a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>';
            echo '</span>';
            ?>
        </div><!-- .entry-meta -->

        <div class="entry-content card-text">
            <?php the_excerpt(); ?>
        </div><!-- .entry-content -->        <footer class="entry-footer mt-auto pt-2">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-primary btn-sm read-more-link">
                <?php esc_html_e('Read More', 'mynews'); ?>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </footer><!-- .entry-footer -->
    </div><!-- .card-body -->
</article><!-- #post-## -->
