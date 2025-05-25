<?php
/**
 * The header for our theme
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package My_News
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=5.0, viewport-fit=cover">
	<meta name="theme-color" content="<?php echo esc_attr(get_theme_mod('mynews_primary_color', '#0d6efd')); ?>">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
	<meta name="format-detection" content="telephone=no">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	
	<?php if (get_theme_mod('mynews_enable_mobile_app_mode', false)) : ?>
	<!-- Mobile Web App Settings -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/app-icon-180.png">
	<link rel="manifest" href="<?php echo esc_url(get_template_directory_uri()); ?>/manifest.json">
	<?php endif; ?>

	<!-- Dark mode detection script - must run before any rendering -->
	<script>
		// Check for saved dark mode preference or system preference
		(function() {
			const storageKey = 'mynews_dark_mode';
			let darkMode = false;
			
			// Check localStorage first
			try {
				if (typeof localStorage !== 'undefined') {
					const savedPreference = localStorage.getItem(storageKey);
					if (savedPreference !== null) {
						darkMode = JSON.parse(savedPreference);
					} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
						// If no saved preference, check system preference
						darkMode = true;
					}
				}
			} catch (e) {
				console.warn('Error accessing localStorage for dark mode preference:', e);
				// Fallback to system preference if localStorage access fails
				if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
					darkMode = true;
				}
			}
			
			// Apply dark mode immediately to prevent flickering
			if (darkMode) {
				document.documentElement.setAttribute('data-theme', 'dark');
			} else {
				document.documentElement.removeAttribute('data-theme');
			}
		})();
	</script>
	
	<?php if (is_singular('post')) : // Create reading progress bar for single posts ?>	<style>
		/* Inline critical CSS for reading progress bar to ensure it loads fast */
		.reading-progress-bar,
		body .reading-progress-bar,
		html body .reading-progress-bar,
		#page .reading-progress-bar,
		div.reading-progress-bar {
			position: fixed !important;
			top: <?php echo is_admin_bar_showing() ? (wp_is_mobile() ? '46px' : '32px') : '0'; ?> !important;
			left: 0 !important;
			height: 5px !important;
			width: 0% !important;
			background-color: <?php echo get_theme_mod('dark_mode_default', false) ? '#4caf50' : '#0073aa'; ?> !important;
			z-index: 2147483647 !important; /* Maximum possible z-index value */
			transition: width 0.1s ease-out !important;
			display: block !important;
			visibility: visible !important;
			opacity: 1 !important;
			pointer-events: none !important;
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
			transform: translateZ(0) !important; /* Force hardware acceleration */
			-webkit-transform: translateZ(0) !important; /* Safari fix */
			will-change: width !important; /* Hint for browser optimization */
			max-width: 100% !important;
			min-height: 5px !important;
		}

		@keyframes progress-pulse {
			0% { opacity: 1 !important; }
			50% { opacity: 0.7 !important; }
			100% { opacity: 1 !important; }
		}

		.reading-progress-bar.complete,
		body .reading-progress-bar.complete,
		html body .reading-progress-bar.complete {
			animation: progress-pulse 2s infinite !important;
		}
		
		/* Safari specific fix */
		@media not all and (min-resolution:.001dpcm) {
			@supports (-webkit-appearance:none) {
				.reading-progress-bar,
				body .reading-progress-bar,
				html body .reading-progress-bar {
					transform: translateZ(0) !important;
					-webkit-transform: translateZ(0) !important;
					z-index: 2147483647 !important;
					will-change: transform !important;
				}
			}
		}
	</style>
	<?php endif; ?>

	<?php 
	// Add global schema.org structured data for website and organization
	if (function_exists('mynews_generate_website_schema')) {
		echo mynews_generate_website_schema();
	}
	if (function_exists('mynews_generate_organization_schema')) {
		echo mynews_generate_organization_schema();
	}
	
	wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if (is_singular('post')) : // Add reading progress bar only for single posts
