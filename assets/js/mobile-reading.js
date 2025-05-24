/**
 * Mobile reading experience enhancements
 * 
 * Improves reading experience for mobile users with:
 * 1. Reading progress bar
 * 2. Font size adjustment
 * 3. Estimated reading time
 * 4. Social sharing
 */

(function($) {
    'use strict';
    
    /**
     * Initialize reading experience enhancements
     */
    function initReadingExperience() {
        // Only apply on mobile devices
        if (!isMobileDevice()) {
            return;
        }
        
        // Only on single posts
        if (!$('body').hasClass('single-post')) {
            return;
        }
        
        // Add reading progress bar
        addReadingProgressBar();
        
        // Add font size controls
        addFontSizeControls();
        
        // Add estimated reading time
        addEstimatedReadingTime();
        
        // Add social sharing buttons
        addSocialSharing();
    }
    
    /**
     * Add reading progress bar to the top of the page
     */
    function addReadingProgressBar() {
        // Add progress bar element
        $('body').append('<div class="reading-progress-bar"></div>');
        
        // Update progress on scroll
        $(window).scroll(function() {
            var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            var scrolled = (winScroll / height) * 100;
            
            $('.reading-progress-bar').css('width', scrolled + '%');
        });
    }
    
    /**
     * Add font size adjustment controls
     */
    function addFontSizeControls() {
        // Create controls
        var controls = $(
            '<div class="font-size-controls">' +
            '<button type="button" class="decrease-font" aria-label="Decrease font size">A-</button>' +
            '<span class="font-size-value">100%</span>' +
            '<button type="button" class="increase-font" aria-label="Increase font size">A+</button>' +
            '</div>'
        );
        
        // Add to the post
        $('.entry-content').before(controls);
        
        // Handle font size adjustments
        $('.decrease-font').on('click', function() {
            changeFontSize('decrease');
        });
        
        $('.increase-font').on('click', function() {
            changeFontSize('increase');
        });
        
        // Load saved font size preference
        loadFontSizePreference();
    }
    
    /**
     * Change the font size
     */
    function changeFontSize(direction) {
        var $body = $('body');
        
        if (direction === 'increase') {
            if ($body.hasClass('font-size-small')) {
                $body.removeClass('font-size-small');
                $('.font-size-value').text('100%');
                saveFontSizePreference('normal');
            } else if (!$body.hasClass('font-size-large') && !$body.hasClass('font-size-xlarge')) {
                $body.addClass('font-size-large');
                $('.font-size-value').text('115%');
                saveFontSizePreference('large');
            } else if ($body.hasClass('font-size-large')) {
                $body.removeClass('font-size-large').addClass('font-size-xlarge');
                $('.font-size-value').text('125%');
                saveFontSizePreference('xlarge');
            }
        } else {
            if ($body.hasClass('font-size-xlarge')) {
                $body.removeClass('font-size-xlarge').addClass('font-size-large');
                $('.font-size-value').text('115%');
                saveFontSizePreference('large');
            } else if ($body.hasClass('font-size-large')) {
                $body.removeClass('font-size-large');
                $('.font-size-value').text('100%');
                saveFontSizePreference('normal');
            } else if (!$body.hasClass('font-size-small')) {
                $body.addClass('font-size-small');
                $('.font-size-value').text('90%');
                saveFontSizePreference('small');
            }
        }
    }
    
    /**
     * Save font size preference to local storage
     */
    function saveFontSizePreference(size) {
        localStorage.setItem('mynews_font_size', size);
    }
    
    /**
     * Load font size preference from local storage
     */
    function loadFontSizePreference() {
        var size = localStorage.getItem('mynews_font_size');
        
        if (size === 'small') {
            $('body').addClass('font-size-small');
            $('.font-size-value').text('90%');
        } else if (size === 'large') {
            $('body').addClass('font-size-large');
            $('.font-size-value').text('115%');
        } else if (size === 'xlarge') {
            $('body').addClass('font-size-xlarge');
            $('.font-size-value').text('125%');
        }
    }
    
    /**
     * Add estimated reading time to the post meta
     */
    function addEstimatedReadingTime() {
        var content = $('.entry-content').text();
        var wordCount = content.trim().split(/\s+/).length;
        var readingTime = Math.ceil(wordCount / 200); // Assuming 200 words per minute
        
        // Add reading time to post meta
        $('.entry-meta').append('<span class="estimated-reading-time"><i class="bi bi-clock"></i> ' + readingTime + ' min read</span>');
    }
    
    /**
     * Add social sharing buttons designed for mobile
     */
    function addSocialSharing() {
        var postUrl = encodeURIComponent(window.location.href);
        var postTitle = encodeURIComponent(document.title);
        
        var shareButtons = $(
            '<div class="mobile-social-share">' +
            '<a href="https://www.facebook.com/sharer/sharer.php?u=' + postUrl + '" target="_blank" rel="noopener noreferrer" class="share-facebook"><i class="bi bi-facebook"></i></a>' +
            '<a href="https://twitter.com/intent/tweet?url=' + postUrl + '&text=' + postTitle + '" target="_blank" rel="noopener noreferrer" class="share-twitter"><i class="bi bi-twitter"></i></a>' +
            '<a href="whatsapp://send?text=' + postTitle + ' ' + postUrl + '" class="share-whatsapp"><i class="bi bi-whatsapp"></i></a>' +
            '<a href="mailto:?subject=' + postTitle + '&body=' + postUrl + '" class="share-email"><i class="bi bi-envelope"></i></a>' +
            '</div>'
        );
        
        // Add after post content
        $('.entry-content').after(shareButtons);
    }
    
    /**
     * Check if current device is mobile
     */
    function isMobileDevice() {
        return window.innerWidth < 992;
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initReadingExperience();
    });
    
})(jQuery);
