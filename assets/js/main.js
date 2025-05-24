/**
 * Main JavaScript file for the MyNews WordPress theme
 * Contains general purpose functionality and initializations
 */

(function($) {
    'use strict';
    
    // Document ready
    $(document).ready(function() {
        // Initialize tooltips
        initTooltips();
        
        // Initialize popovers
        initPopovers();
        
        // Initialize sticky header behavior
        initStickyHeader();
        
        // Initialize custom animations
        initAnimations();
        
        // Initialize responsive video containers
        makeVideosResponsive();
    });

    /**
     * Initialize Bootstrap tooltips
     */
    function initTooltips() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            });
        });
    }
    
    /**
     * Initialize Bootstrap popovers
     */
    function initPopovers() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }
      /**
     * Add sticky header behavior with animation
     */
    function initStickyHeader() {
        var header = $('.site-header');
        var headerHeight = header.outerHeight();
        var scrollPosition = $(window).scrollTop();
        
        // Initial check for page refresh
        if (scrollPosition > headerHeight) {
            header.addClass('sticky-top');
            header.addClass('sticky-header');
            $('body').css('padding-top', headerHeight + 'px');
        }
        
        $(window).on('scroll', function() {
            scrollPosition = $(this).scrollTop();
            
            if (scrollPosition > headerHeight) {
                header.addClass('sticky-top');
                header.addClass('sticky-header');
                $('body').css('padding-top', headerHeight + 'px');
                
                // Add compact styling for better appearance when scrolling
                if (scrollPosition > headerHeight * 2) {
                    header.addClass('compact-header');
                } else {
                    header.removeClass('compact-header');
                }
            } else {
                header.removeClass('sticky-top');
                header.removeClass('sticky-header');
                header.removeClass('compact-header');
                $('body').css('padding-top', 0);
            }
        });
        
        // Add search form toggle functionality
        $('.search-toggle').on('click', function(e) {
            e.preventDefault();
            $('#searchCollapse').collapse('toggle');
            $('#searchCollapse .search-field').focus();
        });
    }
    
    /**
     * Initialize scroll animations using Intersection Observer
     */
    function initAnimations() {
        // Check if IntersectionObserver is supported
        if ('IntersectionObserver' in window) {
            const animatedElements = document.querySelectorAll('.animate-on-scroll');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animated');
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1
            });
            
            animatedElements.forEach(element => {
                observer.observe(element);
            });
        }
    }
    
    /**
     * Make embedded videos responsive
     */
    function makeVideosResponsive() {
        $('.entry-content iframe[src*="youtube"], .entry-content iframe[src*="vimeo"]')
            .wrap('<div class="ratio ratio-16x9"></div>');
    }

    /**
     * Debounce function for performance optimization
     */
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }
})(jQuery);
