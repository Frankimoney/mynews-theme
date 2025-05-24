/**
 * Breaking News Ticker JavaScript
 */
(function($) {
  'use strict';

  // Ticker class
  class BreakingNewsTicker {
    constructor(element, options) {
      // Default options
      const defaults = {
        autoPlay: true,
        speed: 4000,
        pauseOnHover: true,
        direction: 'ltr',
        scrolling: true, // Enable scrolling effect
        scrollDuration: 20000, // 20 seconds to complete one full scroll
        displayMode: 'scroll' // 'scroll' or 'fade'
      };

      // Merge defaults with user options
      this.options = $.extend({}, defaults, options);
      
      // Setup variables
      this.$ticker = $(element);
      this.$items = this.$ticker.find('.mynews-ticker-item');
      this.$controls = this.$ticker.find('.mynews-ticker-controls');
      this.$next = this.$ticker.find('.mynews-ticker-next');
      this.$prev = this.$ticker.find('.mynews-ticker-prev');
      this.$pause = this.$ticker.find('.mynews-ticker-pause');
      
      this.itemCount = this.$items.length;
      this.currentIndex = 0;
      this.isPaused = false;
      this.pausedByHover = false;
      this.interval = null;
      this.isRtl = this.options.direction === 'rtl';
      this.textMeasureEl = null; // For measuring text width
      
      // Initialize the ticker
      this.init();
    }
    
    init() {
      // Show first item
      this.showItem(0);
      
      // Autoplay if enabled
      if (this.options.autoPlay) {
        this.play();
      }
      
      // Set up event handlers
      this.bindEvents();
    }
    
    bindEvents() {
      // Use arrow functions to preserve 'this'
      this.$next.on('click', (e) => {
        e.preventDefault();
        this.showNext();
        this.resetInterval();
      });
      
      this.$prev.on('click', (e) => {
        e.preventDefault();
        this.showPrev();
        this.resetInterval();
      });
      
      // Handle window resize to recalculate animation durations
      $(window).on('resize', () => {
        // Wait a moment for the resize to complete
        clearTimeout(this.resizeTimer);
        this.resizeTimer = setTimeout(() => {
          // Re-show the current item to recalculate dimensions
          if (this.options.displayMode === 'scroll') {
            // For scroll mode, restart the animation
            const $currentItem = this.$ticker.find('.mynews-ticker-item.active');
            if ($currentItem.length) {
              // Briefly remove and reapply the scrolling class to restart animation
              $currentItem.removeClass('scrolling');
              void $currentItem[0].offsetWidth; // Force reflow
              
              // Re-show the current item with fresh animation
              this.showItem(this.currentIndex);
            }
          } else {
            this.showItem(this.currentIndex);
          }
        }, 250);
      });
      
      this.$pause.on('click', (e) => {
        e.preventDefault();
        if (this.isPaused) {
          this.play();
          // Change icon to pause when playing
          this.$pause.html('<i class="fas fa-pause"></i>');
        } else {
          this.pause();
          // Change icon to play when paused
          this.$pause.html('<i class="fas fa-play"></i>');
        }
      });
      
      if (this.options.pauseOnHover) {
        // Apply pause on hover only to the ticker content area, not the controls
        this.$ticker.find('.mynews-ticker-content').on('mouseenter', () => {
          if (!this.isPaused) {
            this.pause(true); // true = paused by hover
            // Don't update the pause button UI when pausing by hover
          }
        }).on('mouseleave', () => {
          if (this.pausedByHover) {
            this.play();
            // Make sure the pause button shows the correct icon when resuming from hover
            if (!this.isPaused) {
              this.$pause.html('<i class="fas fa-pause"></i>');
            }
          }
        });
      }
    }
    
    resetInterval() {
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      
      if (!this.isPaused) {
        this.interval = setInterval(() => {
          this.showNext();
        }, this.options.speed);
      }
    }
    
    showItem(index) {
      // Always get the latest items in case DOM changes
      const $items = this.$ticker.find('.mynews-ticker-item');
      this.itemCount = $items.length; // update itemCount
      
      // Reset all items - remove classes and inline styles
      $items.removeClass('active scrolling fade-transition').css({
        'opacity': '',
        'animation-duration': '',
        'animation-play-state': '',
        'transform': '',
        'visibility': ''
      });
      
      const $currentItem = $items.eq(index);
      
      // Add active class
      $currentItem.addClass('active');
      
      // Make sure item is fully visible
      $currentItem.css({
        'opacity': 1,
        'visibility': 'visible'
      });
      
      // Update the current index
      this.currentIndex = index;
      
      // Handle different display modes
      if (this.options.displayMode === 'fade') {
        // Apply fade transition class
        $currentItem.addClass('fade-transition');
      } else if (this.options.scrolling && this.options.displayMode === 'scroll') {
        // Set initial position for animation
        $currentItem.css({
          'opacity': 1,
          'visibility': 'visible',
          'transform': 'translateX(100%)'  // Start off-screen to the right
        });
        
        // Force a browser reflow to ensure fresh animation start
        void $currentItem[0].offsetWidth;
        
        // Apply the scrolling class AFTER reflow to ensure animation works properly
        $currentItem.addClass('scrolling');
        
        // Force another reflow after adding class to trigger animation correctly
        void $currentItem[0].offsetWidth;
        
        // Set animation duration from options
        if (this.options.scrollDuration) {
          const duration = this.options.scrollDuration / 1000;
          
          // Calculate animation speed based on text length for more consistent reading experience
          const containerWidth = this.$ticker.find('.mynews-ticker-content').width();
          const textWidth = this.getTextWidth($currentItem.text());
          
          // Add padding for better measurement
          const actualTextWidth = textWidth + 100;
          
          // Only center very short text (less than 30% of container)
          if (actualTextWidth <= containerWidth * 0.3) {
            $currentItem.removeClass('scrolling');
            $currentItem.css({
              'transform': 'translateX(0)', // Center in container instead
              'position': 'static', // Change from absolute to static
              'display': 'block' // Use block instead of inline-block
            });
            return;
          }
          
          // Calculate total travel distance
          // For animation from 100% to -100%, we need 200% + text width
          const totalWidth = (containerWidth * 2) + textWidth;
          
          // Adjust speed based on content length
          const pixelsPerSecond = 100; // Higher value = faster scrolling
          const calculatedDuration = totalWidth / pixelsPerSecond;
          
          // Use the larger of calculated or minimum duration
          const adjustedDuration = Math.max(
            8, // Minimum 8 seconds animation duration
            calculatedDuration
          );
          
          $currentItem.css({
            'animation-duration': adjustedDuration + 's'
          });
          
          // Set play state based on pause status
          if (this.isPaused) {
            $currentItem.css('animation-play-state', 'paused');
          }
        }
      }
    }
    
    // Helper method to estimate text width accurately
    getTextWidth(text) {
      if (!this.textMeasureEl) {
        // Create a hidden element to measure text width that matches ticker styling
        const fontSize = getComputedStyle(document.documentElement).getPropertyValue('--mynews-ticker-font-size') || '0.95rem';
        
        this.textMeasureEl = $('<span>').css({
          'position': 'absolute',
          'visibility': 'hidden',
          'white-space': 'nowrap',
          'font-size': fontSize,
          'font-weight': this.$ticker.find('.mynews-ticker-item').css('font-weight') || 'normal',
          'font-family': this.$ticker.css('font-family') || 'inherit',
          'letter-spacing': this.$ticker.css('letter-spacing') || 'normal',
          'padding-right': '20px' // Match padding from ticker items
        }).appendTo('body');
      }
      
      // Add HTML content to accurately measure links and other formatting
      this.textMeasureEl.html(text);
      
      // Add buffer to account for any measurement inaccuracies
      return this.textMeasureEl.width() * 1.5;
    }
    
    showNext() {
      let nextIndex = this.currentIndex + 1;
      
      // Loop back to beginning if at end
      if (nextIndex >= this.itemCount) {
        nextIndex = 0;
      }
      
      // For fade effect, fade out current item before showing next
      if (this.options.displayMode === 'fade') {
        const $currentItem = this.$ticker.find('.mynews-ticker-item.active');
        $currentItem.css('opacity', 0);
        
        // Wait for fade out before showing next
        setTimeout(() => {
          this.showItem(nextIndex);
        }, 400);
      } else if (this.options.displayMode === 'scroll') {
        // For scroll mode, smoothly transition to next item
        const $currentItem = this.$ticker.find('.mynews-ticker-item.active');
        
        // Fade out current item
        $currentItem.css({
          'opacity': 0,
          'transition': 'opacity 0.2s ease-out'
        });
        
        // After fade out complete, show next item
        setTimeout(() => {
          this.showItem(nextIndex);
        }, 220);
      } else {
        this.showItem(nextIndex);
      }
    }
    
    showPrev() {
      let prevIndex = this.currentIndex - 1;
      
      // Loop to end if at beginning
      if (prevIndex < 0) {
        prevIndex = this.itemCount - 1;
      }
      
      // For fade effect, fade out current item before showing prev
      if (this.options.displayMode === 'fade') {
        const $currentItem = this.$ticker.find('.mynews-ticker-item.active');
        $currentItem.css('opacity', 0);
        
        // Wait for fade out before showing prev
        setTimeout(() => {
          this.showItem(prevIndex);
        }, 400);
      } else if (this.options.displayMode === 'scroll') {
        // For scroll mode, smoothly transition to previous item
        const $currentItem = this.$ticker.find('.mynews-ticker-item.active');
        
        // Fade out current item
        $currentItem.css({
          'opacity': 0,
          'transition': 'opacity 0.3s ease-out'
        });
        
        // After fade out complete, show previous item
        setTimeout(() => {
          this.showItem(prevIndex);
        }, 300);
      } else {
        this.showItem(prevIndex);
      }
    }
    
    play() {
      this.isPaused = false;
      this.pausedByHover = false;
      this.resetInterval();
      
      // Resume scrolling animation if in scroll mode
      if (this.options.displayMode === 'scroll') {
        const $activeItem = this.$ticker.find('.mynews-ticker-item.active.scrolling');
        if ($activeItem.length) {
          $activeItem.css('animation-play-state', 'running');
        }
      }
    }
    
    pause(pausedByHover = false) {
      this.isPaused = true;
      this.pausedByHover = pausedByHover;
      
      // Pause scrolling animation if in scroll mode
      if (this.options.displayMode === 'scroll') {
        const $activeItem = this.$ticker.find('.mynews-ticker-item.active.scrolling');
        if ($activeItem.length) {
          $activeItem.css('animation-play-state', 'paused');
        }
      }
      
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
    }
  }
  
  // jQuery plugin
  $.fn.myNewsBreakingTicker = function(options) {
    return this.each(function() {
      if (!$.data(this, "myNewsBreakingTicker")) {
        $.data(this, "myNewsBreakingTicker", new BreakingNewsTicker(this, options));
      }
    });
  };
  
  // Initialize when DOM is ready
  $(document).ready(function() {
    // Get custom speed from settings or use default
    var speed = (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.speed) 
                ? myNewsTickerSettings.speed 
                : 5000;
                
    // Get scrolling setting
    var isScrollingEnabled = (typeof myNewsTickerSettings !== 'undefined' && 'scrolling' in myNewsTickerSettings)
                            ? myNewsTickerSettings.scrolling
                            : true;
                       
    const $ticker = $('.mynews-breaking-news');
    
    // Get display mode setting
    var displayMode = (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.displayMode) 
                      ? myNewsTickerSettings.displayMode 
                      : 'scroll';
                      
    // Apply appropriate classes based on display mode
    if (displayMode === 'scroll' && isScrollingEnabled) {
      $ticker.addClass('scrolling-enabled scrolling-ticker');
      // Force hardware acceleration for smoother scrolling
      $('.mynews-ticker-content').css({
        'transform': 'translateZ(0)',
        '-webkit-transform': 'translateZ(0)',
        '-webkit-backface-visibility': 'hidden'
      });
    } else if (displayMode === 'fade') {
      $ticker.addClass('fade-ticker').removeClass('scrolling-enabled scrolling-ticker');
    } else {
      $ticker.removeClass('scrolling-ticker scrolling-enabled fade-ticker');
    }
    
    if ($ticker.length > 0) {
      // Always ensure only the first item is active at start
      $ticker.find('.mynews-ticker-item').removeClass('active').first().addClass('active');
      
      // Get custom settings from WordPress or use defaults
      var scrollingEnabled = (typeof myNewsTickerSettings !== 'undefined' && 'scrolling' in myNewsTickerSettings)
                            ? myNewsTickerSettings.scrolling
                            : true;
                              
      var scrollDuration = (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.scrollDuration)
                          ? myNewsTickerSettings.scrollDuration
                          : 20000;
      
      // Get custom font size and apply it
      if (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.fontSize) {
        document.documentElement.style.setProperty('--mynews-ticker-font-size', myNewsTickerSettings.fontSize);
      }
      
      // Initialize ticker with proper duration
      $ticker.myNewsBreakingTicker({
        autoPlay: true,
        speed: speed,
        pauseOnHover: true,
        displayMode: displayMode,
        scrolling: scrollingEnabled,
        scrollDuration: scrollDuration * 2 // Increase duration but not too much
      });
      
      // Ensure pause/play button has correct icon on initialization
      $('.mynews-ticker-pause').html('<i class="fas fa-pause"></i>');
      
      // Force recalculation of ticker dimensions after short delay to ensure proper display
      setTimeout(function() {
        $(window).trigger('resize');
      }, 500);
      
      // Additional check a bit later to ensure correct display on slower connections
      setTimeout(function() {
        $(window).trigger('resize');
      }, 1500);
      
      // Final check for very slow loading pages
      setTimeout(function() {
        $(window).trigger('resize');
      }, 3000);
    }
  });

})(jQuery);
