<?php
/**
 * My News Theme Customizer
 *
 * @package My_News
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
 
/**
 * Always show breaking news section in customizer
 * This ensures the section is visible even if no breaking news posts exist yet
 */
add_filter('customize_section_active', 'mynews_always_show_breaking_news_section', 10, 2);

function mynews_always_show_breaking_news_section($active, $section) {
    if ($section->id === 'mynews_breaking_news_section') {
        return true;
    }
    return $active;
}
/**
 * Sanitize checkbox values
 */
function mynews_sanitize_checkbox( $checked ) {
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Sanitize layout options
 */
function mynews_sanitize_layout( $input ) {
	$valid = array(
		'full-width' => 'Full Width',
		'boxed'      => 'Boxed',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return 'full-width';
}

/**
 * Sanitize blog layout options
 */
function mynews_sanitize_blog_layout( $input ) {
	$valid = array(
		'grid' => 'Grid Layout',
		'list' => 'List Layout',
	);

	if ( array_key_exists( $input, $valid ) ) {
		return $input;
	}

	return 'grid';
}

/**
 * Sanitize select
 */
function mynews_sanitize_select( $input, $setting ) {
    // Get list of choices from the control associated with the setting.
    $choices = $setting->manager->get_control( $setting->id )->choices;
    
    // If the input is a valid key, return it; otherwise, return the default.
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

function mynews_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'mynews_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'mynews_customize_partial_blogdescription',
			)
		);
	}
	
	// Add My News Theme Panel
	$wp_customize->add_panel('mynews_theme_panel', array(
		'title'       => __('My News Theme Settings', 'mynews'),
		'description' => __('Configure all aspects of your My News theme', 'mynews'),
		'priority'    => 30,
	));
	// Add theme color settings
	$wp_customize->add_section(
		'mynews_theme_colors',
		array(
			'title'      => __( 'Theme Colors', 'mynews' ),
			'priority'   => 30,
		)
	);

	// Add primary color setting
	$wp_customize->add_setting(
		'mynews_primary_color',
		array(
			'default'           => '#0d6efd',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_primary_color',
			array(
				'label'    => __( 'Primary Color', 'mynews' ),
				'section'  => 'mynews_theme_colors',
				'settings' => 'mynews_primary_color',
			)
		)
	);
	
	// Add secondary color setting
	$wp_customize->add_setting(
		'mynews_secondary_color',
		array(
			'default'           => '#6c757d',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_secondary_color',
			array(
				'label'    => __( 'Secondary Color', 'mynews' ),
				'section'  => 'mynews_theme_colors',
				'settings' => 'mynews_secondary_color',
			)
		)
	);
	
	// Add header background color
	$wp_customize->add_setting(
		'mynews_header_bg_color',
		array(
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_header_bg_color',
			array(
				'label'    => __( 'Header Background', 'mynews' ),
				'section'  => 'mynews_theme_colors',
				'settings' => 'mynews_header_bg_color',
			)
		)
	);
		// Footer Section
	$wp_customize->add_section(
		'mynews_footer_options',
		array(
			'title'       => __( 'Footer Options', 'mynews' ),
			'description' => __( 'Customize your footer layout and appearance', 'mynews' ),
			'priority'    => 130,
		)
	);
	
	// Footer background color
	$wp_customize->add_setting(
		'mynews_footer_bg_color',
		array(
			'default'           => '#212529',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_footer_bg_color',
			array(
				'label'    => __( 'Footer Background', 'mynews' ),
				'section'  => 'mynews_footer_options',
				'settings' => 'mynews_footer_bg_color',
			)
		)
	);
	
	// Footer text color
	$wp_customize->add_setting(
		'mynews_footer_text_color',
		array(
			'default'           => 'rgba(255, 255, 255, 0.6)',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_footer_text_color',
			array(
				'label'    => __( 'Footer Text Color', 'mynews' ),
				'section'  => 'mynews_footer_options',
				'settings' => 'mynews_footer_text_color',
			)
		)
	);
	
	// Footer accent color
	$wp_customize->add_setting(
		'mynews_footer_accent_color',
		array(
			'default'           => '#0d6efd',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'mynews_footer_accent_color',
			array(
				'label'    => __( 'Footer Accent Color', 'mynews' ),
				'section'  => 'mynews_footer_options',
				'settings' => 'mynews_footer_accent_color',
			)
		)
	);
		// Footer widget columns
	$wp_customize->add_setting(
		'mynews_footer_columns',
		array(
			'default'           => '4',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'mynews_footer_columns',
		array(
			'label'       => __( 'Footer Widget Columns', 'mynews' ),
			'description' => __( 'Choose how many columns to display in the footer', 'mynews' ),
			'section'     => 'mynews_footer_options',
			'type'        => 'select',
			'choices'     => array(
				'1' => __( '1 Column', 'mynews' ),
				'2' => __( '2 Columns', 'mynews' ),
				'3' => __( '3 Columns', 'mynews' ),
				'4' => __( '4 Columns', 'mynews' ),
			),
		)
	);
		// Layout Section
	$wp_customize->add_section(
		'mynews_layout_options',
		array(
			'title'       => __( 'Layout Options', 'mynews' ),
			'description' => __( 'Customize theme layout settings', 'mynews' ),
			'priority'    => 130,
		)
	);
		// Theme Layout
	$wp_customize->add_setting(
		'mynews_theme_layout',
		array(
			'default'           => 'full-width',
			'sanitize_callback' => 'mynews_sanitize_layout',
		)
	);

	$wp_customize->add_control(
		'mynews_theme_layout',
		array(
			'label'       => __( 'Theme Layout', 'mynews' ),
			'description' => __( 'Choose the layout style for your site', 'mynews' ),
			'section'     => 'mynews_layout_options',
			'type'        => 'radio',
			'choices'     => array(
				'full-width' => __( 'Full Width', 'mynews' ),
				'boxed'      => __( 'Boxed', 'mynews' ),
			),
		)
	);
	
	// Blog Layout
	$wp_customize->add_setting(
		'mynews_blog_layout',
		array(
			'default'           => 'grid',
			'sanitize_callback' => 'mynews_sanitize_blog_layout',
		)
	);

	$wp_customize->add_control(
		'mynews_blog_layout',
		array(
			'label'       => __( 'Blog Posts Layout', 'mynews' ),
			'description' => __( 'Choose how blog posts are displayed on archive pages', 'mynews' ),
			'section'     => 'mynews_layout_options',
			'type'        => 'radio',
			'choices'     => array(
				'grid' => __( 'Grid Layout', 'mynews' ),
				'list' => __( 'List Layout', 'mynews' ),
			),
		)
	);
	
	// Posts per row (for grid layout)
	$wp_customize->add_setting(
		'mynews_posts_per_row',
		array(
			'default'           => '3',
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'mynews_posts_per_row',
		array(
			'label'       => __( 'Posts Per Row (Grid Layout)', 'mynews' ),
			'description' => __( 'Number of posts to display per row in grid layout (2-4)', 'mynews' ),
			'section'     => 'mynews_layout_options',
			'type'        => 'select',
			'choices'     => array(
				'2' => '2',
				'3' => '3',
				'4' => '4',
			),
		)
	);
	
	// Navigation Section
	$wp_customize->add_section(
		'mynews_navigation_options',
		array(
			'title'       => __( 'Navigation Options', 'mynews' ),
			'description' => __( 'Customize navigation settings', 'mynews' ),
			'priority'    => 140,
		)
	);
	
	// Back to Top Button
	$wp_customize->add_setting(
		'mynews_enable_back_to_top',
		array(
			'default'           => true,
			'sanitize_callback' => 'mynews_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'mynews_enable_back_to_top',
		array(
			'label'       => __( 'Enable Back to Top Button', 'mynews' ),
			'section'     => 'mynews_navigation_options',
			'type'        => 'checkbox',
		)
	);
	
	/**
	 * Breaking News Ticker Section
	 */	// Register breaking news theme panel if not already registered
	if (!$wp_customize->get_panel('mynews_theme_panel')) {
		$wp_customize->add_panel('mynews_theme_panel', array(
			'title'       => __('My News Theme Settings', 'mynews'),
			'description' => __('Configure all aspects of your My News theme', 'mynews'),
			'priority'    => 30,
		));
	}

	// Register breaking news section
	$wp_customize->add_section('mynews_breaking_news_section', array(
		'title'       => __('Breaking News Ticker', 'mynews'),
		'description' => __('Configure the breaking news ticker display and behavior', 'mynews'),
		'priority'    => 30,
		'panel'       => 'mynews_theme_panel',
	));
		// Enable/Disable Breaking News Ticker
	$wp_customize->add_setting('mynews_enable_breaking_ticker', array(
		'default'           => true,
		'sanitize_callback' => 'mynews_sanitize_checkbox',
		'transport'         => 'refresh',
	));
		$wp_customize->add_control('mynews_enable_breaking_ticker', array(
		'label'       => __('Display Breaking News Ticker', 'mynews'),
		'description' => __('Show or hide the breaking news ticker on the homepage.', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'checkbox',
	));
	
	// Show Breaking News Ticker on Single Posts
	$wp_customize->add_setting('mynews_show_ticker_on_single', array(
		'default'           => true,
		'sanitize_callback' => 'mynews_sanitize_checkbox',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_show_ticker_on_single', array(
		'label'       => __('Show on Single Posts', 'mynews'),
		'description' => __('Display the breaking news ticker on single post pages.', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'checkbox',
	));
	
	// Maximum number of breaking news items
	$wp_customize->add_setting('mynews_breaking_news_max_items', array(
		'default'           => 10,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_max_items', array(
		'label'       => __('Maximum Number of Items', 'mynews'),
		'description' => __('The maximum number of breaking news items to display in the ticker.', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min' => 1,
			'max' => 20,
			'step' => 1,
		),
	));
	
	// Display options
	$wp_customize->add_setting('mynews_breaking_news_display', array(
		'default'           => 'all',
		'sanitize_callback' => 'mynews_sanitize_select',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_display', array(
		'label'       => __('Display Source', 'mynews'),
		'description' => __('Choose which types of breaking news to display.', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'select',
		'choices'     => array(
			'all'           => __('All Breaking News Items', 'mynews'),
			'custom_only'   => __('Custom Breaking News Only', 'mynews'),
			'posts_only'    => __('Regular Posts Marked as Breaking Only', 'mynews'),
		),
	));
	
	// Breaking News Label Text
	$wp_customize->add_setting('mynews_breaking_news_label', array(
		'default'           => __('Breaking', 'mynews'),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_label', array(
		'label'    => __('Breaking News Label', 'mynews'),
		'section'  => 'mynews_breaking_news_section',
		'type'     => 'text',
	));
	
	// Breaking News Speed
	$wp_customize->add_setting('mynews_breaking_news_speed', array(
		'default'           => 5000,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_speed', array(
		'label'    => __('Ticker Speed (ms)', 'mynews'),
		'section'  => 'mynews_breaking_news_section',
		'type'     => 'number',
		'input_attrs' => array(
			'min'  => 2000,
			'max'  => 10000,
			'step' => 500,
		),
	));
	// Display Mode
	$wp_customize->add_setting('mynews_breaking_news_display_mode', array(
		'default'           => 'scroll',
		'sanitize_callback' => 'mynews_sanitize_select',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_display_mode', array(
		'label'       => __('Display Mode', 'mynews'),
		'description' => __('Choose how breaking news items should be displayed', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'select',
		'choices'     => array(
			'scroll' => __('Scrolling Text', 'mynews'),
			'fade'   => __('Fade Transition', 'mynews'),
		),
	));

	// Scrolling Mode Settings
	$wp_customize->add_setting('mynews_breaking_news_scrolling', array(
		'default'           => true,
		'sanitize_callback' => 'mynews_sanitize_checkbox',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_scrolling', array(
		'label'       => __('Enable Scrolling Text Effect', 'mynews'),
		'description' => __('Smoothly scroll text from right to left', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'checkbox',
		'active_callback' => function() { 
			return get_theme_mod('mynews_breaking_news_display_mode', 'scroll') === 'scroll';
		},
	));
	
	// Scrolling Speed
	$wp_customize->add_setting('mynews_breaking_news_scroll_duration', array(
		'default'           => 20000,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	));
		$wp_customize->add_control('mynews_breaking_news_scroll_duration', array(
		'label'       => __('Scrolling Duration (ms)', 'mynews'),
		'description' => __('How long it takes for text to scroll across the screen (higher = slower)', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 5000,
			'max'  => 60000,
			'step' => 1000,
		),
		'active_callback' => function() { 
			return get_theme_mod('mynews_breaking_news_display_mode', 'scroll') === 'scroll' 
				&& get_theme_mod('mynews_breaking_news_scrolling', true);
		},
	));
	
	// Font Size
	$wp_customize->add_setting('mynews_breaking_news_font_size', array(
		'default'           => '0.95',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control('mynews_breaking_news_font_size', array(
		'label'       => __('Font Size (rem)', 'mynews'),
		'description' => __('Size of the ticker text relative to base font size', 'mynews'),
		'section'     => 'mynews_breaking_news_section',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 0.5,
			'max'  => 2.0,
			'step' => 0.05,
		),
	));
	
	// Ticker Background Color
	$wp_customize->add_setting('mynews_breaking_news_bg_color', array(
		'default'           => '#f8f9fa',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mynews_breaking_news_bg_color', array(
		'label'    => __('Ticker Background Color', 'mynews'),
		'section'  => 'mynews_breaking_news_section',
	)));
	
	// Ticker Label Color
	$wp_customize->add_setting('mynews_breaking_news_label_color', array(
		'default'           => '#0d6efd',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	));
	
	$wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'mynews_breaking_news_label_color', array(
		'label'    => __('Label Background Color', 'mynews'),
		'section'  => 'mynews_breaking_news_section',
	)));
}
add_action( 'customize_register', 'mynews_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function mynews_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function mynews_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function mynews_customize_preview_js() {
	wp_enqueue_script( 'mynews-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), MYNEWS_VERSION, true );
}
add_action( 'customize_preview_init', 'mynews_customize_preview_js' );
