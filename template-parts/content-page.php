<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package My_News
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('card border-0 shadow-sm'); ?>>
	<header class="entry-header card-header bg-transparent border-0 pb-0">
		<?php the_title('<h1 class="entry-title display-4 fw-bold">', '</h1>'); ?>
	</header><!-- .entry-header -->

	<?php 
	if (has_post_thumbnail()) : ?>
		<div class="featured-image-wrapper">
			<?php the_post_thumbnail('mynews-featured-large', ['class' => 'img-fluid rounded']); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content card-body">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links nav pagination justify-content-center my-4">' . esc_html__('Pages:', 'mynews'),
				'after'  => '</div>',
				'link_before' => '<span class="page-numbers btn btn-outline-primary mx-1">',
				'link_after'  => '</span>',
			)
		);
		?>
	</div><!-- .entry-content -->

	<?php if (get_edit_post_link()) : ?>
		<footer class="entry-footer card-footer bg-transparent">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__('Edit <span class="screen-reader-text">%s</span>', 'mynews'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post(get_the_title())
				),
				'<span class="edit-link btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-square me-1"></i>',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
