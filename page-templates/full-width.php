<?php
/**
 * Template Name: Full Width
 *
 * A template for displaying a full width page without the sidebar.
 *
 * @package My_News
 */

get_header();
?>

	<main id="primary" class="site-main full-width">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
