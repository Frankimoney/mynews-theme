<?php
/**
 * Custom styles for the theme based on customizer settings
 *
 * @package My_News
 */

/**
 * Output customizer CSS to wp_head
 */
function mynews_customizer_css() {
    ?>
    <style type="text/css">
        /* Customizer CSS */
        :root {
            --mynews-primary: <?php echo esc_attr(get_theme_mod('mynews_primary_color', '#0d6efd')); ?>;
            --mynews-secondary: <?php echo esc_attr(get_theme_mod('mynews_secondary_color', '#6c757d')); ?>;
            --mynews-dark: <?php echo esc_attr(get_theme_mod('mynews_footer_bg_color', '#212529')); ?>;
        }
        
        /* Custom layout background colors */
        <?php if (get_theme_mod('mynews_theme_layout', 'full-width') === 'boxed') : ?>
        body.boxed-layout {
            background-color: #f0f0f0;
        }
        <?php endif; ?>
        
        /* Footer styles */
        .site-footer {
            background-color: <?php echo esc_attr(get_theme_mod('mynews_footer_bg_color', '#212529')); ?>;
            color: <?php echo esc_attr(get_theme_mod('mynews_footer_text_color', 'rgba(255, 255, 255, 0.6)')); ?>;
        }
        
        .site-footer a {
            color: <?php echo esc_attr(get_theme_mod('mynews_footer_text_color', 'rgba(255, 255, 255, 0.6)')); ?>;
        }
        
        .site-footer .widget-title:after,
        .site-footer .footer-widget-title:after {
            background-color: <?php echo esc_attr(get_theme_mod('mynews_footer_accent_color', '#0d6efd')); ?>;
        }
        
        .site-footer .footer-contact li i {
            color: <?php echo esc_attr(get_theme_mod('mynews_footer_accent_color', '#0d6efd')); ?>;
        }
        
        .site-footer .btn-primary {
            background-color: <?php echo esc_attr(get_theme_mod('mynews_footer_accent_color', '#0d6efd')); ?>;
            border-color: <?php echo esc_attr(get_theme_mod('mynews_footer_accent_color', '#0d6efd')); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'mynews_customizer_css');
