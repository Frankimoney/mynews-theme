/**
 * Theme custom scripts
 * 
 * Contains all custom functionality for the My News theme
 */

jQuery(document).ready(function($) {
    'use strict';

    // Initialize tooltips (Bootstrap feature)
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Smooth scroll for anchor links
    $('a[href*="#"]:not([href="#"]):not([href="#0"]):not([data-bs-toggle])').click(function(event) {
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 100
                }, 800);
            }
        }
    });

    // Back to top button
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').addClass('show');
        } else {
            $('.back-to-top').removeClass('show');
        }
    });

    $('.back-to-top').click(function(e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, 800);
        return false;
    });

    // Add active class to current menu item
    var currentUrl = window.location.href;
    $('.navbar-nav li a').each(function() {
        if ($(this).attr('href') === currentUrl) {
            $(this).closest('li').addClass('active');
        }
    });

    // Add Bootstrap classes to WordPress elements
    $('.wp-block-button__link').addClass('btn btn-primary');
    $('.comment-reply-link').addClass('btn btn-sm btn-outline-primary');
    $('.page-numbers').addClass('page-link');
    $('.page-numbers.current').addClass('active');
    $('.wp-block-image img').addClass('img-fluid');
    $('table').addClass('table');
    $('.widget select, .wp-block-archives select, .wp-block-categories select').addClass('form-select');
    $('.search-form .search-field').addClass('form-control');
    $('.search-form .search-submit').addClass('btn btn-primary ms-2');
    $('.search-form').addClass('d-flex');

    // Add aria-current attribute to active menu items
    $('.current-menu-item > a, .current_page_item > a').attr('aria-current', 'page');
});
