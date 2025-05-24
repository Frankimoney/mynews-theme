<?php
/**
 * Footer text visibility fix
 * 
 * @package My_News
 */

/**
 * Add inline style to footer headings to ensure visibility
 */
function mynews_footer_text_visibility_fix() {
    ?>
    <style type="text/css">
        /* Direct inline styles to ensure footer text is visible */
        .site-footer .widget-title,
        .site-footer h3.widget-title,
        .site-footer .footer-widget-title {
            color: white !important;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        .site-footer p,
        .site-footer .footer-contact li,
        .site-footer a {
            color: rgba(255, 255, 255, 0.8) !important;
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        .site-footer i.fas,
        .site-footer i.fab,
        .site-footer i.fa {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'mynews_footer_text_visibility_fix', 999);
