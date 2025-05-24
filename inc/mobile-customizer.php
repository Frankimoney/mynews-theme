<?php
/**
 * Mobile Optimization Customizer Options
 *
 * @package My_News
 */

/**
 * Add mobile optimization options to the customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function mynews_mobile_customizer_options( $wp_customize ) {

    // Mobile Optimization Section
    $wp_customize->add_section( 'mynews_mobile_settings', array(
        'title'       => __( 'Mobile Optimization', 'mynews' ),
        'description' => __( 'Settings for mobile device optimization.', 'mynews' ),
        'priority'    => 35,
    ) );
    
    // Enable Mobile Web App Mode
    $wp_customize->add_setting( 'mynews_enable_mobile_app_mode', array(
        'default'           => false,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_enable_mobile_app_mode', array(
        'label'       => __( 'Enable Web App Mode', 'mynews' ),
        'description' => __( 'Allow users to add your site to their home screen as an app.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Mobile Bottom Navigation
    $wp_customize->add_setting( 'mynews_enable_mobile_bottom_nav', array(
        'default'           => false,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_enable_mobile_bottom_nav', array(
        'label'       => __( 'Enable Mobile Bottom Navigation', 'mynews' ),
        'description' => __( 'Add a fixed navigation bar at the bottom of the screen on mobile devices.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Bottom Navigation Items
    $wp_customize->add_setting( 'mynews_mobile_nav_home', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_nav_home', array(
        'label'       => __( 'Home Button', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod( 'mynews_enable_mobile_bottom_nav', false );
        },
    ) );
    
    $wp_customize->add_setting( 'mynews_mobile_nav_search', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_nav_search', array(
        'label'       => __( 'Search Button', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod( 'mynews_enable_mobile_bottom_nav', false );
        },
    ) );
    
    $wp_customize->add_setting( 'mynews_mobile_nav_categories', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_nav_categories', array(
        'label'       => __( 'Categories Button', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
        'active_callback' => function() {
            return get_theme_mod( 'mynews_enable_mobile_bottom_nav', false );
        },
    ) );
    
    // Mobile Font Sizes
    $wp_customize->add_setting( 'mynews_mobile_font_size', array(
        'default'           => 16,
        'sanitize_callback' => 'absint',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_font_size', array(
        'label'       => __( 'Base Font Size (Mobile)', 'mynews' ),
        'description' => __( 'Base font size in pixels for mobile devices.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'number',
        'input_attrs' => array(
            'min'  => 14,
            'max'  => 22,
            'step' => 1,
        ),
    ) );
    
    // Mobile Header Style
    $wp_customize->add_setting( 'mynews_mobile_header_style', array(
        'default'           => 'default',
        'sanitize_callback' => 'mynews_sanitize_mobile_header',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_header_style', array(
        'label'       => __( 'Mobile Header Style', 'mynews' ),
        'description' => __( 'Choose the header style for mobile devices.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'select',
        'choices'     => array(
            'default'    => __( 'Default Header', 'mynews' ),
            'centered'   => __( 'Centered Logo', 'mynews' ),
            'minimal'    => __( 'Minimal (Logo Only)', 'mynews' ),
            'fixed'      => __( 'Fixed Header', 'mynews' ),
        ),
    ) );
    
    // Mobile Dark Mode Toggle
    $wp_customize->add_setting( 'mynews_enable_mobile_dark_mode', array(
        'default'           => false,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_enable_mobile_dark_mode', array(
        'label'       => __( 'Enable Dark Mode Toggle', 'mynews' ),
        'description' => __( 'Add a dark mode toggle button for mobile users.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Mobile Image Loading
    $wp_customize->add_setting( 'mynews_mobile_lazy_load', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_lazy_load', array(
        'label'       => __( 'Lazy Load Images', 'mynews' ),
        'description' => __( 'Load images only as they enter the viewport to improve page speed.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Show Recent Searches in Search Modal
    $wp_customize->add_setting( 'mynews_show_recent_searches', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_show_recent_searches', array(
        'label'       => __( 'Show Recent Searches', 'mynews' ),
        'description' => __( 'Display recent search terms in the mobile search modal.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Show Popular Topics in Search Modal
    $wp_customize->add_setting( 'mynews_show_popular_topics', array(
        'default'           => true,
        'sanitize_callback' => 'mynews_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'mynews_show_popular_topics', array(
        'label'       => __( 'Show Popular Topics', 'mynews' ),
        'description' => __( 'Display popular categories in the mobile search modal.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'checkbox',
    ) );
    
    // Mobile Content Display
    $wp_customize->add_setting( 'mynews_mobile_content_display', array(
        'default'           => 'excerpt',
        'sanitize_callback' => 'mynews_sanitize_mobile_content',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_content_display', array(
        'label'       => __( 'Mobile Content Display', 'mynews' ),
        'description' => __( 'How to display post content on mobile archives.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'select',
        'choices'     => array(
            'excerpt'     => __( 'Short Excerpt', 'mynews' ),
            'medium'      => __( 'Medium Excerpt', 'mynews' ),
            'full'        => __( 'Full Content', 'mynews' ),
            'title_only'  => __( 'Title Only', 'mynews' ),
        ),
    ) );
    
    // Mobile Menu Animation
    $wp_customize->add_setting( 'mynews_mobile_menu_animation', array(
        'default'           => 'slide',
        'sanitize_callback' => 'mynews_sanitize_mobile_animation',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_menu_animation', array(
        'label'       => __( 'Mobile Menu Animation', 'mynews' ),
        'description' => __( 'Animation style for the mobile menu.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'select',
        'choices'     => array(
            'slide'  => __( 'Slide', 'mynews' ),
            'fade'   => __( 'Fade', 'mynews' ),
            'none'   => __( 'No Animation', 'mynews' ),
        ),
    ) );
    
    // Mobile Feature Image Size
    $wp_customize->add_setting( 'mynews_mobile_featured_image', array(
        'default'           => 'medium',
        'sanitize_callback' => 'mynews_sanitize_mobile_image_size',
    ) );
    
    $wp_customize->add_control( 'mynews_mobile_featured_image', array(
        'label'       => __( 'Mobile Featured Image Size', 'mynews' ),
        'description' => __( 'Image size to use for featured images on mobile devices.', 'mynews' ),
        'section'     => 'mynews_mobile_settings',
        'type'        => 'select',
        'choices'     => array(
            'small'  => __( 'Small', 'mynews' ),
            'medium' => __( 'Medium', 'mynews' ),
            'large'  => __( 'Large', 'mynews' ),
            'hidden' => __( 'Hidden', 'mynews' ),
        ),
    ) );
}
add_action( 'customize_register', 'mynews_mobile_customizer_options' );

/**
 * Sanitize mobile header options
 */
function mynews_sanitize_mobile_header( $input ) {
    $valid = array(
        'default'  => 'Default Header',
        'centered' => 'Centered Logo',
        'minimal'  => 'Minimal (Logo Only)',
        'fixed'    => 'Fixed Header',
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    }

    return 'default';
}

/**
 * Sanitize mobile content display options
 */
function mynews_sanitize_mobile_content( $input ) {
    $valid = array(
        'excerpt',
        'medium',
        'full',
        'title_only',
    );

    if ( in_array( $input, $valid ) ) {
        return $input;
    }

    return 'excerpt';
}

/**
 * Sanitize mobile animation options
 */
function mynews_sanitize_mobile_animation( $input ) {
    $valid = array(
        'slide',
        'fade',
        'none',
    );

    if ( in_array( $input, $valid ) ) {
        return $input;
    }

    return 'slide';
}

/**
 * Sanitize mobile image size options
 */
function mynews_sanitize_mobile_image_size( $input ) {
    $valid = array(
        'small',
        'medium',
        'large',
        'hidden',
    );

    if ( in_array( $input, $valid ) ) {
        return $input;
    }

    return 'medium';
}
