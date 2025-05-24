<?php
/**
 * Template part for displaying posts in list layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card mb-4 border-0 shadow-sm'); ?>>
    <div class="row g-0">
        <?php if (has_post_thumbnail()) : ?>
            <div class="col-md-4">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                    <?php the_post_thumbnail('mynews-featured-medium', ['class' => 'img-fluid rounded-start h-100 object-fit-cover']); ?>
                    <?php 
                    // Display featured badge if post is featured
                    if ( function_exists( 'mynews_is_post_featured' ) && mynews_is_post_featured() ) : ?>
                        <span class="featured-badge"><?php _e('Featured', 'mynews'); ?></span>
                    <?php endif; ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="<?php echo has_post_thumbnail() ? 'col-md-8' : 'col-12'; ?>">
            <div class="card-body">
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
                    <?php the_title('<h3 class="entry-title card-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
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
                    
                    // Comments if enabled
                    if (comments_open()) {
                        echo '<span class="comments-link ms-3">';
                        echo '<i class="bi bi-chat"></i> ';
                        comments_popup_link(
                            __('Leave a comment', 'mynews'),
                            __('1 Comment', 'mynews'),
                            __('% Comments', 'mynews')
                        );
                        echo '</span>';
                    }
                    ?>
                </div><!-- .entry-meta -->

                <div class="entry-content card-text">
                    <?php the_excerpt(); ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-primary btn-sm read-more-link">
                        <?php esc_html_e('Read More', 'mynews'); ?>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </footer><!-- .entry-footer -->
            </div><!-- .card-body -->
        </div><!-- .col -->
    </div><!-- .row -->
</article><!-- #post-## -->
