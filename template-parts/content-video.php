<?php
/**
 * Template part for displaying video format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('format-video'); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				if ( function_exists( 'mynews_posted_on' ) ) {
					mynews_posted_on();
				}
				if ( function_exists( 'mynews_posted_by' ) ) {
					mynews_posted_by();
				}
				
				// Add the post views counter if available
				if ( function_exists( 'mynews_display_post_views' ) ) {
					mynews_display_post_views();
				}
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

    <div class="mynews-video-wrapper">
        <?php
        // Try to get video from post content
        $video_content = '';
        $video_found = false;
        
        // Check for embedded video using get_media_embedded_in_content
        $post_content = get_the_content();
        $media = get_media_embedded_in_content($post_content, array('video', 'object', 'embed', 'iframe'));
        
        if (!empty($media)) {
            // Display the first video embed found
            $video_content = $media[0];
            $video_found = true;
        } 
        // Check for video URL
        else {
            $video_url = '';
            
            // Look for YouTube, Vimeo, etc URLs in the content
            $patterns = array(
                'youtube' => '/https?:\/\/(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)(?:&[^\\s]*)?/i',
                'vimeo' => '/https?:\/\/(?:www\.)?vimeo\.com\/([0-9]+)(?:\?[^\\s]*)?/i',
                'mp4' => '/(https?:\/\/[^\s"\']+\.(mp4|webm|ogv))/i',
            );
            
            foreach ($patterns as $service => $pattern) {
                if (preg_match($pattern, $post_content, $matches)) {
                    switch ($service) {
                        case 'youtube':
                            $video_content = wp_oembed_get('https://www.youtube.com/watch?v=' . $matches[1], array('width' => 1080));
                            $video_found = true;
                            break;
                        case 'vimeo':
                            $video_content = wp_oembed_get('https://vimeo.com/' . $matches[1], array('width' => 1080));
                            $video_found = true;
                            break;
                        case 'mp4':
                            $video_content = wp_video_shortcode(array('src' => $matches[1], 'width' => 1080, 'height' => 608));
                            $video_found = true;
                            break;
                    }
                    
                    if ($video_found) {
                        break; // Stop searching after first match
                    }
                }
            }
            
            // Or check for video URL in custom field
            if (!$video_found && function_exists('get_field')) {
                $video_url = get_field('video_url');
                if (!empty($video_url)) {
                    $video_content = wp_oembed_get($video_url, array('width' => 1080));
                    $video_found = true;
                }
            }
        }
        
        // Display the video content
        if ($video_found && !empty($video_content)) {
            echo '<div class="mynews-video-container">' . $video_content . '</div>';
        }
        ?>
        
        <?php if (has_post_thumbnail() && (empty($video_content) || !is_singular())) : ?>
            <div class="video-thumbnail">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                    <?php the_post_thumbnail('mynews-featured-medium'); ?>
                    <span class="video-overlay">
                        <i class="bi bi-play-circle-fill"></i>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>

	<div class="entry-content">
		<?php
		if (is_singular()) {
            // For single posts, show the full content minus the video embed
            $content = get_the_content();
            
            // Remove the first video embed if it exists
            if ($video_found && !empty($media)) {
                $content = str_replace($media[0], '', $content);
            }
            
            // Apply filters and output content
            $content = apply_filters('the_content', $content);
            $content = str_replace(']]>', ']]&gt;', $content);
            echo $content;
		} else {
			the_excerpt();
		}
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php 
		if ( function_exists( 'mynews_entry_footer' ) ) {
			mynews_entry_footer();
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