// Insert reading progress bar as the first element after body open
echo '<div id="mynews-reading-progress" class="reading-progress-bar" style="position:fixed !important;top:' . (is_admin_bar_showing() ? (wp_is_mobile() ? '46px' : '32px') : '0') . ' !important;left:0 !important;height:5px !important;background-color:' . (get_theme_mod('dark_mode_default', false) ? '#4caf50' : '#0073aa') . ' !important;width:0% !important;z-index:2147483647 !important;transition:width 0.1s ease-out !important;display:block !important;visibility:visible !important;opacity:1 !important;transform:translateZ(0) !important;-webkit-transform:translateZ(0) !important;will-change:width !important;"></div>';
?>
<script>
// Simple script to handle reading progress bar - completely standalone implementation
(function() {
	// Create/verify our progress bar exists and is in the right place
	function ensureProgressBar() {
		// Try to find existing bar first
		var progressBar = document.querySelector('.reading-progress-bar');
		
		// If it doesn't exist, create it
		if (!progressBar) {
			progressBar = document.createElement('div');
			progressBar.id = 'mynews-reading-progress';
			progressBar.className = 'reading-progress-bar';
			
			// Set critical inline styles that can't be overridden
			var adminBarHeight = document.body.classList.contains('admin-bar') ? 
				(window.innerWidth < 783 ? '46px' : '32px') : '0';
			
			var barColor = document.documentElement.getAttribute('data-theme') === 'dark' ? 
				'#4caf50' : '#0073aa';
			
			progressBar.setAttribute('style', 
				'position:fixed !important;' + 
				'top:' + adminBarHeight + ' !important;' + 
				'left:0 !important;' + 
				'height:5px !important;' + 
				'background-color:' + barColor + ' !important;' + 
				'width:0% !important;' + 
				'z-index:2147483647 !important;' + 
				'transition:width 0.1s ease-out !important;' + 
				'display:block !important;' + 
				'visibility:visible !important;' + 
				'opacity:1 !important;' +
				'transform:translateZ(0) !important;' +
				'-webkit-transform:translateZ(0) !important;' +
				'will-change:width !important;'
			);
			
			// Insert at the beginning of body
			document.body.insertBefore(progressBar, document.body.firstChild);
		}
		
		return progressBar;
	}
	
	// Update the progress bar width based on scroll position
	function updateReadingProgress() {
		try {
			var progressBar = ensureProgressBar();
			if (!progressBar) return;
			
			var winScroll = window.pageYOffset || document.documentElement.scrollTop;
			var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
			var scrolled = (winScroll / height) * 100;
			
			// Ensure valid percentage
			if (isNaN(scrolled)) scrolled = 0;
			if (scrolled < 0) scrolled = 0;
			if (scrolled > 100) scrolled = 100;
			
			// Update width with !important to ensure it's applied
			progressBar.style.setProperty('width', scrolled + '%', 'important');
			
			// Add/remove complete class for animation
			if (scrolled > 98) {
				progressBar.classList.add('complete');
			} else {
				progressBar.classList.remove('complete');
			}
		} catch (e) {
			// Silently catch errors to prevent disruptions
			console.error('Error updating reading progress bar:', e);
		}
	}
	
	// Initialize: Add scroll event listener and perform initial update
	function initialize() {
		// Ensure we have a progress bar
		ensureProgressBar();
		
		// Update on scroll
		window.addEventListener('scroll', updateReadingProgress);
		
		// Update on window resize (for responsive layouts)
		window.addEventListener('resize', updateReadingProgress);
		
		// Update when page is fully loaded
		window.addEventListener('load', updateReadingProgress);
		
		// Do initial update
		updateReadingProgress();
		
		// Re-apply after a short delay to fight other scripts
		setTimeout(updateReadingProgress, 100);
		setTimeout(updateReadingProgress, 500);
		setTimeout(updateReadingProgress, 1500);
	}
	
	// Run initialization
	initialize();
})();
</script>
<?php endif; ?>
<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'mynews' ); ?></a>

