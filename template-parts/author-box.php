<?php
/**
 * Template part for displaying author box in single posts
 *
 * @package My_News
 */

// Get author data
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name', $author_id);
$author_bio = get_the_author_meta('description', $author_id);
$author_posts_url = get_author_posts_url($author_id);
$author_posts_count = count_user_posts($author_id);
$author_featured_image = get_the_author_meta('author_featured_image', $author_id);
?>

<div class="author-box card mb-4">
    <div class="card-header">
        <h3 class="author-box-title m-0">
            <?php _e('About the Author', 'mynews'); ?>
        </h3>
    </div>
    
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center">
                <?php if (!empty($author_featured_image)) : ?>
                    <img src="<?php echo esc_url($author_featured_image); ?>" alt="<?php echo esc_attr($author_name); ?>" class="img-fluid rounded-circle author-avatar mb-3">
                <?php else : ?>
                    <?php echo get_avatar($author_id, 150, '', $author_name, array('class' => 'img-fluid rounded-circle author-avatar mb-3')); ?>
                <?php endif; ?>
                
                <a href="<?php echo esc_url($author_posts_url); ?>" class="btn btn-sm btn-primary author-archive-link d-block d-md-inline-block">
                    <?php 
                    printf(
                        /* translators: %s: Number of posts */
                        _n(
                            'View %s Article',
                            'View all %s Articles', 
                            $author_posts_count,
                            'mynews'
                        ),
                        number_format_i18n($author_posts_count)
                    ); 
                    ?>
                </a>
            </div>
            
            <div class="col-md-9">
                <h4 class="author-name">
                    <a href="<?php echo esc_url($author_posts_url); ?>"><?php echo esc_html($author_name); ?></a>
                </h4>
                
                <?php if (!empty($author_bio)) : ?>
                    <div class="author-bio mb-3">
                        <?php echo wpautop(wp_kses_post($author_bio)); ?>
                    </div>
                <?php endif; ?>
                
                <div class="author-social">
                    <?php
                    // Display social media links if they exist
                    $social_platforms = array(
                        'twitter' => array('icon' => 'bi-twitter-x', 'label' => __('Twitter/X', 'mynews')),
                        'facebook' => array('icon' => 'bi-facebook', 'label' => __('Facebook', 'mynews')),
                        'instagram' => array('icon' => 'bi-instagram', 'label' => __('Instagram', 'mynews')),
                        'linkedin' => array('icon' => 'bi-linkedin', 'label' => __('LinkedIn', 'mynews')),
                        'youtube' => array('icon' => 'bi-youtube', 'label' => __('YouTube', 'mynews')),
                        'github' => array('icon' => 'bi-github', 'label' => __('GitHub', 'mynews'))
                    );
                    
                    foreach ($social_platforms as $platform => $details) :
                        $platform_url = get_the_author_meta($platform . '_profile', $author_id);
                        if (!empty($platform_url)) :
                        ?>
                            <a href="<?php echo esc_url($platform_url); ?>" class="btn btn-sm btn-outline-primary me-2 mb-2" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr($details['label']); ?>">
                                <i class="bi <?php echo esc_attr($details['icon']); ?>"></i>
                                <span class="visually-hidden"><?php echo esc_html($details['label']); ?></span>
                            </a>
                        <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                
                <?php 
                // Display latest articles by this author
                $author_latest = new WP_Query(array(
                    'author' => $author_id,
                    'posts_per_page' => 3,
                    'post__not_in' => array(get_the_ID()),
                    'no_found_rows' => true,
                ));
                
                if ($author_latest->have_posts() && $author_latest->post_count > 0) : 
                ?>
                    <div class="author-latest-posts mt-3">
                        <h5><?php _e('Latest Articles by This Author', 'mynews'); ?></h5>
                        <ul class="list-unstyled author-latest-posts-list">
                            <?php while ($author_latest->have_posts()) : $author_latest->the_post(); ?>
                                <li class="author-latest-post-item">
                                    <a href="<?php the_permalink(); ?>" class="d-flex align-items-center p-2 mb-1 border-bottom">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <div class="author-latest-post-thumbnail me-2" style="width: 60px;">
                                                <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="author-latest-post-content">
                                            <h6 class="author-latest-post-title mb-0"><?php the_title(); ?></h6>
                                            <small class="text-muted"><?php echo get_the_date(); ?></small>
                                        </div>
                                    </a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                <?php
                endif;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </div>
</div>
