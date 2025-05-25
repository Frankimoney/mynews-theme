/**
 * Reading Progress Bar Conflict Resolver
 * This script serves as an emergency fallback to ensure the reading progress bar
 * is always visible and working, even if other scripts or styles interfere with it.
 */

(function($) {
    'use strict';
    
    // Debugging - set to true to enable console logs
    var debug = false;
    
    // Debug log function
    function log() {
        if (debug) {
            console.log.apply(console, ['[ProgressBar]'].concat(
                Array.prototype.slice.call(arguments)
            ));
        }
    }
    
    // Check as early as possible
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    
    // Initialize our resolver
    function init() {
        log('Initializing reading progress resolver');
        
        // Only run on single posts
        if (!isSinglePost()) {
            log('Not a single post page, exiting');
            return;
        }
        
        // Check for existing progress bar and ensure it's working
        ensureProgressBar();
        
        // Set up interval to periodically check and fix the progress bar
        setInterval(ensureProgressBar, 2000);
        
        // Additional checks at specific times
        setTimeout(ensureProgressBar, 500);
        setTimeout(ensureProgressBar, 1500);
        
        // Also check when scrolling (throttled)
        var scrollThrottle;
        window.addEventListener('scroll', function() {
            if (!scrollThrottle) {
                scrollThrottle = setTimeout(function() {
                    ensureProgressBar();
                    scrollThrottle = null;
                }, 1000);
            }
        });
        
        // Check when the page is fully loaded
        window.addEventListener('load', function() {
            ensureProgressBar();
            setTimeout(ensureProgressBar, 1000);
        });
    }
    
    // Check if we're on a single post page
    function isSinglePost() {
        return document.body.classList.contains('single') || 
               document.body.classList.contains('single-post') || 
               document.body.classList.contains('single-article');
    }
    
    // Ensure a working progress bar exists
    function ensureProgressBar() {
        // Find any existing progress bar
        var progressBar = findProgressBar();
        
        if (!progressBar) {
            log('No progress bar found, creating new one');
            createProgressBar();
            return;
        }
        
        log('Found existing progress bar, checking if it works');
        
        // Test if the progress bar is visible and working
        var styles = window.getComputedStyle(progressBar);
        
        var isVisible = styles.display !== 'none' && 
                        styles.visibility !== 'hidden' && 
                        styles.opacity !== '0';
                        
        var isPositionedCorrectly = styles.position === 'fixed' && 
                                    parseInt(styles.zIndex, 10) >= 9999;
        
        if (!isVisible || !isPositionedCorrectly) {
            log('Progress bar not visible or positioned correctly, fixing');
            fixProgressBar(progressBar);
        } else {
            log('Progress bar appears to be working correctly');
        }
        
        // Make sure the width is updating based on scroll position
        updateProgressBarWidth(progressBar);
    }
    
    // Find any existing progress bar
    function findProgressBar() {
        return document.getElementById('mynews-reading-progress') || 
               document.querySelector('.reading-progress-bar');
    }
    
    // Create a new progress bar
    function createProgressBar() {
        var bar = document.createElement('div');
        bar.id = 'mynews-reading-progress-resolver';
        bar.className = 'reading-progress-bar';
        
        // Apply styles
        applyProgressBarStyles(bar);
        
        // Insert at the beginning of the body
        document.body.insertBefore(bar, document.body.firstChild);
        
        // Initial update
        updateProgressBarWidth(bar);
        
        // Set up scroll event
        window.addEventListener('scroll', function() {
            updateProgressBarWidth(bar);
        });
        
        log('New progress bar created');
        return bar;
    }
    
    // Fix an existing progress bar
    function fixProgressBar(progressBar) {
        log('Fixing progress bar styles');
        applyProgressBarStyles(progressBar);
    }
    
    // Apply all necessary styles to a progress bar
    function applyProgressBarStyles(bar) {
        if (!bar) return;
        
        // Determine admin bar height
        var adminBarOffset = document.body.classList.contains('admin-bar') ? 
            (window.innerWidth < 783 ? '46px' : '32px') : '0';
        
        // Determine color based on theme
        var barColor = document.documentElement.getAttribute('data-theme') === 'dark' ? 
            '#4caf50' : '#0073aa';
        
        // Get current width if available
        var currentWidth = '0%';
        var currentStyle = bar.getAttribute('style');
        if (currentStyle) {
            var widthMatch = currentStyle.match(/width:\s*(\d+\.?\d*)%/);
            if (widthMatch && widthMatch[1]) {
                currentWidth = widthMatch[1] + '%';
            }
        }
        
        // Safari-specific adjustments for better rendering
        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        var safariStyles = isSafari ? 
            '-webkit-transform:translate3d(0,0,0) !important;-webkit-backface-visibility:hidden !important;' : '';
        
        // Apply all critical styles with !important
        bar.setAttribute('style', 
            'position:fixed !important;' +
            'top:' + adminBarOffset + ' !important;' +
            'left:0 !important;' +
            'height:5px !important;' +
            'width:' + currentWidth + ' !important;' +
            'background-color:' + barColor + ' !important;' +
            'z-index:2147483647 !important;' +
            'transition:width 0.1s ease-out !important;' +
            'display:block !important;' +
            'visibility:visible !important;' +
            'opacity:1 !important;' +
            'pointer-events:none !important;' +
            'transform:translateZ(0) !important;' +
            safariStyles +
            '-webkit-transform:translateZ(0) !important;' +
            'will-change:width !important;' +
            'box-shadow:0 1px 3px rgba(0,0,0,0.2) !important;');
    }
    
    // Update the progress bar width based on scroll position
    function updateProgressBarWidth(progressBar) {
        if (!progressBar) return;
        
        try {
            // Calculate scroll percentage
            var winScroll = window.pageYOffset || document.documentElement.scrollTop;
            var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            
            // Handle division by zero or negative heights
            if (height <= 0) height = 1;
            
            var scrolled = (winScroll / height) * 100;
            
            // Validate percentage
            if (isNaN(scrolled)) scrolled = 0;
            if (scrolled < 0) scrolled = 0;
            if (scrolled > 100) scrolled = 100;
            
            // Update width with !important to ensure it's applied
            var currentStyle = progressBar.getAttribute('style') || '';
            
            if (currentStyle.includes('width:')) {
                // Replace existing width value
                currentStyle = currentStyle.replace(/width:[^;]+!important/, 'width:' + scrolled + '%!important');
                progressBar.setAttribute('style', currentStyle);
            } else {
                // No width found, reapply all styles
                applyProgressBarStyles(progressBar);
                
                // Try replacing again
                currentStyle = progressBar.getAttribute('style') || '';
                if (currentStyle.includes('width:')) {
                    currentStyle = currentStyle.replace(/width:[^;]+!important/, 'width:' + scrolled + '%!important');
                    progressBar.setAttribute('style', currentStyle);
                }
            }
            
            // Add animation class for completion
            if (scrolled > 98) {
                if (!progressBar.classList.contains('complete')) {
                    progressBar.classList.add('complete');
                }
            } else {
                if (progressBar.classList.contains('complete')) {
                    progressBar.classList.remove('complete');
                }
            }
        } catch (e) {
            // Silently catch errors
            console.error('Error updating reading progress bar:', e);
        }
    }
    
    // Add a special handler for the window load event
    $(window).on('load', function() {
        // Do a final check after all resources are loaded
        setTimeout(function() {
            // Make one more check
            var progressBar = findProgressBar();
            
            if (!progressBar) {
                log('No progress bar found after full page load, creating one');
                createProgressBar();
            } else {
                log('Progress bar found after full page load, ensuring it works');
                fixProgressBar(progressBar);
                updateProgressBarWidth(progressBar);
            }
        }, 1000);
    });
    
    // Monitor DOM changes to detect AJAX navigation
    if (window.MutationObserver) {
        var bodyObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    // Check if we're now on a single post page
                    if (isSinglePost()) {
                        log('Detected navigation to a single post, checking progress bar');
                        ensureProgressBar();
                    }
                }
            });
        });
        
        // Start observing
        bodyObserver.observe(document.body, { attributes: true });
    }

})(jQuery);
