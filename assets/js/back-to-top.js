/**
 * Back to Top Button Functionality
 */
(function($) {
    'use strict';
    
    // When the document is ready
    $(document).ready(function() {
        
        // Check if back to top is enabled in customizer
        if (typeof mynewsBackToTop !== 'undefined' && mynewsBackToTop.enabled) {
            // Create the back to top button element and append to body
            $('body').append('<a href="#" class="back-to-top" aria-label="Back to top"><i class="fas fa-arrow-up"></i></a>');
        
        var $backToTop = $('.back-to-top');
        
        // Show/hide the button based on scroll position
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $backToTop.fadeIn();
            } else {
                $backToTop.fadeOut();
            }
        });
          // Smooth scroll to top when clicked
            $backToTop.on('click', function(e) {
                e.preventDefault();
                $('html, body').animate({scrollTop: 0}, 800);
            });
        }
    });
    
})(jQuery);
