<?php
/**
 * Enqueue the ticker test script
 */
function mynews_enqueue_ticker_test_script() {
    if ( is_front_page() || is_home() ) {
        wp_enqueue_script(
            'mynews-ticker-test',
            get_template_directory_uri() . '/assets/js/ticker-test.js',
            array( 'jquery', 'mynews-breaking-news-ticker' ),
            '1.0.0',
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'mynews_enqueue_ticker_test_script', 30 );
