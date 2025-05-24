/**
 * Mobile Bottom Navigation Bar
 *
 * This file implements a responsive bottom navigation for mobile devices
 * with customizer controls for showing different elements.
 */

(function($) {
    'use strict';
    
    /**
     * Initialize the mobile bottom navigation
     */
    function initMobileBottomNav() {
        // Only run on mobile devices
        if (!isMobileDevice()) {
            return;
        }
        
        // Check if bottom nav is enabled
        if (!myNewsBottomNav.enabled) {
            return;
        }
        
        // Add body class for spacing
        $('body').addClass('has-mobile-nav');
        
        // Create the bottom navigation
        createBottomNav();
        
        // Handle click events
        handleNavClicks();
        
        // Handle scroll behavior
        handleScrollBehavior();
    }
    
    /**
     * Create the bottom nav markup
     */
    function createBottomNav() {
        var $bottomNav = $('<div class="mobile-bottom-nav"></div>');
        
        // Add home button if enabled
        if (myNewsBottomNav.showHome) {
            $bottomNav.append(
                '<a href="' + myNewsBottomNav.homeUrl + '" class="bottom-nav-item bottom-nav-home">' +
                '<i class="bi bi-house"></i>' +
                '<span>' + myNewsBottomNav.labels.home + '</span>' +
                '</a>'
            );
        }
        
        // Add search button if enabled
        if (myNewsBottomNav.showSearch) {
            $bottomNav.append(
                '<a href="#search-modal" class="bottom-nav-item bottom-nav-search" data-bs-toggle="modal">' +
                '<i class="bi bi-search"></i>' +
                '<span>' + myNewsBottomNav.labels.search + '</span>' +
                '</a>'
            );
        }
        
        // Add categories button if enabled
        if (myNewsBottomNav.showCategories) {
            $bottomNav.append(
                '<a href="#categories-modal" class="bottom-nav-item bottom-nav-categories" data-bs-toggle="modal">' +
                '<i class="bi bi-grid"></i>' +
                '<span>' + myNewsBottomNav.labels.categories + '</span>' +
                '</a>'
            );
        }
        
        // Add dark mode toggle if enabled
        if (myNewsBottomNav.showDarkMode) {
            var darkModeIcon = isDarkMode() ? 'bi-sun' : 'bi-moon';
            $bottomNav.append(
                '<a href="#" class="bottom-nav-item bottom-nav-dark-mode">' +
                '<i class="bi ' + darkModeIcon + '"></i>' +
                '<span>' + myNewsBottomNav.labels.darkMode + '</span>' +
                '</a>'
            );
        }
        
        // Add menu button to show the main menu
        $bottomNav.append(
            '<a href="#" class="bottom-nav-item bottom-nav-menu">' +
            '<i class="bi bi-list"></i>' +
            '<span>' + myNewsBottomNav.labels.menu + '</span>' +
            '</a>'
        );
        
        // Add to the body
        $('body').append($bottomNav);
        
        // Create the categories modal if enabled
        if (myNewsBottomNav.showCategories) {
            createCategoriesModal();
        }
    }
    
    /**
     * Create the categories modal
     */
    function createCategoriesModal() {
        var $modal = $(
            '<div class="modal fade" id="categories-modal" tabindex="-1" aria-hidden="true">' +
            '<div class="modal-dialog modal-fullscreen-sm-down">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h5 class="modal-title">' + myNewsBottomNav.labels.categories + '</h5>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
            '</div>' +
            '<div class="modal-body">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>'
        );
        
        // Add categories list
        var categoriesList = '<ul class="list-unstyled mobile-categories-list">';
        for (var i = 0; i < myNewsBottomNav.categories.length; i++) {
            var cat = myNewsBottomNav.categories[i];
            categoriesList += '<li><a href="' + cat.url + '">' + cat.name + ' <span class="badge bg-secondary rounded-pill">' + cat.count + '</span></a></li>';
        }
        categoriesList += '</ul>';
        
        $modal.find('.modal-body').html(categoriesList);
        
        // Add to the body
        $('body').append($modal);
    }
    
    /**
     * Handle click events on the bottom nav
     */
    function handleNavClicks() {
        // Dark mode toggle
        $('.bottom-nav-dark-mode').on('click', function(e) {
            e.preventDefault();
            
            if (isDarkMode()) {
                disableDarkMode();
                $(this).find('i').removeClass('bi-sun').addClass('bi-moon');
            } else {
                enableDarkMode();
                $(this).find('i').removeClass('bi-moon').addClass('bi-sun');
            }
        });
        
        // Menu button
        $('.bottom-nav-menu').on('click', function(e) {
            e.preventDefault();
            $('.navbar-toggler').trigger('click');
        });
    }
    
    /**
     * Handle scroll behavior - hide on scroll down, show on scroll up
     */
    function handleScrollBehavior() {
        var lastScroll = 0;
        var $bottomNav = $('.mobile-bottom-nav');
        
        $(window).scroll(function() {
            var currentScroll = $(this).scrollTop();
            
            if (currentScroll > lastScroll && currentScroll > 200) {
                // Scrolling down, hide nav
                $bottomNav.addClass('bottom-nav-hidden');
            } else {
                // Scrolling up, show nav
                $bottomNav.removeClass('bottom-nav-hidden');
            }
            
            lastScroll = currentScroll;
        });
    }
    
    /**
     * Check if current device is mobile
     */
    function isMobileDevice() {
        return window.innerWidth < 992;
    }
    
    /**
     * Check if dark mode is active
     */
    function isDarkMode() {
        return $('body').hasClass('dark-mode');
    }
    
    /**
     * Enable dark mode
     */
    function enableDarkMode() {
        $('body').addClass('dark-mode');
        localStorage.setItem('mynews_dark_mode', 'enabled');
    }
    
    /**
     * Disable dark mode
     */
    function disableDarkMode() {
        $('body').removeClass('dark-mode');
        localStorage.setItem('mynews_dark_mode', 'disabled');
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initMobileBottomNav();
    });
    
})(jQuery);
