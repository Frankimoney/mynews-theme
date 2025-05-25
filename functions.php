<?php
/**
 * My News Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package My_News
 */

if ( ! defined( 'MYNEWS_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'MYNEWS_VERSION', '1.0.0' );
}

/**
 * Define constants for the theme directories
 */
define( 'MYNEWS_THEME_DIR', get_template_directory() );
define( 'MYNEWS_THEME_URI', get_template_directory_uri() );
define( 'MYNEWS_ASSETS_URI', MYNEWS_THEME_URI . '/assets' );

/**
 * Include the breaking news ticker fix
 */
require get_template_directory() . '/inc/breaking-news-fix.php';

/**
 * Include ticker test script (for development only)
 */
require get_template_directory() . '/inc/ticker-test.php';

/**
 * Include post views tracking functionality
 */
require get_template_directory() . '/inc/post-views.php';

/**
 * Include Popular Reactions Widget
 */
require get_template_directory() . '/inc/widgets/popular-reactions-widget.php';

/**
 * Include Author Profile functionality
 */
require get_template_directory() . '/inc/author-profile.php';

/**
 * Include Footer Text Visibility Fix
 */
require get_template_directory() . '/inc/footer-text-fix.php';

/**
 * Include Post Formats Admin Help
 */
require get_template_directory() . '/inc/post-formats-admin.php';

/**
 * Include Dark Mode Troubleshooting Tool
 */
require get_template_directory() . '/inc/dark-mode-troubleshoot.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function mynews_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );
	
	// Add custom image sizes for responsive design
	add_image_size( 'mynews-featured-large', 1200, 628, true ); // Featured image on single posts/pages
	add_image_size( 'mynews-featured-medium', 800, 500, true ); // Featured image on archive pages
	add_image_size( 'mynews-card', 600, 400, true ); // For card layouts
	add_image_size( 'mynews-thumbnail', 300, 300, true ); // For sidebars and small listings

	// Register menus
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary Menu', 'mynews' ),
			'top-bar' => esc_html__( 'Top Bar Menu', 'mynews' ),
			'footer' => esc_html__( 'Footer Menu', 'mynews' ),
			'social' => esc_html__( 'Social Menu', 'mynews' ),
			'mobile' => esc_html__( 'Mobile Menu', 'mynews' ),
		)
	);

	// Switch default core markup to output valid HTML5.
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'mynews_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for block styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for full and wide align images.
	add_theme_support( 'align-wide' );
	// Add support for editor styles.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );
	
	// Add support for post formats
	add_theme_support(
		'post-formats',
		array(
			'audio',
			'video',
		)
	);

	// Add support for custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'mynews_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function mynews_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'mynews_content_width', 1200 );
}
add_action( 'after_setup_theme', 'mynews_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function mynews_widgets_init() {
	// Main Sidebar
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'mynews' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here for the main sidebar.', 'mynews' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);

	// Footer Widget Areas
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 1', 'mynews' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Add widgets here for first footer column.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 2', 'mynews' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Add widgets here for second footer column.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 3', 'mynews' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Add widgets here for third footer column.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer 4', 'mynews' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Add widgets here for fourth footer column.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="footer-widget-title">',
			'after_title'   => '</h3>',
		)
	);

	// Header Widget Area
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Top', 'mynews' ),
			'id'            => 'header-top',
			'description'   => esc_html__( 'Add widgets here for top header area (next to top menu).', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="header-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	// Premium Ad Widget Areas
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Ad', 'mynews' ),
			'id'            => 'ad-header',
			'description'   => esc_html__( 'Add ad code here for the premium header placement (below navigation, above content). Great for high-visibility banners.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Below Post Title Ad', 'mynews' ),
			'id'            => 'ad-below-title',
			'description'   => esc_html__( 'Add ad code here for placement below post title. High CTR placement above the fold.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Mid Content Ad', 'mynews' ),
			'id'            => 'ad-mid-content',
			'description'   => esc_html__( 'Add ad code here for placement in the middle of post content. Native-looking placement with high engagement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'After Content Ad', 'mynews' ),
			'id'            => 'ad-after-content',
			'description'   => esc_html__( 'Add ad code here for placement after post content, before comments. High completion placement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Top Ad', 'mynews' ),
			'id'            => 'ad-sidebar-top',
			'description'   => esc_html__( 'Add ad code here for placement at the top of the sidebar. High-visibility placement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Bottom Ad', 'mynews' ),
			'id'            => 'ad-sidebar-bottom',
			'description'   => esc_html__( 'Add ad code here for placement at the bottom of the sidebar.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Ad', 'mynews' ),
			'id'            => 'ad-footer',
			'description'   => esc_html__( 'Add ad code here for site-wide footer placement. Displays on all pages before the footer.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	// Premium Ad Widget Areas
	register_sidebar(
		array(
			'name'          => esc_html__( 'Header Ad', 'mynews' ),
			'id'            => 'ad-header',
			'description'   => esc_html__( 'Add ad code here for the premium header placement (below navigation, above content). Great for high-visibility banners.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Below Post Title Ad', 'mynews' ),
			'id'            => 'ad-below-title',
			'description'   => esc_html__( 'Add ad code here for placement below post title. High CTR placement above the fold.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Mid Content Ad', 'mynews' ),
			'id'            => 'ad-mid-content',
			'description'   => esc_html__( 'Add ad code here for placement in the middle of post content. Native-looking placement with high engagement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'After Content Ad', 'mynews' ),
			'id'            => 'ad-after-content',
			'description'   => esc_html__( 'Add ad code here for placement after post content, before comments. High completion placement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Top Ad', 'mynews' ),
			'id'            => 'ad-sidebar-top',
			'description'   => esc_html__( 'Add ad code here for placement at the top of the sidebar. High-visibility placement.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Bottom Ad', 'mynews' ),
			'id'            => 'ad-sidebar-bottom',
			'description'   => esc_html__( 'Add ad code here for placement at the bottom of the sidebar.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
	
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Ad', 'mynews' ),
			'id'            => 'ad-footer',
			'description'   => esc_html__( 'Add ad code here for site-wide footer placement. Displays on all pages before the footer.', 'mynews' ),
			'before_widget' => '<div id="%1$s" class="ad-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="visually-hidden">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'mynews_widgets_init' );

/**
 * Enqueue scripts and styles with optimization.
 */
function mynews_scripts() {
	// Bootstrap CSS from CDN with preload
	wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css', array(), '5.3.2' );
	
	// Preload Bootstrap CSS
	add_action('wp_head', function() {
		echo '<link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" as="style">';
	}, 1);
	// Font Awesome for icons - defer loading for non-critical icons
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', array(), '6.4.2' );
	
	// Bootstrap Icons
	wp_enqueue_style( 'bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css', array(), '1.11.1' );
	
	// Add main theme CSS file (after Bootstrap so we can override styles)
	wp_enqueue_style( 'mynews-main', get_template_directory_uri() . '/assets/css/main.css', array('bootstrap'), MYNEWS_VERSION );
		// Add footer and additional styles	wp_enqueue_style( 'mynews-footer', get_template_directory_uri() . '/assets/css/footer.css', array('mynews-main'), MYNEWS_VERSION );
		// Add back to top button styles
	wp_enqueue_style( 'mynews-back-to-top', get_template_directory_uri() . '/assets/css/back-to-top.css', array('mynews-main'), MYNEWS_VERSION );
		// Add layout styles
	wp_enqueue_style( 'mynews-layout', get_template_directory_uri() . '/assets/css/layout.css', array('mynews-main'), MYNEWS_VERSION );
		// Add blog post layout styles	wp_enqueue_style( 'mynews-blog', get_template_directory_uri() . '/assets/css/blog.css', array('mynews-main'), MYNEWS_VERSION );
		// Add page styles
	wp_enqueue_style( 'mynews-page', get_template_directory_uri() . '/assets/css/page.css', array('mynews-main'), MYNEWS_VERSION );
	// Add pagination and navigation styles
	wp_enqueue_style( 'mynews-pagination', get_template_directory_uri() . '/assets/css/pagination.css', array('mynews-main'), MYNEWS_VERSION );
	// Add popular posts widget styles
	wp_enqueue_style( 'mynews-popular-posts', get_template_directory_uri() . '/assets/css/popular-posts-widget.css', array('mynews-main'), MYNEWS_VERSION );
		// Add popular reactions widget style if exists
	if (file_exists(get_template_directory() . '/assets/css/popular-reactions-widget.css')) {
		wp_enqueue_style( 'mynews-popular-reactions', get_template_directory_uri() . '/assets/css/popular-reactions-widget.css', array('mynews-main'), MYNEWS_VERSION );
	}
	
	// Add top reactions style if exists
	if (file_exists(get_template_directory() . '/assets/css/top-reactions.css')) {
		wp_enqueue_style( 'mynews-top-reactions', get_template_directory_uri() . '/assets/css/top-reactions.css', array('mynews-main'), MYNEWS_VERSION );
	}
	// Add mobile-specific optimizations	wp_enqueue_style( 'mynews-mobile', get_template_directory_uri() . '/assets/css/mobile.css', array('mynews-main'), MYNEWS_VERSION );	// Add grid layout fixes
	wp_enqueue_style( 'mynews-grid-fixes', get_template_directory_uri() . '/assets/css/grid-fixes.css', array('mynews-main', 'mynews-blog'), MYNEWS_VERSION );
	
	// Improved single post styling
	wp_enqueue_style( 'mynews-single-post', get_template_directory_uri() . '/assets/css/single-post.css', array('mynews-main'), MYNEWS_VERSION );

	// Add featured image size constraints	wp_enqueue_style( 'mynews-featured-image-fixes', get_template_directory_uri() . '/assets/css/featured-image-fixes.css', array('mynews-main'), MYNEWS_VERSION );
	// Add ad placements styles
	wp_enqueue_style( 'mynews-ad-placements', get_template_directory_uri() . '/assets/css/ad-placements.css', array('mynews-main'), MYNEWS_VERSION );
	// Add custom style.css (for additional custom styles)
	wp_enqueue_style( 'mynews-custom', get_template_directory_uri() . '/assets/css/style.css', array('bootstrap', 'mynews-main'), MYNEWS_VERSION );
	// Add dark mode styles	
	wp_enqueue_style( 'mynews-dark-mode', get_template_directory_uri() . '/assets/css/dark-mode.css', array('bootstrap', 'mynews-main'), MYNEWS_VERSION );
	// Add dark mode fix styles
	wp_enqueue_style( 'mynews-dark-mode-fix', get_template_directory_uri() . '/assets/css/dark-mode-fix.css', array('mynews-dark-mode'), MYNEWS_VERSION );
		// Add post formats styling
	wp_enqueue_style( 'mynews-post-formats', get_template_directory_uri() . '/assets/css/post-formats.css', array('mynews-main'), MYNEWS_VERSION );
		// Add author profiles styling
	wp_enqueue_style( 'mynews-author-profiles', get_template_directory_uri() . '/assets/css/author-profiles.css', array('mynews-main'), MYNEWS_VERSION );
		// Add footer text visibility fix - high priority to override other styles
	wp_enqueue_style( 'mynews-footer-fix', get_template_directory_uri() . '/assets/css/footer-fix.css', array('mynews-main', 'mynews-dark-mode'), MYNEWS_VERSION );
		// Add related content styles for related articles and read next components
	wp_enqueue_style( 'mynews-related-content', get_template_directory_uri() . '/assets/css/related-content.css', array('mynews-main'), MYNEWS_VERSION );
	
	// Add post navigation dark mode fix
	wp_enqueue_style( 'mynews-post-navigation-fix', get_template_directory_uri() . '/assets/css/post-navigation-fix.css', array('mynews-main', 'mynews-dark-mode'), MYNEWS_VERSION );
	
	// jQuery (if needed)
	wp_enqueue_script( 'jquery' );
	
	// Bootstrap JS Bundle (includes Popper) - defer loading
	wp_enqueue_script( 'bootstrap-bundle', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', array('jquery'), '5.3.2', array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	// Theme navigation JavaScript
	wp_enqueue_script( 'mynews-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array('jquery'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
		
	// Main JavaScript file
	wp_enqueue_script( 'mynews-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'bootstrap-bundle'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
		
	// Theme custom scripts
	wp_enqueue_script( 'mynews-scripts', get_template_directory_uri() . '/assets/js/scripts.js', array('jquery', 'bootstrap-bundle'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	// Back to top button script
	wp_enqueue_script( 'mynews-back-to-top', get_template_directory_uri() . '/assets/js/back-to-top.js', array('jquery'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	
	// FastClick library to eliminate 300ms delay on mobile devices
	wp_enqueue_script( 'fastclick', 'https://cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.6/fastclick.min.js', array('jquery'), '1.0.6', array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	
	// Mobile touch optimization script
	wp_enqueue_script( 'mynews-mobile-touch', get_template_directory_uri() . '/assets/js/mobile-touch.js', array('jquery', 'fastclick'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	
	// Mobile bottom navigation
	if ( get_theme_mod( 'mynews_enable_mobile_bottom_nav', false ) ) {
		wp_enqueue_script( 'mynews-mobile-bottom-nav', get_template_directory_uri() . '/assets/js/mobile-bottom-nav.js', array('jquery', 'bootstrap-bundle'), MYNEWS_VERSION, array(
			'strategy' => 'defer',
			'in_footer' => true,
		));
		
		// Get categories for the bottom nav
		$categories = get_categories( array(
			'orderby' => 'count',
			'order'   => 'DESC',
			'number'  => 10,
		) );
		
		$categories_for_js = array();
		foreach ( $categories as $category ) {
			$categories_for_js[] = array(
				'name'  => $category->name,
				'url'   => get_category_link( $category->term_id ),
				'count' => $category->count,
			);
		}
		
		// Localize script with categories and settings
		wp_localize_script( 'mynews-mobile-bottom-nav', 'myNewsBottomNav', array(
			'enabled'        => true,
			'homeUrl'        => home_url( '/' ),
			'showHome'       => get_theme_mod( 'mynews_mobile_nav_home', true ),
			'showSearch'     => get_theme_mod( 'mynews_mobile_nav_search', true ),
			'showCategories' => get_theme_mod( 'mynews_mobile_nav_categories', true ),
			'showDarkMode'   => get_theme_mod( 'mynews_enable_mobile_dark_mode', false ),
			'categories'     => $categories_for_js,
			'labels'         => array(
				'home'       => __( 'Home', 'mynews' ),
				'search'     => __( 'Search', 'mynews' ),
				'categories' => __( 'Categories', 'mynews' ),
				'darkMode'   => __( 'Dark Mode', 'mynews' ),
				'menu'       => __( 'Menu', 'mynews' ),
			),
		) );
		
		// Recent searches tracking
		if ( get_theme_mod( 'mynews_show_recent_searches', true ) ) {
			wp_enqueue_script( 'mynews-recent-searches', get_template_directory_uri() . '/assets/js/recent-searches.js', array('jquery'), MYNEWS_VERSION, array(
				'strategy' => 'defer',
				'in_footer' => true,
			) );
			
			wp_localize_script( 'mynews-recent-searches', 'myNewsSearchTracker', array(
				'enabled'  => true,
				'maxTerms' => 10,
			) );
		}
	}
		// Pass customizer settings to back to top script
	wp_localize_script( 'mynews-back-to-top', 'mynewsBackToTop', array(
		'enabled' => get_theme_mod('mynews_enable_back_to_top', true),
	));
		// Dark Mode toggle script
	wp_enqueue_script( 'mynews-dark-mode', get_template_directory_uri() . '/assets/js/dark-mode.js', array('jquery'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	
	// Dark Mode fix script
	wp_enqueue_script( 'mynews-dark-mode-fix', get_template_directory_uri() . '/assets/js/dark-mode-fix.js', array('jquery', 'mynews-dark-mode'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	));
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mynews_scripts' );

/**
 * Include and register widgets
 */
require get_template_directory() . '/inc/widgets/popular-posts-widget.php';

/**
 * Register Popular Posts widget
 */
function mynews_register_widgets() {
    register_widget('Mynews_Popular_Posts_Widget');
}
add_action('widgets_init', 'mynews_register_widgets');

/**
 * Check if there are active breaking news items
 *
 * @return bool True if there are active breaking news items and the ticker is enabled
 */
function mynews_has_active_breaking_news() {
    // First check if breaking news ticker is enabled in customizer
    if (!get_theme_mod('mynews_enable_breaking_ticker', true)) {
        return false;
    }
    
    $today = current_time('Y-m-d');
    
    // Create meta query for expiry dates
    $expiry_meta_query = array(
        'relation' => 'OR',
        // Posts with no expiry date
        array(
            'key' => '_breaking_news_expiry',
            'value' => '',
            'compare' => '='
        ),
        // Posts with expiry date in the future
        array(
            'key' => '_breaking_news_expiry',
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE'
        )
    );
    
    // First check custom breaking_news post type
    $breaking_args = array(
        'post_type' => 'breaking_news',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => $expiry_meta_query
    );
    
    $breaking_news = new WP_Query($breaking_args);
    
    // If we found breaking news custom posts, return true
    if ($breaking_news->have_posts()) {
        return true;
    }
    
    // If not, check regular posts marked as breaking news
    $regular_breaking_args = array(
        'post_type' => 'post',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => array(
            'relation' => 'AND',
            array(
                'key' => '_is_breaking_news',
                'value' => '1',
                'compare' => '='
            ),
            $expiry_meta_query
        )
    );
    
    $regular_breaking_news = new WP_Query($regular_breaking_args);
    return $regular_breaking_news->have_posts();
}

/**
 * Enqueue Breaking News Ticker scripts and styles
 */
function mynews_enqueue_breaking_news_ticker_assets() {
    // Load on front page or single posts (if enabled) when breaking news exist
    $show_on_single = is_singular('post') && get_theme_mod('mynews_show_ticker_on_single', true);
    $show_on_front = is_front_page();
    
    if (($show_on_front || $show_on_single) && mynews_has_active_breaking_news()) {
        wp_enqueue_style('mynews-breaking-news-ticker', get_template_directory_uri() . '/assets/css/breaking-news-ticker.css', array('mynews-main'), MYNEWS_VERSION);
        wp_enqueue_script('mynews-breaking-news-ticker', get_template_directory_uri() . '/assets/js/breaking-news-ticker.js', array('jquery'), MYNEWS_VERSION, array(
            'strategy' => 'defer',
            'in_footer' => true,
        ));
        
        // Add fallback script for browsers with limited animation support
        wp_enqueue_script('mynews-ticker-fallback', get_template_directory_uri() . '/assets/js/ticker-fallback.js', array('jquery', 'mynews-breaking-news-ticker'), MYNEWS_VERSION, array(
            'strategy' => 'defer',
            'in_footer' => true,
        ));
    }
}
add_action('wp_enqueue_scripts', 'mynews_enqueue_breaking_news_ticker_assets');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom styles from customizer
 */
require get_template_directory() . '/inc/custom-styles.php';

/**
 * Mobile optimization customizer options
 */
require get_template_directory() . '/inc/mobile-customizer.php';

/**
 * Ad placements customizer options
 */
require get_template_directory() . '/inc/ad-customizer.php';

/**
 * Ad insertion functions
 */
require get_template_directory() . '/inc/ad-insertion.php';

/**
 * Advanced ad features - in-feed ads, sticky ads, and video ads
 */
require get_template_directory() . '/inc/advanced-ad-features.php';

/**
 * Breaking News help and documentation
 */
require get_template_directory() . '/inc/breaking-news-help.php';

/**
 * Bootstrap 5 Nav Walker for navigation menus
 */
require get_template_directory() . '/inc/bootstrap-5-nav-walker.php';

/**
 * Theme helper functions for Bootstrap integration
 */

/**
 * Add Bootstrap 5 classes to the comment form
 */
function mynews_comment_form_fields( $fields ) {
    foreach( $fields as &$field ) {
        $field = str_replace( 'class="comment-form-', 'class="comment-form- mb-3 ', $field );
        $field = str_replace( 'id="author"', 'id="author" class="form-control"', $field );
        $field = str_replace( 'id="email"', 'id="email" class="form-control"', $field );
        $field = str_replace( 'id="url"', 'id="url" class="form-control"', $field );
    }
    return $fields;
}
add_filter( 'comment_form_default_fields', 'mynews_comment_form_fields' );

/**
 * Add Bootstrap 5 classes to the comment form textarea
 */
function mynews_comment_form( $args ) {
    $args['class_form'] = 'comment-form needs-validation';
    $args['class_submit'] = 'btn btn-primary';
    $args['comment_field'] = str_replace( 'textarea', 'textarea class="form-control"', $args['comment_field'] );
    return $args;
}
add_filter( 'comment_form_defaults', 'mynews_comment_form' );

/**
 * Add Bootstrap 5 classes to WordPress pagination
 */
function mynews_pagination_classes( $html ) {
    $html = str_replace( '<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center">', $html );
    $html = str_replace( '<li>', '<li class="page-item">', $html );
    $html = str_replace( 'page-numbers', 'page-link', $html );
    $html = str_replace( 'current', 'current active', $html );
    return $html;
}
add_filter( 'the_posts_pagination', 'mynews_pagination_classes' );
add_filter( 'paginate_links', 'mynews_pagination_classes' );

/**
 * Enhanced numeric pagination for archives and index
 * 
 * @return void
 */
function mynews_numeric_pagination() {
    if (is_singular()) {
        return;
    }

    global $wp_query;
    
    // Don't print empty markup if there's only one page
    if ($wp_query->max_num_pages < 2) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max   = intval($wp_query->max_num_pages);
    
    // Responsive settings - show fewer page numbers on mobile
    $is_mobile = wp_is_mobile();
    $end_size = $is_mobile ? 1 : 2;
    $mid_size = $is_mobile ? 1 : 2;

    // Array of arguments
    $args = array(
        'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format'       => '?paged=%#%',
        'current'      => max(1, $paged),
        'total'        => $max,
        'type'         => 'list',
        'show_all'     => false,
        'end_size'     => $end_size,
        'mid_size'     => $mid_size,
        'prev_next'    => true,
        'prev_text'    => '<i class="bi bi-chevron-left"></i> <span class="d-none d-sm-inline">' . __('Previous', 'mynews') . '</span>',
        'next_text'    => '<span class="d-none d-sm-inline">' . __('Next', 'mynews') . '</span> <i class="bi bi-chevron-right"></i>',
        'add_args'     => false,
        'add_fragment' => '',
    );
    
    // Generate the pagination markup
    $pages = paginate_links($args);
    
    // Fix the pagination classes for Bootstrap
    $pages = str_replace('<ul class=\'page-numbers\'>', '<ul class="pagination justify-content-center">', $pages);
    $pages = str_replace('<li>', '<li class="page-item">', $pages);
    $pages = str_replace('page-numbers', 'page-link', $pages);
    $pages = str_replace('current', 'active', $pages);
    
    // Add aria attributes for accessibility
    $pages = str_replace('<ul class="pagination', '<ul role="navigation" aria-label="' . __('Pagination', 'mynews') . '" class="pagination', $pages);

    // Return the pagination
    echo '<div class="mynews-pagination py-4">' . $pages . '</div>';
}

/**
 * Bootstrap 5 compatible nav menu
 */
function mynews_bootstrap_menu( $theme_location ) {
    if ( ( $theme_location ) && ( has_nav_menu( $theme_location ) ) ) {
        wp_nav_menu( array(
            'theme_location'  => $theme_location,
            'depth'           => 2,
            'container'       => 'div',
            'container_class' => 'collapse navbar-collapse',
            'container_id'    => 'navbarCollapse',
            'menu_class'      => 'navbar-nav ms-auto mb-2 mb-md-0',
            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
            'walker'          => new Mynews_Bootstrap_5_Nav_Walker()
        ) );
    } else {
        echo '<div class="alert alert-warning">Please assign a menu to the ' . $theme_location . ' menu location in WordPress admin panel.</div>';
    }
}

/**
 * Enhanced post navigation for single posts with thumbnails and category
 */
function mynews_post_navigation() {    // Get the previous and next posts
    $prev_post = get_previous_post();
    $next_post = get_next_post();

    // Start output buffer
    ob_start(); 
    ?>
    <nav class="mynews-post-navigation my-5 pt-4 border-top" aria-label="<?php esc_attr_e('Post Navigation', 'mynews'); ?>">
        <h4 class="h5 mb-4 text-center fw-bold"><?php _e('Continue Reading', 'mynews'); ?></h4>
        <div class="row g-4">
            <?php if (!empty($prev_post)) : ?>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm post-navigation-card">
                        <?php if (has_post_thumbnail($prev_post->ID)) : ?>
                            <div class="post-nav-thumbnail">
                                <?php echo get_the_post_thumbnail($prev_post->ID, 'thumbnail', array('class' => 'img-fluid')); ?>
                                <div class="post-nav-overlay">
                                    <i class="bi bi-arrow-left-circle-fill"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="card-body">
                            <span class="d-block text-uppercase small post-nav-label">
                                <i class="bi bi-arrow-left"></i> <?php _e('Previous Post', 'mynews'); ?>
                            </span>
                            <h3 class="h5 mb-3">
                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="stretched-link text-decoration-none">
                                    <?php echo wp_trim_words(get_the_title($prev_post->ID), 10); ?>
                                </a>
                            </h3>
                            <div class="d-flex align-items-center">
                                <?php 
                                // Get the first category
                                $categories = get_the_category($prev_post->ID);
                                if (!empty($categories)) {
                                    $first_category = $categories[0];
                                    echo '<span class="badge bg-primary">' . esc_html($first_category->name) . '</span>';
                                }
                                ?>
                                <span class="ms-2 small text-muted">
                                    <?php echo get_the_date('', $prev_post->ID); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-md-6"></div>
            <?php endif; ?>
            
            <?php if (!empty($next_post)) : ?>
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm post-navigation-card">
                        <?php if (has_post_thumbnail($next_post->ID)) : ?>
                            <div class="post-nav-thumbnail">
                                <?php echo get_the_post_thumbnail($next_post->ID, 'thumbnail', array('class' => 'img-fluid')); ?>
                                <div class="post-nav-overlay">
                                    <i class="bi bi-arrow-right-circle-fill"></i>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="card-body text-end">
                            <span class="d-block text-uppercase small post-nav-label">
                                <?php _e('Next Post', 'mynews'); ?> <i class="bi bi-arrow-right"></i>
                            </span>
                            <h3 class="h5 mb-3">
                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="stretched-link text-decoration-none">
                                    <?php echo wp_trim_words(get_the_title($next_post->ID), 10); ?>
                                </a>
                            </h3>
                            <div class="d-flex align-items-center justify-content-end">
                                <span class="me-2 small text-muted">
                                    <?php echo get_the_date('', $next_post->ID); ?>
                                </span>
                                <?php 
                                // Get the first category
                                $categories = get_the_category($next_post->ID);
                                if (!empty($categories)) {
                                    $first_category = $categories[0];
                                    echo '<span class="badge bg-primary">' . esc_html($first_category->name) . '</span>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else : ?>
                <div class="col-md-6"></div>
            <?php endif; ?>
        </div>
    </nav>
    <?php
    return ob_get_clean();
}

/**
 * Add Bootstrap 5 classes to gallery
 */
function mynews_bootstrap_gallery( $output, $attr, $instance ) {
    global $post;
    $atts = shortcode_atts( array(
        'order'   => 'ASC',
        'exclude' => '',
        'id'      => $post ? $post->ID : 0,
        'size'    => 'thumbnail',
        'include' => '',
        'link'    => '',
        'columns' => 3,
    ), $attr, 'gallery' );

    $columns = $atts['columns'];
    $col_class = 'col-md-' . (12 / $columns);
    
    // Add Bootstrap row class to gallery
    $output = str_replace( 'class="gallery ', 'class="gallery row ', $output );
    
    // Add Bootstrap column classes to gallery items
    $output = str_replace( 'class="gallery-item', 'class="gallery-item ' . $col_class . ' mb-4', $output );
    
    // Add Bootstrap img-fluid class to images
    $output = str_replace( 'class="attachment-', 'class="img-fluid attachment-', $output );
    
    return $output;
}
add_filter( 'post_gallery', 'mynews_bootstrap_gallery', 10, 3 );

/**
 * Display post thumbnail.
 */
if ( ! function_exists( 'mynews_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */	function mynews_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail mb-4">
				<?php the_post_thumbnail('mynews-featured-large', array('class' => 'img-fluid rounded')); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail mb-3 d-block" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'mynews-featured-medium',
						array(
							'class' => 'img-fluid rounded',
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

/**
 * Mobile lazy loading
 */
if ( get_theme_mod( 'mynews_mobile_lazy_load', true ) ) {
	wp_enqueue_script( 'mynews-lazy-loading', get_template_directory_uri() . '/assets/js/lazy-loading.js', array('jquery'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	) );
	
	wp_localize_script( 'mynews-lazy-loading', 'myNewsLazyLoad', array(
		'enabled' => true,
	) );
	
	// Filter post content to add lazy loading attributes
	add_filter( 'the_content', 'mynews_add_lazy_loading_attributes' );
	
	// Filter post thumbnails to add lazy loading attributes
	add_filter( 'post_thumbnail_html', 'mynews_add_lazy_loading_attributes' );
}

// Mobile reading experience enhancements on single posts
if ( is_singular( 'post' ) && wp_is_mobile() ) {
	wp_enqueue_script( 'mynews-mobile-reading', get_template_directory_uri() . '/assets/js/mobile-reading.js', array('jquery'), MYNEWS_VERSION, array(
		'strategy' => 'defer',
		'in_footer' => true,
	) );
}

/**
 * Add lazy loading attributes to images in content
 *
 * @param string $content The content to filter.
 * @return string Filtered content with lazy loading attributes.
 */
function mynews_add_lazy_loading_attributes( $content ) {
    // Only apply on mobile devices
    if ( ! wp_is_mobile() ) {
        return $content;
    }
    
    // Don't apply to feeds, previews
    if ( is_feed() || is_preview() ) {
        return $content;
    }
    
    // Don't lazy-load if the content has already been processed
    if ( strpos( $content, 'data-src' ) !== false ) {
        return $content;
    }
    
    // Replace image tags with lazy loading versions
    $content = preg_replace_callback(
        '/<img\s+([^>]+)>/i',
        'mynews_lazy_load_image_callback',
        $content
    );
    
    return $content;
}

/**
 * Callback to replace image tags with lazy loading versions
 */
function mynews_lazy_load_image_callback( $matches ) {
    // Check if this is already lazy loaded
    if ( strpos( $matches[1], 'data-src' ) !== false ) {
        return $matches[0];
    }
    
    // Get the source
    if ( preg_match( '/src=["\'](.*?)["\']/i', $matches[1], $src_matches ) ) {
        $src = $src_matches[1];
        
        // Create the lazy loading version
        $lazy_markup = str_replace(
            'src="' . $src . '"',
            'src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" data-src="' . $src . '"',
            $matches[0]
        );
        
        // Also handle srcset if present
        if ( preg_match( '/srcset=["\'](.*?)["\']/i', $lazy_markup, $srcset_matches ) ) {
            $srcset = $srcset_matches[1];
            
            $lazy_markup = str_replace(
                'srcset="' . $srcset . '"',
                'data-srcset="' . $srcset . '"',
                $lazy_markup
            );
        }
        
        // Add lazy loading class
        $lazy_markup = str_replace( 'class="', 'class="lazy-loading ', $lazy_markup );
        
        // If no class exists, add one
        if ( strpos( $lazy_markup, 'class=' ) === false ) {
            $lazy_markup = str_replace( '<img ', '<img class="lazy-loading" ', $lazy_markup );
        }
        
        return $lazy_markup;
    }
    
    return $matches[0];
}

// Grid layout fixes for blog posts
	if ( is_home() || is_archive() || is_search() ) {
		wp_enqueue_script( 'mynews-grid-fixes', get_template_directory_uri() . '/assets/js/grid-fixes.js', array('jquery'), MYNEWS_VERSION, array(
			'strategy' => 'defer',
			'in_footer' => true,
		) );
		
		// Pass customizer settings to the script
		wp_localize_script( 'mynews-grid-fixes', 'myNewsGridSettings', array(
			'postsPerRow' => get_theme_mod('mynews_posts_per_row', '3'),
		) );
	}

/**
 * Register Breaking News custom post type
 */
function mynews_register_breaking_news_post_type() {
    $labels = array(
        'name'               => _x( 'Breaking News', 'post type general name', 'mynews' ),
        'singular_name'      => _x( 'Breaking News', 'post type singular name', 'mynews' ),
        'menu_name'          => _x( 'Breaking News', 'admin menu', 'mynews' ),
        'name_admin_bar'     => _x( 'Breaking News', 'add new on admin bar', 'mynews' ),
        'add_new'            => _x( 'Add New', 'breaking news', 'mynews' ),
        'add_new_item'       => __( 'Add New Breaking News', 'mynews' ),
        'new_item'           => __( 'New Breaking News', 'mynews' ),
        'edit_item'          => __( 'Edit Breaking News', 'mynews' ),
        'view_item'          => __( 'View Breaking News', 'mynews' ),
        'all_items'          => __( 'All Breaking News', 'mynews' ),
        'search_items'       => __( 'Search Breaking News', 'mynews' ),
        'parent_item_colon'  => __( 'Parent Breaking News:', 'mynews' ),
        'not_found'          => __( 'No breaking news found.', 'mynews' ),
        'not_found_in_trash' => __( 'No breaking news found in Trash.', 'mynews' )
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-megaphone',
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'breaking-news' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 5,
        'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'breaking_news', $args );
    
    // Register taxonomy for breaking news categories
    register_taxonomy(
        'breaking_news_category',
        'breaking_news',
        array(
            'labels' => array(
                'name' => __('Categories', 'mynews'),
                'singular_name' => __('Category', 'mynews'),
            ),
            'hierarchical' => true,
            'show_admin_column' => true,
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'breaking-news-category'),
        )
    );
    
    // Add a meta box for urgency level
    add_action('add_meta_boxes', 'mynews_add_breaking_news_meta_boxes');
    add_action('save_post_breaking_news', 'mynews_save_breaking_news_meta');
}
add_action( 'init', 'mynews_register_breaking_news_post_type' );

/**
 * Add meta boxes to the breaking news post type
 */
function mynews_add_breaking_news_meta_boxes() {
    add_meta_box(
        'mynews_breaking_news_meta',
        __('Breaking News Options', 'mynews'),
        'mynews_breaking_news_meta_callback',
        'breaking_news',
        'side',
        'default'
    );
    
    // Add a meta box to select existing posts
    add_meta_box(
        'mynews_breaking_news_select_post',
        __('Select Existing Post', 'mynews'),
        'mynews_breaking_news_select_post_callback',
        'breaking_news',
        'normal',
        'high'
    );
}

/**
 * Callback function for the breaking news meta box
 */
function mynews_breaking_news_meta_callback($post) {
    // Add a nonce field so we can check for it later
    wp_nonce_field('mynews_breaking_news_meta', 'mynews_breaking_news_meta_nonce');
    
    // Get the current value
    $urgency = get_post_meta($post->ID, '_breaking_news_urgency', true);
    $expiry_date = get_post_meta($post->ID, '_breaking_news_expiry', true);
    
    if (!$urgency) {
        $urgency = 'normal';
    }
    
    echo '<p>';
    echo '<label for="breaking_news_urgency">' . __('Urgency Level:', 'mynews') . '</label><br>';
    echo '<select id="breaking_news_urgency" name="breaking_news_urgency" style="width: 100%;">';
    echo '<option value="normal" ' . selected($urgency, 'normal', false) . '>' . __('Normal', 'mynews') . '</option>';
    echo '<option value="important" ' . selected($urgency, 'important', false) . '>' . __('Important', 'mynews' ) . '</option>';
    echo '<option value="urgent" ' . selected($urgency, 'urgent', false) . '>' . __('Urgent', 'mynews' ) . '</option>';
    echo '</select>';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="breaking_news_expiry">' . __('Expiry Date:', 'mynews') . '</label><br>';
    echo '<input type="date" id="breaking_news_expiry" name="breaking_news_expiry" value="' . esc_attr($expiry_date) . '" style="width: 100%;">';
    echo '<small>' . __('Leave empty for no expiry', 'mynews') . '</small>';
    echo '</p>';
}

/**
 * Callback function for the select existing post meta box
 */
function mynews_breaking_news_select_post_callback($post) {
    // Add a nonce field so we can check for it later
    wp_nonce_field('mynews_breaking_news_select_post', 'mynews_breaking_news_select_post_nonce');
    
    // Get the current value
    $linked_post_id = get_post_meta($post->ID, '_linked_post_id', true);
    
    echo '<p>';
    echo '<label for="linked_post_search">' . __('Search for a post:', 'mynews') . '</label><br>';
    echo '<input type="text" id="linked_post_search" class="widefat" placeholder="' . esc_attr__('Start typing to search posts...', 'mynews') . '">';
    echo '</p>';
    
    echo '<div id="linked_post_results" class="linked-post-results"></div>';
    
    echo '<p>';
    echo '<label for="linked_post_id">' . __('Selected Post:', 'mynews') . '</label><br>';
    
    if ($linked_post_id) {
        $linked_post = get_post($linked_post_id);
        if ($linked_post) {
            echo '<div class="linked-post-selected">';
            echo '<input type="hidden" id="linked_post_id" name="linked_post_id" value="' . esc_attr($linked_post_id) . '">';
            echo '<strong>' . esc_html($linked_post->post_title) . '</strong>';
            echo ' <a href="#" class="remove-linked-post" style="color: red; text-decoration: none;">×</a>';
            echo '<br><small>' . get_the_date('', $linked_post_id) . ' - ' . get_post_type_object(get_post_type($linked_post_id))->labels->singular_name . '</small>';
            echo '</div>';
        } else {
            echo '<input type="hidden" id="linked_post_id" name="linked_post_id" value="">';
            echo '<em>' . __('No post selected', 'mynews') . '</em>';
        }
    } else {
        echo '<input type="hidden" id="linked_post_id" name="linked_post_id" value="">';
        echo '<em>' . __('No post selected', 'mynews') . '</em>';
    }
    echo '</p>';
    
    echo '<p class="description">';
    echo __('If you select an existing post, this breaking news item will link to that post rather than having its own content.', 'mynews');
    echo '</p>';

    // Add JavaScript for post search and selection
    ?>
    <script>
    jQuery(document).ready(function($) {
        let searchTimeout;
        
        // Handle post search
        $('#linked_post_search').on('keyup', function() {
            const searchTerm = $(this).val();
            clearTimeout(searchTimeout);
            
            if (searchTerm.length < 3) {
                $('#linked_post_results').html('');
                return;
            }
            
            searchTimeout = setTimeout(function() {
                $('#linked_post_results').html('<p>Searching...</p>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'mynews_search_posts',
                        security: '<?php echo wp_create_nonce("mynews-search-posts-nonce"); ?>',
                        search: searchTerm
                    },
                    success: function(response) {
                        if (response.success) {
                            let html = '';
                            
                            if (response.data.length > 0) {
                                html = '<ul class="linked-posts-list">';
                                
                                response.data.forEach(function(post) {
                                    html += '<li data-id="' + post.id + '" data-title="' + post.title + '" data-date="' + post.date + '" data-type="' + post.type + '">';
                                    html += '<strong>' + post.title + '</strong><br>';
                                    html += '<small>' + post.date + ' - ' + post.type_label + '</small>';
                                    html += '</li>';
                                });
                                
                                html += '</ul>';
                            } else {
                                html = '<p>No posts found.</p>';
                            }
                            
                            $('#linked_post_results').html(html);
                        } else {
                            $('#linked_post_results').html('<p>Error: ' + response.data + '</p>');
                        }
                    },
                    error: function() {
                        $('#linked_post_results').html('<p>Error connecting to server.</p>');
                    }
                });
            }, 500);
        });
        
        // Handle post selection
        $(document).on('click', '.linked-posts-list li', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const date = $(this).data('date');
            const type = $(this).data('type');
            
            $('#linked_post_id').val(id);
            $('.linked-post-selected').remove();
            
            const selectedHtml = '<div class="linked-post-selected">' +
                '<strong>' + title + '</strong> ' +
                '<a href="#" class="remove-linked-post" style="color: red; text-decoration: none;">×</a>' +
                '<br><small>' + date + ' - ' + type + '</small>' +
                '</div>';
            
            $('#linked_post_id').after(selectedHtml);
            $('#linked_post_results').html('');
            $('#linked_post_search').val('');
        });
        
        // Handle post removal
        $(document).on('click', '.remove-linked-post', function(e) {
            e.preventDefault();
            $('#linked_post_id').val('');
            $('.linked-post-selected').replaceWith('<em>No post selected</em>');
        });
    });
    </script>
    <style>
    .linked-posts-list {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        margin: 0;
    }
    
    .linked-posts-list li {
        cursor: pointer;
        padding: 5px;
        margin: 0;
    }
    
    .linked-posts-list li:hover {
        background: #f0f0f0;
    }
    
    .linked-post-results {
        margin-bottom: 15px;
    }
    </style>
    <?php
}

/**
 * Save the breaking news meta box data
 */
function mynews_save_breaking_news_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['mynews_breaking_news_meta_nonce'])) {
        return;
    }
    
    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['mynews_breaking_news_meta_nonce'], 'mynews_breaking_news_meta')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Sanitize user input
    if (isset($_POST['breaking_news_urgency'])) {
        $urgency = sanitize_text_field($_POST['breaking_news_urgency']);
        update_post_meta($post_id, '_breaking_news_urgency', $urgency);
    }
    
    if (isset($_POST['breaking_news_expiry'])) {
        $expiry = sanitize_text_field($_POST['breaking_news_expiry']);
        update_post_meta($post_id, '_breaking_news_expiry', $expiry);
    }
    
    // Save linked post ID
    if (isset($_POST['linked_post_id'])) {
        $linked_post_id = absint($_POST['linked_post_id']);
        
        if ($linked_post_id > 0) {
            update_post_meta($post_id, '_linked_post_id', $linked_post_id);
        } else {
            delete_post_meta($post_id, '_linked_post_id');
        }
    }
}

/**
 * AJAX handler for searching posts
 */
function mynews_search_posts_ajax() {
    // Check nonce
    check_ajax_referer('mynews-search-posts-nonce', 'security');
    
    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('You do not have permission to search posts.');
        return;
    }
    
    $search = sanitize_text_field($_POST['search']);
    
    if (strlen($search) < 3) {
        wp_send_json_error('Search term too short.');
        return;
    }
    
    $args = array(
        'post_type'      => array('post', 'page'),
        'post_status'    => 'publish',
        'posts_per_page' => 10,
        's'              => $search,
    );
    
    $posts_query = new WP_Query($args);
    $found_posts = array();
    
    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            $post_id = get_the_ID();
            
            $found_posts[] = array(
                'id'         => $post_id,
                'title'      => get_the_title(),
                'date'       => get_the_date(),
                'type'       => get_post_type(),
                'type_label' => get_post_type_object(get_post_type())->labels->singular_name,
                'permalink'  => get_permalink(),
            );
        }
        wp_reset_postdata();
    }
    
    wp_send_json_success($found_posts);
}
add_action('wp_ajax_mynews_search_posts', 'mynews_search_posts_ajax');

/**
 * Add Breaking News meta box to regular posts
 */
function mynews_add_breaking_news_meta_to_posts() {
    // Add meta box to regular posts
    add_meta_box(
        'mynews_post_breaking_news_meta',
        __('Breaking News Options', 'mynews'),
        'mynews_post_breaking_news_meta_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'mynews_add_breaking_news_meta_to_posts');

/**
 * Callback function for the breaking news meta box in regular posts
 */
function mynews_post_breaking_news_meta_callback($post) {
    // Add a nonce field for security
    wp_nonce_field('mynews_post_breaking_news_meta', 'mynews_post_breaking_news_meta_nonce');
    
    // Get current values
    $is_breaking = get_post_meta($post->ID, '_is_breaking_news', true);
    $urgency = get_post_meta($post->ID, '_breaking_news_urgency', true);
    $expiry_date = get_post_meta($post->ID, '_breaking_news_expiry', true);
    
    if (!$urgency) {
        $urgency = 'normal';
    }
    
    echo '<p>';
    echo '<input type="checkbox" id="is_breaking_news" name="is_breaking_news" value="1" ' . checked($is_breaking, '1', false) . '>';
    echo ' <label for="is_breaking_news"><strong>' . __('Mark as Breaking News', 'mynews') . '</strong></label>';
    echo '</p>';
    
    echo '<div id="breaking_news_options">';
    
    echo '<p>';
    echo '<label for="breaking_news_urgency">' . __('Urgency Level:', 'mynews') . '</label><br>';
    echo '<select id="breaking_news_urgency" name="breaking_news_urgency" style="width: 100%;">';
    echo '<option value="normal" ' . selected($urgency, 'normal', false) . '>' . __('Normal', 'mynews') . '</option>';
    echo '<option value="important" ' . selected($urgency, 'important', false) . '>' . __('Important', 'mynews' ) . '</option>';
    echo '<option value="urgent" ' . selected($urgency, 'urgent', false) . '>' . __('Urgent', 'mynews' ) . '</option>';
    echo '</select>';
    echo '</p>';
    
    echo '<p>';
    echo '<label for="breaking_news_expiry">' . __('Show Until:', 'mynews') . '</label><br>';
    echo '<input type="date" id="breaking_news_expiry" name="breaking_news_expiry" value="' . esc_attr($expiry_date) . '" style="width: 100%;">';
    echo '<small>' . __('Leave empty to show indefinitely', 'mynews') . '</small>';
    echo '</p>';
    
    echo '</div>';
    
    // Add JavaScript to toggle options display
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#is_breaking_news').change(function() {
            if($(this).is(':checked')) {
                $('#breaking_news_options').show();
            } else {
                $('#breaking_news_options').hide();
            }
        });
    });
    </script>
    <?php
}

/**
 * Save the breaking news meta data for regular posts
 */
function mynews_save_post_breaking_news_meta($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['mynews_post_breaking_news_meta_nonce'])) {
        return;
    }
    
    // Verify the nonce
    if (!wp_verify_nonce($_POST['mynews_post_breaking_news_meta_nonce'], 'mynews_post_breaking_news_meta')) {
        return;
    }
    
    // If this is an autosave, don't do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if ('post' === $_POST['post_type'] && !current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save breaking news status
    $is_breaking = isset($_POST['is_breaking_news']) ? '1' : '';
    update_post_meta($post_id, '_is_breaking_news', $is_breaking);
    
    // Only save other meta if it's a breaking news
    if ($is_breaking) {
        // Save urgency level
        if (isset($_POST['breaking_news_urgency'])) {
            $urgency = sanitize_text_field($_POST['breaking_news_urgency']);
            update_post_meta($post_id, '_breaking_news_urgency', $urgency);
        }
        
        // Save expiry date
        if (isset($_POST['breaking_news_expiry'])) {
            $expiry = sanitize_text_field($_POST['breaking_news_expiry']);
            update_post_meta($post_id, '_breaking_news_expiry', $expiry);
        }
    }
}
add_action('save_post', 'mynews_save_post_breaking_news_meta');

/**
 * Enqueue script for AJAX Load More functionality
 */
function mynews_enqueue_ajax_load_more() {
    // Only enqueue on the main blog page
    if (is_home() || is_archive() || is_search()) {
        // Enqueue the script
        wp_enqueue_script(
            'mynews-ajax-load-more',
            MYNEWS_ASSETS_URI . '/js/ajax-load-more.js',
            array('jquery'),
            MYNEWS_VERSION,
            true
        );
        
        // Enqueue the CSS
        wp_enqueue_style(
            'mynews-ajax-load-more',
            MYNEWS_ASSETS_URI . '/css/ajax-load-more.css',
            array(),
            MYNEWS_VERSION
        );
        
        // Pass PHP variables to JavaScript
        global $wp_query;
        wp_localize_script(
            'mynews-ajax-load-more',
            'mynewsLoadMore',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('mynews_load_more_nonce'),
                'maxPages' => $wp_query->max_num_pages,
                'loadingText' => esc_html__('Loading...', 'mynews')
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'mynews_enqueue_ajax_load_more');

/**
 * AJAX handler for loading more posts
 */
function mynews_ajax_load_more_posts() {
    // Check nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'mynews_load_more_nonce')) {
        wp_send_json_error('Invalid security token');
    }
    
    // Get parameters
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $blog_layout = isset($_POST['layout']) ? sanitize_text_field($_POST['layout']) : 'grid';
    $posts_per_row = isset($_POST['posts_per_row']) ? sanitize_text_field($_POST['posts_per_row']) : '3';
    
    // Calculate Bootstrap column class based on posts per row
    $column_class = 'col-sm-12 col-md-6 col-lg-4';
    if ($posts_per_row == '2') {
        $column_class = 'col-sm-12 col-md-6';
    } elseif ($posts_per_row == '4') {
        $column_class = 'col-sm-12 col-md-6 col-lg-3';
    }
    
    // Setup query args
    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'paged' => $page,
        'posts_per_page' => get_option('posts_per_page')
    );
    
    // Apply filters to match archive conditions
    if (isset($_POST['cat'])) {
        $args['cat'] = intval($_POST['cat']);
    }
    
    if (isset($_POST['tag'])) {
        $args['tag'] = sanitize_text_field($_POST['tag']);
    }
    
    if (isset($_POST['s'])) {
        $args['s'] = sanitize_text_field($_POST['s']);
    }
    
    // Run query
    $posts_query = new WP_Query($args);
    
    ob_start();
    
    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) {
            $posts_query->the_post();
            
            if ($blog_layout === 'grid') {
                echo '<div class="' . esc_attr($column_class) . ' mb-4">';
                get_template_part('template-parts/content', 'grid');
                echo '</div>';
            } else {
                get_template_part('template-parts/content', 'list');
            }
        }
    }
    
    $html = ob_get_clean();
    wp_reset_postdata();
    
    wp_send_json_success(array(

        'html' => $html,
        'page' => $page,
        'max_pages' => $posts_query->max_num_pages
    ));
}

add_action('wp_ajax_mynews_load_more_posts', 'mynews_ajax_load_more_posts');
add_action('wp_ajax_nopriv_mynews_load_more_posts', 'mynews_ajax_load_more_posts');

/**
 * Enqueue Reading Progress Bar assets for single posts
 */
function mynews_enqueue_reading_progress_assets() {
    // Only load on single posts
    if (is_singular('post')) {
        // Enqueue dark mode CSS if needed
        wp_enqueue_style(
            'mynews-dark-mode',
            get_template_directory_uri() . '/assets/css/dark-mode.css',
            array(),
            MYNEWS_VERSION . '.' . time()
        );
        // Enqueue only the resolver script
        wp_enqueue_script(
            'mynews-reading-progress-resolver',
            get_template_directory_uri() . '/assets/js/reading-progress-resolver.js',
            array('jquery'),
            MYNEWS_VERSION . '.' . time(),
            array(
                'strategy' => 'async',
                'in_footer' => true,
            )
        );
    }
}
add_action('wp_enqueue_scripts', 'mynews_enqueue_reading_progress_assets');

/**
 * Post Reactions functionality
 */

/**
 * Enqueue post reactions styles and scripts
 */
function mynews_enqueue_post_reactions_assets() {
    // Only enqueue on single posts
    if (is_single()) {
        // Enqueue CSS
        wp_enqueue_style('mynews-post-reactions', get_template_directory_uri() . '/assets/css/post-reactions.css', array('mynews-main'), MYNEWS_VERSION);
        
        // Enqueue JS
        wp_enqueue_script('mynews-post-reactions', get_template_directory_uri() . '/assets/js/post-reactions.js', array('jquery'), MYNEWS_VERSION, true);
        
        // Localize the script with data
        wp_localize_script('mynews-post-reactions', 'mynewsReactions', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('mynews-post-reactions-nonce'),
            'loginPrompt' => __('You need to be logged in to react. Would you like to log in now?', 'mynews'),
            'loginUrl' => wp_login_url(get_permalink())
        ));
    }
}
add_action('wp_enqueue_scripts', 'mynews_enqueue_post_reactions_assets');

/**
 * AJAX handler for post reactions
 */
function mynews_handle_post_reaction() {
    // Check nonce for security
    if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'mynews-post-reactions-nonce')) {
        wp_send_json_error(array('message' => 'Security check failed'));
        return;
    }
    
    // Verify required data
    if (!isset($_POST['post_id']) || !isset($_POST['reaction'])) {
        wp_send_json_error(array('message' => 'Missing required data'));
        return;
    }
    
    $post_id = intval($_POST['post_id']);
    $reaction = sanitize_text_field($_POST['reaction']);
    
    // Make sure the post exists
    if (!get_post($post_id)) {
        wp_send_json_error(array('message' => 'Invalid post ID'));
        return;
    }
    
    // Valid reactions
    $valid_reactions = array('like', 'love', 'haha', 'wow', 'sad');
    
    if (!in_array($reaction, $valid_reactions)) {
        wp_send_json_error(array('message' => 'Invalid reaction type'));
        return;
    }
    
    // Handle reaction based on user status
    if (is_user_logged_in()) {
        // For logged in users
        $user_id = get_current_user_id();
        $result = mynews_process_user_reaction($user_id, $post_id, $reaction);
        
        if ($result) {
            wp_send_json_success(array(
                'reactions' => mynews_get_post_reactions($post_id),
                'user_reaction' => mynews_get_user_reaction($user_id, $post_id)
            ));
        } else {
            wp_send_json_error(array('message' => 'Failed to process reaction'));
        }
    } else {
        // For non-logged in users, we could track by IP or session, but for now just show a message
        wp_send_json_error(array('message' => 'User not logged in'));
    }
}
add_action('wp_ajax_mynews_handle_post_reaction', 'mynews_handle_post_reaction');
add_action('wp_ajax_nopriv_mynews_handle_post_reaction', 'mynews_handle_post_reaction');

/**
 * Process a user's reaction to a post
 *
 * @param int $user_id User ID
 * @param int $post_id Post ID
 * @param string $reaction Reaction type
 * @return bool Success or failure
 */
function mynews_process_user_reaction($user_id, $post_id, $reaction) {
    // Get stored reactions for this post
    $post_reactions = get_post_meta($post_id, '_mynews_post_reactions', true);
    if (!is_array($post_reactions)) {
        $post_reactions = array();
    }
    
    // Get user's existing reactions
    $user_reactions = get_user_meta($user_id, '_mynews_user_reactions', true);
    if (!is_array($user_reactions)) {
        $user_reactions = array();
    }
    
    // Check if user already reacted to this post
    $previous_reaction = isset($user_reactions[$post_id]) ? $user_reactions[$post_id] : '';
    
    // If user is clicking the same reaction again, it's a toggle (remove it)
    if ($previous_reaction === $reaction) {
        // Remove the reaction from user's reactions
        unset($user_reactions[$post_id]);
        
        // Decrease the reaction count
        if (isset($post_reactions[$reaction])) {
            $post_reactions[$reaction] = max(0, intval($post_reactions[$reaction]) - 1);
        }
    } else {
        // If user had a different reaction before, decrease that count
        if ($previous_reaction && isset($post_reactions[$previous_reaction])) {
            $post_reactions[$previous_reaction] = max(0, intval($post_reactions[$previous_reaction]) - 1);
        }
        
        // Add the new reaction
        $user_reactions[$post_id] = $reaction;
        
        // Increase the new reaction count
        if (!isset($post_reactions[$reaction])) {
            $post_reactions[$reaction] = 0;
        }
        $post_reactions[$reaction] = intval($post_reactions[$reaction]) + 1;
    }
    
    // Update the post meta
    update_post_meta($post_id, '_mynews_post_reactions', $post_reactions);
    
    // Update user meta
    update_user_meta($user_id, '_mynews_user_reactions', $user_reactions);
    
    return true;
}

/**
 * Get all reactions for a post
 *
 * @param int $post_id Post ID
 * @return array Reactions with counts
 */
function mynews_get_post_reactions($post_id) {
    $reactions = get_post_meta($post_id, '_mynews_post_reactions', true);
    
    if (!is_array($reactions)) {
        $reactions = array();
    }
    
    // Ensure all reaction types are set
    $valid_reactions = array('like', 'love', 'haha', 'wow', 'sad');
    foreach ($valid_reactions as $type) {
        if (!isset($reactions[$type])) {
            $reactions[$type] = 0;
        }
    }
    
    return $reactions;
}

/**
 * Get a user's reaction to a specific post
 *
 * @param int $user_id User ID
 * @param int $post_id Post ID
 * @return string|null The reaction or null if none
 */
function mynews_get_user_reaction($user_id, $post_id) {
    $user_reactions = get_user_meta($user_id, '_mynews_user_reactions', true);
    
    if (!is_array($user_reactions) || !isset($user_reactions[$post_id])) {
        return null;
    }
    
    return $user_reactions[$post_id];
}

/**
 * Shortcode for displaying post reactions anywhere
 * 
 * Usage: [mynews_post_reactions post_id="123"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function mynews_post_reactions_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
    ), $atts, 'mynews_post_reactions');
    
    // Start output buffering
    ob_start();
    
    // Include the template part
    set_query_var('mynews_reaction_post_id', $atts['post_id']);
    get_template_part('template-parts/post-reactions');
    set_query_var('mynews_reaction_post_id', null);
    
    // Get the buffered content and return it
    return ob_get_clean();
}
add_shortcode('mynews_post_reactions', 'mynews_post_reactions_shortcode');
