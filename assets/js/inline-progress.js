/**
 * Inline Reading Progress Bar Script
 * This script creates and updates a reading progress bar at the top of single posts.
 * It runs immediately without waiting for document ready to ensure visibility.
 */

(function() {
    // Immediately create the reading progress bar at the top of the page
    function createProgressBar() {
        // Don't proceed if we're not on a single post page
        if (!document.body.classList.contains('single') && 
            !document.body.classList.contains('single-post')) {
            return;
        }
        
        // Check if the progress bar already exists
        var existingBar = document.querySelector('.reading-progress-bar');
        if (existingBar) {
            // Make sure it's visible
            existingBar.style.display = 'block';
            existingBar.style.visibility = 'visible';
            existingBar.style.opacity = '1';
            return existingBar;
        }
        
        // Create the progress bar
        var progressBar = document.createElement('div');
        progressBar.className = 'reading-progress-bar';
        
        // Set essential styles inline to ensure visibility
        var styles = {
            position: 'fixed',
            top: (document.body.classList.contains('admin-bar') ? 
                  (window.innerWidth < 783 ? '46px' : '32px') : '0'),
            left: '0',
            height: '4px',
            width: '0%',
            backgroundColor: document.documentElement.getAttribute('data-theme') === 'dark' ? 
                             '#4caf50' : '#0073aa',
            zIndex: '999999',
            transition: 'width 0.2s ease-in-out',
            boxShadow: '0 1px 3px rgba(0, 0, 0, 0.2)',
            display: 'block',
            visibility: 'visible',
            opacity: '1'
        };
        
        // Apply styles
        Object.keys(styles).forEach(function(property) {
            progressBar.style[property] = styles[property];
        });
        
        // Insert at the beginning of the body
        document.body.insertBefore(progressBar, document.body.firstChild);
        
        return progressBar;
    }
    
    // Update the progress bar width based on scroll position
    function updateProgressBar() {
        var progressBar = document.querySelector('.reading-progress-bar');
        if (!progressBar) {
            progressBar = createProgressBar();
            if (!progressBar) return; // Exit if we couldn't create the bar
        }
        
        var winHeight = window.innerHeight;
        var docHeight = Math.max(
            document.body.scrollHeight, 
            document.documentElement.scrollHeight,
            document.body.offsetHeight, 
            document.documentElement.offsetHeight,
            document.body.clientHeight, 
            document.documentElement.clientHeight
        );
        var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Calculate readable content (excluding the part that's below the viewport)
        var readableHeight = docHeight - winHeight;
        
        // Calculate the percentage scrolled (0-100)
        var percentage = (scrollTop / readableHeight) * 100;
        percentage = Math.min(100, Math.max(0, percentage)); // Ensure between 0-100
        
        // Update the progress bar width
        progressBar.style.width = percentage + '%';
        
        // Add a class when the user reaches the end of the content
        if (percentage >= 98) {
            progressBar.classList.add('complete');
        } else {
            progressBar.classList.remove('complete');
        }
    }
    
    // Set up scroll event listener
    function setupScrollListener() {
        window.addEventListener('scroll', updateProgressBar);
        // Initial update
        updateProgressBar();
    }
    
    // Run immediately and also after DOM is loaded
    createProgressBar();
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            createProgressBar();
            setupScrollListener();
        });
    } else {
        setupScrollListener();
    }
    
    // Also run on window load to ensure all elements are fully loaded
    window.addEventListener('load', function() {
        createProgressBar();
        updateProgressBar();
    });
})();
