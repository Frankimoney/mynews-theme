/**
 * Reading Progress Bar Functionality
 * 
 * Creates a progress bar that shows how far a user has scrolled through an article
 *
 * @package My_News
 */

(function($) {
    'use strict';
    
    // Run after the DOM is fully loaded
    $(document).ready(function() {        
        // Only initialize on single post pages
        if ($('body.single-post').length) {
            // Get the progress bar element that's already in the DOM
            const progressBar = $('.reading-progress-bar');
            
            if (progressBar.length) {
                // Get the post content area for measuring scroll progress
                const postContent = $('.entry-content');
                if (postContent.length) {
                    // Listen for scroll events to update the progress bar
                    $(window).on('scroll', function() {
                        updateProgressBar(postContent, progressBar);
                    });
                    
                    // Initial update
                    updateProgressBar(postContent, progressBar);
                }
            }
        }
    });
      /**
     * Update the progress bar width based on scroll position
     *
     * @param {jQuery} postContent - The post content element
     * @param {jQuery} progressBar - The progress bar element
     */
    function updateProgressBar(postContent, progressBar) {
        const windowHeight = $(window).height();
        const documentHeight = $(document).height();
        const scrollTop = $(window).scrollTop();
        
        // Calculate the start and end positions of the content
        const contentTop = postContent.offset().top;
        const contentHeight = postContent.outerHeight();
        const contentBottom = contentTop + contentHeight;
        
        // Calculate how far we've scrolled through the content
        let readHeight = scrollTop - contentTop;
        if (readHeight < 0) readHeight = 0;
        
        // Calculate readable content (excluding the part that's below the viewport)
        const readableHeight = documentHeight - windowHeight;
        
        // Calculate the percentage scrolled (0-100)
        let percentage = (scrollTop / readableHeight) * 100;
        
        // Ensure the percentage is between 0-100
        percentage = Math.min(100, Math.max(0, percentage));
        
        // Update the progress bar width
        progressBar.css('width', percentage + '%');
        
        // Add a class when the user reaches the end of the content
        if (percentage >= 98) {
            progressBar.addClass('complete');
        } else {
            progressBar.removeClass('complete');
        }
    }
    
})(jQuery);
