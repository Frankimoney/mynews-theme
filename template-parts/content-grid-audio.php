<?php
/**
 * Template part for displaying audio format posts in grid layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card h-100 border-0 shadow-sm format-audio'); ?>>
    <div class="card-audio-container">
        <?php
        // Try to get audio from post content
        $audio_content = '';
        
        // Check for embedded audio using get_media_embedded_in_content
        $post_content = get_the_content();
        $media = get_media_embedded_in_content($post_content, array('audio', 'iframe'));
        
        if (!empty($media)) {
            // Display the first audio embed found
            $audio_content = $media[0];
        } 
        // Check if there's no embedded audio but there might be a URL
        else {
            $audio_url = '';
            
            // Check for audio URL in content
            $post_content = get_the_content();
            $pattern = '/(https?:\/\/[^\s"\']+\.(mp3|ogg|wav|m4a))/i';
            if (preg_match($pattern, $post_content, $matches)) {
                $audio_url = $matches[1];
            }
            
            // Or check for audio URL in custom field
            if (empty($audio_url) && function_exists('get_field')) {
                $audio_url = get_field('audio_url');
            }
            
            // Create audio player if URL was found
            if (!empty($audio_url)) {
                $audio_content = wp_audio_shortcode(array('src' => $audio_url));
            }
        }
        
        // If audio found, display it, otherwise show featured image with audio overlay
        if (!empty($audio_content)) {
            echo '<div class="mynews-audio-player card-img-top">' . $audio_content . '</div>';
        } elseif (has_post_thumbnail()) {
            ?>
            <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                <?php the_post_thumbnail('mynews-card', ['class' => 'card-img-top']); ?>
                <span class="audio-overlay">
                    <i class="bi bi-music-note-beamed"></i>
                </span>
                <?php 
                // Display featured badge if post is featured
                if ( function_exists( 'mynews_is_post_featured' ) && mynews_is_post_featured() ) : ?>
                    <span class="featured-badge"><?php _e('Featured', 'mynews'); ?></span>
                <?php endif; ?>
            </a>
            <?php
        }
        ?>
    </div>
    
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
            
            // Add audio format icon
            echo '<span class="post-format ms-3">';
            echo '<i class="bi bi-music-note-beamed"></i> ';
            echo '<span>' . __('Audio', 'mynews') . '</span>';
            echo '</span>';
            ?>
        </div><!-- .entry-meta -->

        <div class="entry-content card-text">
            <?php the_excerpt(); ?>
        </div><!-- .entry-content -->
        
        <footer class="entry-footer mt-auto pt-2">
            <a href="<?php echo esc_url(get_permalink()); ?>" class="btn btn-primary btn-sm read-more-link">
                <?php esc_html_e('Listen Now', 'mynews'); ?>
                <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </footer><!-- .entry-footer -->
    </div><!-- .card-body -->
</article><!-- #post-## -->
