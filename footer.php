<?php
/**
 * The template for displaying the footer
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_News
 */

?>
	<footer id="colophon" class="site-footer">
		<div class="container">
			<?php
			// Determine footer layout based on active widget areas
			$footer_widgets = 0;
			for ($i = 1; $i <= 4; $i++) {
				if (is_active_sidebar('footer-' . $i)) {
					$footer_widgets++;
				}
			}
			
			// Set column classes based on number of active widgets
			$column_class = 'col-md-3'; // Default: 4 columns
			if ($footer_widgets == 1) {
				$column_class = 'col-md-12';
			} elseif ($footer_widgets == 2) {
				$column_class = 'col-md-6';
			} elseif ($footer_widgets == 3) {
				$column_class = 'col-md-4';
			}
			?>
			
			<div class="row footer-widgets-row">
				<!-- Footer Column 1 -->
				<div class="<?php echo esc_attr($column_class); ?> mb-4 mb-md-0">
					<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
						<?php dynamic_sidebar( 'footer-1' ); ?>
					<?php else : ?>
						<div class="footer-logo">
							<?php
							if ( has_custom_logo() ) {
								$custom_logo_id = get_theme_mod( 'custom_logo' );
								$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
								echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . get_bloginfo( 'name' ) . '" class="img-fluid">';
							} else {
								echo '<h3>' . get_bloginfo( 'name' ) . '</h3>';
							}
							?>
						</div>
						<p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
						<ul class="social-icons">
							<li><a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a></li>
							<li><a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a></li>
							<li><a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a></li>
							<li><a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a></li>
						</ul>
					<?php endif; ?>
				</div>
				
				<!-- Footer Column 2 -->
				<div class="<?php echo esc_attr($column_class); ?> mb-4 mb-md-0">
					<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-2' ); ?>
					<?php elseif ( has_nav_menu( 'footer' ) ) : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Quick Links', 'mynews' ); ?></h3>
						<?php
						wp_nav_menu( array(
							'theme_location' => 'footer',
							'menu_class'     => 'list-unstyled',
							'depth'          => 1,
						) );
						?>
					<?php endif; ?>
				</div>
				
				<!-- Footer Column 3 -->
				<div class="<?php echo esc_attr($column_class); ?> mb-4 mb-md-0">
					<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-3' ); ?>
					<?php else : ?>
						<h3 class="widget-title"><?php esc_html_e( 'Subscribe to Newsletter', 'mynews' ); ?></h3>
						<p><?php esc_html_e( 'Stay updated with our latest news and articles.', 'mynews' ); ?></p>
						<div class="newsletter-form">
							<input type="email" class="form-control" placeholder="<?php esc_attr_e( 'Your email address', 'mynews' ); ?>">
							<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Subscribe', 'mynews' ); ?></button>
						</div>
					<?php endif; ?>
				</div>
				
				<!-- Footer Column 4 -->
				<?php if (is_active_sidebar('footer-4') || $footer_widgets == 0): ?>
				<div class="<?php echo esc_attr($column_class); ?>">
					<?php if (is_active_sidebar('footer-4')): ?>
						<?php dynamic_sidebar('footer-4'); ?>
					<?php else: ?>
						<h3 class="widget-title"><?php esc_html_e('Contact Us', 'mynews'); ?></h3>
						<ul class="footer-contact list-unstyled">
							<li><i class="fas fa-map-marker-alt"></i> <?php echo esc_html__('123 Street, City, Country', 'mynews'); ?></li>
							<li><i class="fas fa-phone-alt"></i> <?php echo esc_html__('(123) 456-7890', 'mynews'); ?></li>
							<li><i class="fas fa-envelope"></i> <?php echo esc_html__('info@example.com', 'mynews'); ?></li>
						</ul>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			
			<div class="footer-bottom text-center">
				<div class="site-info">
					<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'mynews' ) ); ?>">
						<?php
						/* translators: %s: CMS name, i.e. WordPress. */
						printf( esc_html__( 'Proudly powered by %s', 'mynews' ), 'WordPress' );
						?>
					</a>
					<span class="sep"> | </span>
					<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Theme: %1$s by %2$s.', 'mynews' ), 'My News', '<a href="https://example.com/">Togor Francis</a>' );
					?>
				</div><!-- .site-info -->
			</div>
		</div><!-- .container -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php 
// Include mobile search modal if bottom navigation is enabled
if ( get_theme_mod( 'mynews_enable_mobile_bottom_nav', false ) && get_theme_mod( 'mynews_mobile_nav_search', true ) ) {
	get_template_part( 'template-parts/mobile-search-modal' );
}

// Back to top button is now handled by back-to-top.js
// The duplicate button has been removed from here
?>

<?php wp_footer(); ?>

<script>
    // Ensure dark mode state is properly synced on page load
    (function() {
        const toggle = document.getElementById('dark-mode-toggle');
        const isDarkMode = document.documentElement.hasAttribute('data-theme');
        
        if (toggle && toggle.checked !== isDarkMode) {
            toggle.checked = isDarkMode;
        }
    })();
</script>
</body>
</html>
