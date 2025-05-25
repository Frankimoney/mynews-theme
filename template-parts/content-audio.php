<?php
/**
 * Template part for displaying audio format posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('format-audio'); ?>>
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

    <div class="mynews-audio-wrapper">
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
        
        // Display the audio content
        if (!empty($audio_content)) {
            echo '<div class="mynews-audio-player">' . $audio_content . '</div>';
        }
        ?>
        
        <?php if (has_post_thumbnail() && (empty($audio_content) || !is_singular())) : ?>
            <div class="audio-thumbnail">
                <a href="<?php the_permalink(); ?>" class="post-thumbnail">
                    <?php the_post_thumbnail('medium'); ?>
                    <span class="audio-overlay">
                        <i class="bi bi-music-note-beamed"></i>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </div>

	<div class="entry-content">
		<?php
		if (is_singular()) {
            // For single posts, show the full content minus the audio embed
            $content = get_the_content();
            
            // Remove the first audio embed if it exists
            if (!empty($media)) {
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
