<?php
/**
 * MyNews Theme Ad Settings Customizer
 *
 * @package My_News
 */

/**
 * Add ad controls to customizer
 */
function mynews_ad_customize_register( $wp_customize ) {
    // Add Ad Settings Section
    $wp_customize->add_section(
        'mynews_ad_settings',
        array(
            'title'       => esc_html__( 'Advertisement Settings', 'mynews' ),
            'description' => esc_html__( 'Configure premium ad placement settings throughout the theme.', 'mynews' ),
            'priority'    => 90,
        )
    );

    // Global Ad Toggle
    $wp_customize->add_setting(
        'mynews_enable_ads',
        array(
            'default'           => true,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_enable_ads',
        array(
            'label'       => esc_html__( 'Enable Ad Placements', 'mynews' ),
            'description' => esc_html__( 'Master switch to enable or disable all ad placements.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );

    // Show Ad Labels
    $wp_customize->add_setting(
        'mynews_show_ad_labels',
        array(
            'default'           => true,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_show_ad_labels',
        array(
            'label'       => esc_html__( 'Show "Advertisement" Labels', 'mynews' ),
            'description' => esc_html__( 'Display "Advertisement" text above each ad placement for regulatory compliance.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );

    // Hide Ads for Admin
    $wp_customize->add_setting(
        'mynews_hide_ads_admin',
        array(
            'default'           => false,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_hide_ads_admin',
        array(
            'label'       => esc_html__( 'Hide Ads for Admin Users', 'mynews' ),
            'description' => esc_html__( 'Hide all ad placements when logged in as administrator.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );

    // Individual Ad Placement Controls
    $ad_placements = array(
        'header'        => esc_html__( 'Header Ad (below navigation)', 'mynews' ),
        'below_title'   => esc_html__( 'Below Post Title Ad', 'mynews' ),
        'mid_content'   => esc_html__( 'Mid Content Ad', 'mynews' ),
        'after_content' => esc_html__( 'After Content Ad', 'mynews' ),
        'sidebar_top'   => esc_html__( 'Sidebar Top Ad', 'mynews' ),
        'sidebar_bottom' => esc_html__( 'Sidebar Bottom Ad', 'mynews' ),
        'footer'        => esc_html__( 'Footer Ad', 'mynews' ),
    );

    foreach ( $ad_placements as $id => $label ) {
        $setting_id = 'mynews_enable_' . $id . '_ad';

        $wp_customize->add_setting(
            $setting_id,
            array(
                'default'           => true,
                'sanitize_callback' => 'mynews_sanitize_checkbox',
                'transport'         => 'refresh',
            )
        );

        $wp_customize->add_control(
            $setting_id,
            array(
                'label'       => $label,
                'description' => sprintf( esc_html__( 'Enable the %s placement.', 'mynews' ), strtolower($label) ),
                'section'     => 'mynews_ad_settings',
                'type'        => 'checkbox',
            )
        );
    }

    // Ad Density Control
    $wp_customize->add_setting(
        'mynews_ad_density',
        array(
            'default'           => 'balanced',
            'sanitize_callback' => 'mynews_sanitize_ad_density',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_ad_density',
        array(
            'label'       => esc_html__( 'Ad Density', 'mynews' ),
            'description' => esc_html__( 'Control the overall density of ads throughout the site.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'select',
            'choices'     => array(
                'minimal'   => esc_html__( 'Minimal - Fewer ads', 'mynews' ),
                'balanced'  => esc_html__( 'Balanced - Recommended setting', 'mynews' ),
                'monetized' => esc_html__( 'Monetized - More ads', 'mynews' ),
            ),
        )
    );    // Article Paragraph Count Before Mid Content Ad
    $wp_customize->add_setting(
        'mynews_paragraph_count_before_ad',
        array(
            'default'           => 4,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_paragraph_count_before_ad',
        array(
            'label'       => esc_html__( 'Paragraphs Before Mid-Content Ad', 'mynews' ),
            'description' => esc_html__( 'Number of paragraphs to display before inserting the mid-content ad.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 2,
                'max' => 10,
                'step' => 1,
            ),
        )
    );
    
    // Add a separator heading for Advanced Ad Features
    $wp_customize->add_setting(
        'mynews_advanced_ad_features_heading',
        array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Heading_Control(
            $wp_customize,
            'mynews_advanced_ad_features_heading',
            array(
                'label'       => esc_html__( 'Advanced Ad Features', 'mynews' ),
                'section'     => 'mynews_ad_settings',
                'settings'    => 'mynews_advanced_ad_features_heading',
            )
        )
    );
    
    // STICKY ADS SETTINGS
    
    // Enable Sticky Sidebar Ad
    $wp_customize->add_setting(
        'mynews_enable_sticky_sidebar_ad',
        array(
            'default'           => false,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_enable_sticky_sidebar_ad',
        array(
            'label'       => esc_html__( 'Enable Sticky Sidebar Ad', 'mynews' ),
            'description' => esc_html__( 'Make the top sidebar ad stick when scrolling for higher visibility.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );
    
    // Sticky Ad Settings - Distance from Top
    $wp_customize->add_setting(
        'mynews_sticky_ad_top_offset',
        array(
            'default'           => 80,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_sticky_ad_top_offset',
        array(
            'label'       => esc_html__( 'Sticky Ad Top Offset (px)', 'mynews' ),
            'description' => esc_html__( 'Distance from the top of the viewport for sticky ads.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 0,
                'max' => 300,
                'step' => 5,
            ),
            'active_callback' => function() {
                return get_theme_mod('mynews_enable_sticky_sidebar_ad', false);
            },
        )
    );
    
    // IN-FEED ADS SETTINGS
    
    // Enable In-Feed Ads
    $wp_customize->add_setting(
        'mynews_enable_infeed_ads',
        array(
            'default'           => false,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_enable_infeed_ads',
        array(
            'label'       => esc_html__( 'Enable In-Feed Ads', 'mynews' ),
            'description' => esc_html__( 'Insert ads between posts in archive, category, and home pages.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );
    
    // In-Feed Ad Position
    $wp_customize->add_setting(
        'mynews_infeed_position',
        array(
            'default'           => 3,
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_infeed_position',
        array(
            'label'       => esc_html__( 'In-Feed Ad Position', 'mynews' ),
            'description' => esc_html__( 'Insert in-feed ad after this number of posts.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'number',
            'input_attrs' => array(
                'min' => 1,
                'max' => 10,
                'step' => 1,
            ),
            'active_callback' => function() {
                return get_theme_mod('mynews_enable_infeed_ads', false);
            },
        )
    );
    
    // In-Feed Ad Repeat
    $wp_customize->add_setting(
        'mynews_infeed_repeat',
        array(
            'default'           => true,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_infeed_repeat',
        array(
            'label'       => esc_html__( 'Repeat In-Feed Ads', 'mynews' ),
            'description' => esc_html__( 'Insert additional in-feed ads after the same number of posts.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('mynews_enable_infeed_ads', false);
            },
        )
    );
    
    // VIDEO AD SETTINGS
    
    // Enable Video Ad Support
    $wp_customize->add_setting(
        'mynews_enable_video_ads',
        array(
            'default'           => false,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_enable_video_ads',
        array(
            'label'       => esc_html__( 'Enable Video Ad Support', 'mynews' ),
            'description' => esc_html__( 'Add support for video advertisements in ad placements.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
        )
    );
    
    // Video Ad Position
    $wp_customize->add_setting(
        'mynews_video_ad_position',
        array(
            'default'           => 'after-content',
            'sanitize_callback' => 'mynews_sanitize_video_ad_position',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_video_ad_position',
        array(
            'label'       => esc_html__( 'Video Ad Position', 'mynews' ),
            'description' => esc_html__( 'Choose the best placement for video advertisements.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'select',
            'choices'     => array(
                'before-content' => esc_html__( 'Before Content (Below Title)', 'mynews' ),
                'mid-content'    => esc_html__( 'Mid Content', 'mynews' ),
                'after-content'  => esc_html__( 'After Content', 'mynews' ),
            ),
            'active_callback' => function() {
                return get_theme_mod('mynews_enable_video_ads', false);
            },
        )
    );
    
    // Autoplay Video Ads
    $wp_customize->add_setting(
        'mynews_video_autoplay',
        array(
            'default'           => false,
            'sanitize_callback' => 'mynews_sanitize_checkbox',
            'transport'         => 'refresh',
        )
    );

    $wp_customize->add_control(
        'mynews_video_autoplay',
        array(
            'label'       => esc_html__( 'Autoplay Video Ads', 'mynews' ),
            'description' => esc_html__( 'Allow video ads to autoplay (muted) when in viewport.', 'mynews' ),
            'section'     => 'mynews_ad_settings',
            'type'        => 'checkbox',
            'active_callback' => function() {
                return get_theme_mod('mynews_enable_video_ads', false);
            },
        )
    );
}

add_action( 'customize_register', 'mynews_ad_customize_register' );

/**
 * Sanitize ad density options
 */
function mynews_sanitize_ad_density( $input ) {
    $valid = array( 'minimal', 'balanced', 'monetized' );

    if ( in_array( $input, $valid ) ) {
        return $input;
    }

    return 'balanced';
}

/**
 * Sanitize video ad position options
 */
function mynews_sanitize_video_ad_position( $input ) {
    $valid = array( 'before-content', 'mid-content', 'after-content' );

    if ( in_array( $input, $valid ) ) {
        return $input;
    }    return 'after-content';
}

/**
 * Register custom controls for the Customizer
 */
function mynews_register_custom_controls() {
    /**
     * Custom heading control for the customizer
     */
    if ( ! class_exists( 'WP_Customize_Heading_Control' ) && class_exists( 'WP_Customize_Control' ) ) {
        class WP_Customize_Heading_Control extends WP_Customize_Control {
            public $type = 'heading';
            
            public function render_content() {
                if ( ! empty( $this->label ) ) {
                    echo '<h4 class="customize-control-heading" style="margin-top:30px; border-top:1px solid #ddd; padding-top:20px;">' . esc_html( $this->label ) . '</h4>';
                }
                if ( ! empty( $this->description ) ) {
                    echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
                }
            }
        }
    }
}
add_action( 'customize_register', 'mynews_register_custom_controls', 1 );
