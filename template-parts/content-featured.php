<?php
/**
 * Template part for displaying featured post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('featured-post card border-0 shadow mb-4'); ?>>
    <div class="row g-0">
        <?php if (has_post_thumbnail()) : ?>
        <div class="col-md-6">
            <a href="<?php the_permalink(); ?>" class="featured-thumbnail h-100">
                <?php the_post_thumbnail('large', ['class' => 'img-fluid h-100 w-100 object-fit-cover rounded-start']); ?>
                <span class="featured-badge position-absolute top-0 start-0 bg-danger text-white px-3 py-1 m-3 rounded-pill">
                    <?php _e('Featured', 'mynews'); ?>
                </span>
            </a>
        </div>
        <div class="col-md-6">
        <?php else : ?>
        <div class="col-12">
        <?php endif; ?>
            <div class="card-body d-flex flex-column h-100 p-4">
                <?php 
                // Display categories
                $categories = get_the_category();
                if ($categories) : ?>
                    <div class="post-categories mb-2">
                        <?php foreach ($categories as $category) : ?>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="badge bg-primary text-decoration-none me-1">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <header class="entry-header">
                    <?php the_title('<h2 class="entry-title fs-3 fw-bold"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>'); ?>
                </header>

                <div class="entry-meta text-muted small mb-2">
                    <span class="posted-on">
                        <i class="bi bi-clock"></i>
                        <?php echo esc_html(get_the_date()); ?>
                    </span>
                    <span class="byline ms-3">
                        <i class="bi bi-person"></i>
                        <?php the_author(); ?>
                    </span>
                </div>
                
                <div class="entry-content">
                    <?php the_excerpt(); ?>
                </div>
                
                <div class="entry-footer mt-auto">
                    <a href="<?php the_permalink(); ?>" class="btn btn-primary">
                        <?php _e('Read More', 'mynews'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</article>
