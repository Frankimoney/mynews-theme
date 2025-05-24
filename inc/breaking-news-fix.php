<?php
/**
 * Add extra CSS to fix breaking news ticker
 */
function mynews_add_breaking_news_fix() {
    wp_enqueue_style(
        'breaking-news-fix',
        get_template_directory_uri() . '/assets/css/breaking-news-fix.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/breaking-news-fix.css')
    );
}
add_action('wp_enqueue_scripts', 'mynews_add_breaking_news_fix', 20);
