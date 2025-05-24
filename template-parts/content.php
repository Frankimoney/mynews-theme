<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<?php 
// Add microdata attributes for singular post views
$microdata_attributes = '';
$schema_type = 'Article';

// Determine the best schema type based on category
if (is_singular() && 'post' === get_post_type()) {
    if (has_category('news') || has_category('headlines')) {
        $schema_type = 'NewsArticle';
    } else {
        $schema_type = 'BlogPosting';
    }
    $microdata_attributes = ' itemscope itemtype="https://schema.org/' . $schema_type . '"';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?><?php echo $microdata_attributes; ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>			<div class="entry-meta">
				<?php
				if (is_singular()) :
					echo '<meta itemprop="datePublished" content="' . esc_attr(get_the_date('c')) . '">';
					echo '<meta itemprop="dateModified" content="' . esc_attr(get_the_modified_date('c')) . '">';
				endif;
				
				// Post date
				if ( function_exists( 'mynews_posted_on' ) ) {
					mynews_posted_on();
				} else {
					echo '<span class="posted-on">Posted on <a href="' . esc_url( get_permalink() ) . '">' . get_the_date() . '</a></span>';
				}
						if ( function_exists( 'mynews_posted_by' ) ) {
					mynews_posted_by();
				} else {
					if (is_singular()) :
						echo '<span class="byline"> by <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" itemprop="author" itemscope itemtype="https://schema.org/Person"><span itemprop="name">' . esc_html( get_the_author() ) . '</span></a></span>';
					else :
						echo '<span class="byline"> by <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
					endif;
				}
						// Display post view count
				if (is_singular() && function_exists('mynews_display_post_views')) :
					mynews_display_post_views();
				endif;
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->	<?php 
	if ( function_exists( 'mynews_post_thumbnail' ) ) {
		if (is_singular()) {
			echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
			mynews_post_thumbnail();
			
			// Add image metadata if available
			if (has_post_thumbnail()) {
				$image_id = get_post_thumbnail_id();
				$image_data = wp_get_attachment_image_src($image_id, 'full');
				if ($image_data) {
					echo '<meta itemprop="url" content="' . esc_url($image_data[0]) . '">';
					echo '<meta itemprop="width" content="' . esc_attr($image_data[1]) . '">';
					echo '<meta itemprop="height" content="' . esc_attr($image_data[2]) . '">';
				}
			}
			echo '</div>';
		} else {
			mynews_post_thumbnail();
		}
	} else {
		if ( has_post_thumbnail() ) {
			if ( is_singular() ) {
				echo '<div class="post-thumbnail" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
				the_post_thumbnail();
				
				// Add image metadata if available
				$image_id = get_post_thumbnail_id();
				$image_data = wp_get_attachment_image_src($image_id, 'full');
				if ($image_data) {
					echo '<meta itemprop="url" content="' . esc_url($image_data[0]) . '">';
					echo '<meta itemprop="width" content="' . esc_attr($image_data[1]) . '">';
					echo '<meta itemprop="height" content="' . esc_attr($image_data[2]) . '">';
				}
				echo '</div>';
			} else {
				echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '">';
				the_post_thumbnail();
				echo '</a>';
			}
		}
	}
	?>
	<div class="entry-content"<?php echo is_singular() ? ' itemprop="articleBody"' : ''; ?>>
		<?php
		if ( is_singular() ) :
			// Add publisher info for article schema
			echo '<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">';
			echo '<meta itemprop="name" content="' . esc_attr(get_bloginfo('name')) . '">';
			
			// Add logo if available
			$custom_logo_id = get_theme_mod('custom_logo');
			if ($custom_logo_id) {
				$logo_data = wp_get_attachment_image_src($custom_logo_id, 'full');
				if ($logo_data) {
					echo '<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">';
					echo '<meta itemprop="url" content="' . esc_url($logo_data[0]) . '">';
					echo '<meta itemprop="width" content="600">';
					echo '<meta itemprop="height" content="60">';
					echo '</div>';
				}
			}
			echo '</div>';
			
			// Add mainEntityOfPage
			echo '<meta itemprop="mainEntityOfPage" content="' . esc_url(get_permalink()) . '">';
			
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'mynews' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'mynews' ),
					'after'  => '</div>',
				)
			);
		else :
			the_excerpt();
		endif;
		?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<?php 
		if ( function_exists( 'mynews_entry_footer' ) ) {
			mynews_entry_footer();
		} else {
			// Basic fallback for entry footer
			if ( 'post' === get_post_type() ) {
				$categories_list = get_the_category_list( esc_html__( ', ', 'mynews' ) );
				if ( $categories_list ) {
					echo '<span class="cat-links">' . esc_html__( 'Posted in ', 'mynews' ) . $categories_list . '</span>';
				}
				
				$tags_list = get_the_tag_list( '', esc_html__( ', ', 'mynews' ) );
				if ( $tags_list ) {
					echo '<span class="tags-links">' . esc_html__( 'Tagged ', 'mynews' ) . $tags_list . '</span>';
				}
			}
			
			if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
				echo '<span class="comments-link">';
				comments_popup_link( esc_html__( 'Leave a comment', 'mynews' ) );
				echo '</span>';
			}
			
			edit_post_link( esc_html__( 'Edit', 'mynews' ), '<span class="edit-link">', '</span>' );
		}
		?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
