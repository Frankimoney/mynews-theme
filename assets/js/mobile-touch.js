/**
 * Mobile Touch Detection and Optimization
 * 
 * This script detects touch devices and improves interaction by:
 * 1. Adding appropriate body classes
 * 2. Implementing mobile-specific gesture support
 * 3. Optimizing tap/click events for responsiveness
 * 4. Handling mobile-specific navigation behaviors
 */

(function($) {
    'use strict';
    
    // Touch detection
    var isTouchDevice = false;
    
    function detectTouch() {
        if (('ontouchstart' in window) || 
            (navigator.maxTouchPoints > 0) || 
            (navigator.msMaxTouchPoints > 0)) {
            return true;
        }
        return false;
    }
    
    function setupTouchOptimizations() {
        isTouchDevice = detectTouch();
        
        if (isTouchDevice) {
            $('body').addClass('touch-device');
            
            // Fix 300ms delay on iOS
            FastClick.attach(document.body);
            
            // Better focus handling for inputs to avoid zoom on iOS
            $('input, select, textarea').on('touchstart', function(e) {
                if ($(this).is('select') || $(this).is('input[type="date"]')) {
                    return;
                }
                if ($(this).attr('readonly') || $(this).attr('disabled')) {
                    e.preventDefault();
                }
            });
            
            // Enhance dropdown menus for touch
            $('.menu-item-has-children > a, .dropdown-toggle').on('touchend', function(e) {
                var $parent = $(this).parent();
                
                if (!$parent.hasClass('show')) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other open menus
                    $('.menu-item-has-children.show').removeClass('show');
                    $('.dropdown.show').removeClass('show');
                    $('.dropdown-menu.show').removeClass('show');
                    
                    // Open this menu
                    $parent.addClass('show');
                    $parent.find('> .dropdown-menu, > .sub-menu').addClass('show');
                }
            });
            
            // Close dropdown when clicking outside
            $(document).on('touchend', function(e) {
                if ($(e.target).closest('.menu-item-has-children, .dropdown').length === 0) {
                    $('.menu-item-has-children.show, .dropdown.show').removeClass('show');
                    $('.dropdown-menu.show, .sub-menu.show').removeClass('show');
                }
            });
            
            // Add swipe support for carousel
            $('.carousel').each(function() {
                var carousel = $(this);
                
                carousel.on('touchstart', function(e) {
                    var touch = e.originalEvent.touches[0];
                    $(this).data('touchstartX', touch.clientX);
                    $(this).data('touchstartY', touch.clientY);
                });
                
                carousel.on('touchend', function(e) {
                    var touch = e.originalEvent.changedTouches[0];
                    var touchEndX = touch.clientX;
                    var touchStartX = $(this).data('touchstartX');
                    var touchEndY = touch.clientY;
                    var touchStartY = $(this).data('touchstartY');
                    
                    // Calculate distance
                    var distanceX = touchEndX - touchStartX;
                    var distanceY = Math.abs(touchEndY - touchStartY);
                    
                    // Only handle horizontal swipes
                    if (distanceY < 50) {
                        if (distanceX > 50) { // Right swipe
                            $(this).carousel('prev');
                        } else if (distanceX < -50) { // Left swipe
                            $(this).carousel('next');
                        }
                    }
                });
            });
        } else {
            $('body').addClass('no-touch-device');
        }
        
        // Always optimize for either touch or non-touch
        optimizeInteractions();
    }
    
    function optimizeInteractions() {
        // Prevent double-tap zoom on navigation for iOS
        $('.nav-link, .btn, button, .page-link, a.card').on('touchend', function(e) {
            if (isTouchDevice) {
                $(this).trigger('focus');
            }
        });
        
        // Improve scroll performance on touch devices
        if (isTouchDevice) {
            $('.navbar').addClass('will-change-transform');
            
            // Handle mobile bottom navigation bar 
            if ($(window).width() < 768 && $('.mobile-bottom-nav').length) {
                $('body').addClass('has-mobile-nav');
            }
            
            // Disable hover effects that can cause performance issues
            $('body').addClass('hover-effects-disabled');
        }
        
        // Custom mobile menu toggle behavior
        $('.navbar-toggler').on('click', function() {
            if ($('body').hasClass('menu-open')) {
                $('body').removeClass('menu-open');
            } else {
                $('body').addClass('menu-open');
            }
        });
        
        // Add support for iOS "Add to Home Screen" web app mode
        if (("standalone" in window.navigator) && window.navigator.standalone) {
            $('body').addClass('ios-standalone-mode');
            
            // Handle in-app navigation for standalone mode
            $(document).on('click', 'a', function(e) {
                var link = $(this);
                var href = link.attr('href');
                
                if (!href.startsWith('#') && href.indexOf('http') !== 0 && !link.hasClass('no-app-link')) {
                    e.preventDefault();
                    window.location = href;
                }
            });
        }
    }
    
    // Add back-to-top functionality optimized for mobile
    function setupMobileBackToTop() {
        var $backToTop = $('#back-to-top');
        
        if ($backToTop.length) {
            // Show/hide button based on scroll position
            $(window).on('scroll', function() {
                if ($(this).scrollTop() > 300) {
                    $backToTop.addClass('visible');
                } else {
                    $backToTop.removeClass('visible');
                }
            });
            
            // Smooth scroll to top
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
                return false;
            });
        }
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Detect touch capabilities and apply optimizations
        setupTouchOptimizations();
        
        // Set up mobile-friendly back-to-top functionality
        setupMobileBackToTop();
        
        // Handle orientation change
        $(window).on('orientationchange', function() {
            // Adjust UI elements after orientation change
            setTimeout(function() {
                if (window.innerHeight > window.innerWidth) {
                    $('body').removeClass('landscape').addClass('portrait');
                } else {
                    $('body').removeClass('portrait').addClass('landscape');
                }
            }, 200);
        }).trigger('orientationchange');
    });
    
})(jQuery);
