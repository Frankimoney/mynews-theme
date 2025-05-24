/**
 * Lazy loading for images on mobile devices
 * 
 * This script implements lazy loading for images to improve page load
 * performance on mobile devices.
 */

(function($) {
    'use strict';
    
    // Elements to lazy load
    var lazyElements = [];
    
    // Options
    var options = {
        rootMargin: '50px 0px',
        threshold: 0.01
    };
    
    /**
     * Initialize lazy loading
     */
    function initLazyLoading() {
        // Only run if the feature is enabled
        if (!myNewsLazyLoad.enabled) {
            return;
        }
        
        // Find all images to lazy load
        findLazyElements();
        
        // Set up the intersection observer if supported
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(onIntersection, options);
            
            // Observe each lazy element
            lazyElements.forEach(function(element) {
                observer.observe(element);
            });
        } else {
            // Fallback for browsers that don't support IntersectionObserver
            loadAllElements();
        }
        
        // Also handle dynamically added content
        observeDOMChanges();
    }
    
    /**
     * Find all elements to lazy load
     */
    function findLazyElements() {
        // Reset the array
        lazyElements = [];
        
        // Find all images with data-src attribute
        document.querySelectorAll('img[data-src]').forEach(function(img) {
            lazyElements.push(img);
        });
        
        // Find all elements with data-bg attribute (for background images)
        document.querySelectorAll('[data-bg]').forEach(function(el) {
            lazyElements.push(el);
        });
        
        // Find all videos with data-src attribute
        document.querySelectorAll('video[data-src]').forEach(function(video) {
            lazyElements.push(video);
        });
    }
    
    /**
     * Handle intersection observer callback
     */
    function onIntersection(entries, observer) {
        entries.forEach(function(entry) {
            // If the element is visible
            if (entry.isIntersecting) {
                // Stop watching
                observer.unobserve(entry.target);
                
                // Load the element
                loadElement(entry.target);
            }
        });
    }
    
    /**
     * Load an individual element
     */
    function loadElement(element) {
        // Handle different element types
        if (element.tagName.toLowerCase() === 'img') {
            // Load image src
            if (element.dataset.src) {
                element.src = element.dataset.src;
                delete element.dataset.src;
            }
            
            // Load srcset if present
            if (element.dataset.srcset) {
                element.srcset = element.dataset.srcset;
                delete element.dataset.srcset;
            }
        } else if (element.dataset.bg) {
            // Load background image
            element.style.backgroundImage = 'url(' + element.dataset.bg + ')';
            delete element.dataset.bg;
        } else if (element.tagName.toLowerCase() === 'video') {
            // Load video src
            if (element.dataset.src) {
                element.src = element.dataset.src;
                delete element.dataset.src;
            }
            
            // Load poster if present
            if (element.dataset.poster) {
                element.poster = element.dataset.poster;
                delete element.dataset.poster;
            }
        }
        
        // Add loaded class
        element.classList.add('lazy-loaded');
        
        // Remove loading class
        element.classList.remove('lazy-loading');
    }
    
    /**
     * Fallback to load all elements
     */
    function loadAllElements() {
        lazyElements.forEach(function(element) {
            loadElement(element);
        });
    }
    
    /**
     * Watch for DOM changes to lazy load dynamically added elements
     */
    function observeDOMChanges() {
        // Use MutationObserver if available
        if ('MutationObserver' in window) {
            var observer = new MutationObserver(function(mutations) {
                var hasNewImages = false;
                
                mutations.forEach(function(mutation) {
                    // Look for new nodes
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach(function(node) {
                            // Check if the added node is an element
                            if (node.nodeType === 1) {
                                // Look for lazy loadable elements
                                if (node.tagName.toLowerCase() === 'img' && node.dataset.src) {
                                    hasNewImages = true;
                                }
                                
                                // Also check children
                                var images = node.querySelectorAll('img[data-src]');
                                if (images.length > 0) {
                                    hasNewImages = true;
                                }
                            }
                        });
                    }
                });
                
                // If new lazy loadable elements were added, reinitialize
                if (hasNewImages) {
                    initLazyLoading();
                }
            });
            
            // Start observing
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }
    
    /**
     * Initialize on document ready
     */
    $(document).ready(function() {
        initLazyLoading();
    });
    
})(jQuery);