<div id="page" class="site">
		<?php 
	// Include breaking news ticker but only on the front page and if breaking news exist
	if (is_front_page() && function_exists('mynews_has_active_breaking_news') && mynews_has_active_breaking_news()) {
		get_template_part('template-parts/breaking-news-ticker');
	}
	?>
	
	<?php if ( has_nav_menu( 'top-bar' ) || has_nav_menu( 'social' ) ) : ?>
	<!-- Top Bar -->
	<div class="top-bar py-2 d-none d-md-block">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-md-8">
					<?php if ( has_nav_menu( 'top-bar' ) ) : ?>
						<ul class="top-bar-menu">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'top-bar',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'fallback_cb'    => false,
							) );
							?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="col-md-4">
					<?php if ( has_nav_menu( 'social' ) ) : ?>
						<ul class="social-menu ms-auto">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'social',
								'container'      => false,
								'items_wrap'     => '%3$s',
								'depth'          => 1,
								'fallback_cb'    => false,
								'link_before'    => '<span class="screen-reader-text">',
								'link_after'     => '</span>',
							) );
							?>
						</ul>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<header id="masthead" class="site-header">
		<nav class="navbar navbar-expand-lg navbar-light py-3 shadow-sm">
			<div class="container">
				<div class="navbar-brand">
					<?php
					if ( has_custom_logo() ) {
						// Add img-fluid class to logo for better responsiveness
						$custom_logo_id = get_theme_mod( 'custom_logo' );
						$logo = wp_get_attachment_image_src( $custom_logo_id , 'full' );
						$logo_alt = get_post_meta( $custom_logo_id, '_wp_attachment_image_alt', true );
						echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home" aria-current="page">';
						echo '<img src="' . esc_url( $logo[0] ) . '" class="img-fluid custom-logo" alt="' . esc_attr( $logo_alt ?: get_bloginfo( 'name' ) ) . '" width="' . $logo[1] . '" height="' . $logo[2] . '">';
						echo '</a>';
					} else {
						if ( is_front_page() && is_home() ) :
							?>
							<h1 class="site-title mb-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-decoration-none"><?php bloginfo( 'name' ); ?></a></h1>
							<?php
						else :
							?>
							<p class="site-title mb-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-decoration-none"><?php bloginfo( 'name' ); ?></a></p>
							<?php
						endif;
						
						$mynews_description = get_bloginfo( 'description', 'display' );
						if ( $mynews_description || is_customize_preview() ) :
							?>
							<p class="site-description mb-0 small text-muted"><?php echo $mynews_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
						<?php endif;
					}
					?>
				</div><!-- .navbar-brand -->				<div class="d-flex align-items-center ms-auto">					<!-- Dark Mode Toggle -->
					<div class="dark-mode-container me-3">
						<label class="dark-mode-toggle" title="<?php esc_attr_e('Toggle Dark Mode', 'mynews'); ?>" style="visibility: visible;">
							<input type="checkbox" id="dark-mode-toggle">
							<span class="slider">
								<i class="sun bi bi-sun-fill"></i>
								<i class="moon bi bi-moon-fill"></i>
							</span>
						</label>
						<span class="visually-hidden"><?php esc_html_e('Toggle Dark Mode', 'mynews'); ?></span>
					</div>

					<!-- Optional Search Icon -->
					<div class="search-icon me-3">
						<a href="#" class="search-toggle text-decoration-none" aria-expanded="false" aria-controls="searchCollapse">
							<i class="fas fa-search"></i>
						</a>
					</div>

					<!-- Improved Hamburger Menu Button -->
					<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'mynews' ); ?>">
						<span class="navbar-toggler-icon"></span>
					</button>
				</div>
				
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					mynews_bootstrap_menu( 'primary' );
				} else {
					echo '<div class="collapse navbar-collapse" id="navbarCollapse">';
					echo '<ul class="navbar-nav ms-auto mb-2 mb-md-0">';
					echo '<li class="nav-item"><a class="nav-link" href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'mynews' ) . '</a></li>';
					echo '</ul>';
					echo '</div>';
				}
				?>			</div><!-- .container -->
		</nav><!-- .navbar -->
		
		<!-- Collapsible Search Box -->
		<div class="collapse bg-light" id="searchCollapse">
			<div class="container py-3">
				<?php get_search_form(); ?>
			</div>
		</div>
	</header><!-- #masthead -->
	
	<?php if ( is_front_page() && is_home() && function_exists( 'dynamic_sidebar' ) && is_active_sidebar( 'header-widgets' ) ) : ?>
	<div class="header-widgets py-4 bg-light">
		<div class="container">
			<?php dynamic_sidebar( 'header-widgets' ); ?>
		</div>
	</div>
	<?php endif; ?>
