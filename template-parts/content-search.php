<?php
/**
 * Template part for displaying search results
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php
			if ( function_exists( 'mynews_posted_on' ) ) {
				mynews_posted_on();
			} else {
				echo '<span class="posted-on">Posted on <a href="' . esc_url( get_permalink() ) . '">' . get_the_date() . '</a></span>';
			}
			
			if ( function_exists( 'mynews_posted_by' ) ) {
				mynews_posted_by();
			} else {
				echo '<span class="byline"> by <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
			}
			?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->
	<?php 
	if ( function_exists( 'mynews_post_thumbnail' ) ) {
		mynews_post_thumbnail();
	} else {
		if ( has_post_thumbnail() ) {
			echo '<a class="post-thumbnail" href="' . esc_url( get_permalink() ) . '">';
			the_post_thumbnail();
			echo '</a>';
		}
	}
	?>

	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
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
