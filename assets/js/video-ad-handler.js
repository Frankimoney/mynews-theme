/**
 * Video Ad Handler - Manages video ad behavior and playback
 *
 * Used to control autoplay behavior, visibility detection, and other
 * video ad-specific functionality for the MyNews theme.
 * 
 * @package My_News
 */

(function($) {
    'use strict';
    
    // Video Ad Handler
    var MyNewsVideoAds = {
        // Store references to video containers
        videoContainers: [],
        
        /**
         * Initialize video ad functionality
         */
        init: function() {
            // Find all video ad containers
            this.videoContainers = document.querySelectorAll('.video-ad-container');
            
            // If no video containers found, exit
            if (this.videoContainers.length === 0) {
                return;
            }
            
            // Setup video containers
            this.setupVideoContainers();
            
            // Setup intersection observer for visibility-based playback
            this.setupVisibilityObserver();
            
            // Handle window resize events
            window.addEventListener('resize', this.handleResize.bind(this));
        },
        
        /**
         * Setup video containers for proper display
         */
        setupVideoContainers: function() {
            // Process each video container
            this.videoContainers.forEach(function(container) {
                var videoWrapper = container.querySelector('.video-ad-wrapper');
                
                if (!videoWrapper) {
                    return;
                }
                
                // Find all iframes and videos in the container
                var videos = videoWrapper.querySelectorAll('iframe, video');
                
                videos.forEach(function(video) {
                    // Make sure videos are responsive
                    if (!video.hasAttribute('style') || !video.style.width) {
                        video.style.width = '100%';
                    }
                    
                    // Add proper classes for styling
                    video.classList.add('mynews-video-ad');
                    
                    // Handle autoplay settings for video elements
                    if (myNewsVideoAds.autoplay && video.tagName === 'VIDEO') {
                        video.setAttribute('autoplay', '');
                        video.setAttribute('muted', '');
                        video.setAttribute('playsinline', '');
                    }
                    
                    // For iframes, add proper parameters if possible
                    if (myNewsVideoAds.autoplay && video.tagName === 'IFRAME') {
                        var src = video.getAttribute('src');
                        
                        // Add autoplay parameters based on the iframe source
                        if (src) {
                            // For YouTube
                            if (src.indexOf('youtube.com') > -1) {
                                if (src.indexOf('?') > -1) {
                                    video.setAttribute('src', src + '&autoplay=1&mute=1');
                                } else {
                                    video.setAttribute('src', src + '?autoplay=1&mute=1');
                                }
                            }
                            // For Vimeo
                            else if (src.indexOf('vimeo.com') > -1) {
                                if (src.indexOf('?') > -1) {
                                    video.setAttribute('src', src + '&autoplay=1&muted=1');
                                } else {
                                    video.setAttribute('src', src + '?autoplay=1&muted=1');
                                }
                            }
                        }
                    }
                });
            });
        },
        
        /**
         * Setup intersection observer to handle video visibility
         */
        setupVisibilityObserver: function() {
            // Only setup if IntersectionObserver is supported
            if (!('IntersectionObserver' in window)) {
                return;
            }
            
            var options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.5 // 50% visibility
            };
            
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    var videos = entry.target.querySelectorAll('video');
                    
                    videos.forEach(function(video) {
                        // If video is visible and autoplay is enabled
                        if (entry.isIntersecting && myNewsVideoAds.autoplay) {
                            video.play();
                        } else {
                            video.pause();
                        }
                    });
                });
            }, options);
            
            // Observe all video containers
            this.videoContainers.forEach(function(container) {
                observer.observe(container);
            });
        },
        
        /**
         * Handle window resize events
         */
        handleResize: function() {
            // Adjust video sizes on resize if needed
            this.videoContainers.forEach(function(container) {
                var videoWrapper = container.querySelector('.video-ad-wrapper');
                
                if (!videoWrapper) {
                    return;
                }
                
                // Update video dimensions if needed
                var videos = videoWrapper.querySelectorAll('iframe, video');
                
                videos.forEach(function(video) {
                    // Maintain aspect ratio if needed
                    if (video.getAttribute('data-preserve-ratio') === 'true') {
                        var ratio = video.getAttribute('data-ratio') || 0.5625; // 16:9 default
                        var width = video.offsetWidth;
                        video.style.height = (width * ratio) + 'px';
                    }
                });
            });
        }
    };
    
    // Initialize when document is ready
    $(document).ready(function() {
        MyNewsVideoAds.init();
    });
    
})(jQuery);
