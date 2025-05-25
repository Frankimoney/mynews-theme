/**
 * Breaking News Ticker JavaScript
 */
(function($) {
  'use strict';

  // Ticker class
  class BreakingNewsTicker {    constructor(element, options) {
      // Default options
      const defaults = {
        autoPlay: true,
        speed: 4000,
        pauseOnHover: true,
        direction: 'ltr',
        scrolling: true, // Enable scrolling effect
        scrollDuration: 20000, // 20 seconds to complete one full scroll
        displayMode: 'scroll' // Default to scroll mode - ensures consistent mode
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
      
      this.itemCount = this.$items.length;      this.currentIndex = 0;
      this.isPaused = false;
      this.interval = null;
      this.isRtl = this.options.direction === 'rtl';
      this.textMeasureEl = null; // For measuring text width
      
      // Initialize the ticker
      this.init = this.init.bind(this);
      this.showItem = this.showItem.bind(this);
      this.showNext = this.showNext.bind(this);
      this.showPrev = this.showPrev.bind(this);      this.play = this.play.bind(this);
      this.pause = this.pause.bind(this);
      this.bindEvents = this.bindEvents.bind(this);
      this.resetInterval = this.resetInterval.bind(this);
      this.getTextWidth = this.getTextWidth.bind(this);
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
      this.$pause.on('click', (e) => {
        e.preventDefault();
        if (this.isPaused) {
          this.play();
          this.$pause.html('<i class="fas fa-pause"></i>');
        } else {
          this.pause();
          this.$pause.html('<i class="fas fa-play"></i>');
        }
      });
      if (this.options.pauseOnHover) {
        this.$ticker.on('mouseenter', () => {
          if (!this.isPaused) {
            this.pause(true); // true = paused by hover
          }
        }).on('mouseleave', () => {
          if (this.pausedByHover) {
            this.play();
          }
        });
      }
    }
      resetInterval() {
      // Clear any existing interval
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      
      // For scrolling mode, we rely on animation end event to advance
      // But for non-scrolling items or fade mode, use interval fallback
      if (!this.isPaused) {
        // Get current active item
        const $activeItem = this.$ticker.find('.mynews-ticker-item.active');
        
        // Only create interval if:
        // 1. Item is not scrolling (meaning it fits in container), OR
        // 2. Display mode is not "scroll"
        if (($activeItem.length && !$activeItem.hasClass('scrolling')) || 
            this.options.displayMode !== 'scroll') {
          
          // Create new interval for auto-advancing
          this.interval = setInterval(() => {
            this.showNext();
          }, this.options.speed);
          
          console.log('Ticker interval set for', this.options.speed, 'ms');
        }
      }
    }showItem(index) {
      // Validate index is within bounds
      if (index < 0 || (this.itemCount > 0 && index >= this.itemCount)) {
        console.warn('Invalid item index:', index, 'using index 0 instead');
        index = 0;
      }
      
      // Always get the latest items in case DOM changes
      const $items = this.$ticker.find('.mynews-ticker-item');
      this.itemCount = $items.length; // update itemCount!
      
      // Set current index immediately - critical!
      this.currentIndex = index;
      
      $items.removeClass('active scrolling').css({
        'opacity': '',
        'animation-duration': '',
        'animation-play-state': ''
      });
      
      const $currentItem = $items.eq(index);
        // Add active class
      $currentItem.addClass('active');
      
      // Check display mode and scrolling preference
      if (this.options.scrolling && this.options.displayMode === 'scroll') {
        // Force a reflow before adding the scrolling class to ensure animation starts properly
        void $currentItem[0].offsetWidth;
        
        // Always apply scrolling class in scroll mode regardless of text length
        $currentItem.addClass('scrolling');
        
        // Set animation duration from options
        if (this.options.scrollDuration) {
          const duration = this.options.scrollDuration / 1000;
          
          // Calculate animation speed based on text length for more consistent reading experience
          const textLength = $currentItem.text().length;
          const containerWidth = this.$ticker.find('.mynews-ticker-content').width();
          const textWidth = this.getTextWidth($currentItem.text());
            // In scroll mode, always apply scrolling animation regardless of text width
          // This ensures consistent behavior across all items
          // if (textWidth <= containerWidth * 0.8) {
          //  $currentItem.removeClass('scrolling');
          //  // Use interval for non-scrolling items
          //  this.resetInterval();
          //  return;
          // }// Calculate total travel distance for full scrolling across the screen
          // Use viewport width as basis to ensure consistent speed regardless of container size
          const viewportWidth = Math.max(window.innerWidth, document.documentElement.clientWidth);
          const totalWidth = viewportWidth + textWidth + 100; // Add extra padding
          
          // Use a consistent pixels-per-second speed for better readability
          const pixelsPerSecond = 120; // Higher value = faster scrolling
          const calculatedDuration = totalWidth / pixelsPerSecond;
          
          // Use the larger of calculated or minimum duration, and cap at maximum
          const adjustedDuration = Math.max(
            Math.min(calculatedDuration, 30), // Cap at 30 seconds maximum
            8 // Minimum 8 seconds animation duration
          );
            console.log('Item animation calculation:', {
            text: $currentItem.text().substring(0, 20) + '...',
            textWidth: textWidth + 'px',
            windowWidth: window.innerWidth + 'px',
            displayMode: this.options.displayMode,
            scrolling: this.options.scrolling,
            isScrollingClass: $currentItem.hasClass('scrolling'),
            calculatedDuration: calculatedDuration.toFixed(1) + 's',
            finalDuration: adjustedDuration + 's'
          });
          
          $currentItem.css({
            'animation-duration': adjustedDuration + 's'
          });
        }        // Listen for animation end to advance to next item        // Remove any existing animation listeners to prevent multiple triggers
        $currentItem.off('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd');
        
        // Add new listener with cross-browser support for animation end events
        $currentItem.one('animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd', () => {
          // Only advance to next if not paused, and if this is still the active item
          // This check ensures we don't get duplicate animations
          if (!this.isPaused && $currentItem.hasClass('active')) {
            // Debug to check if the animation end event is firing
            console.log('Animation ended for item at index:', this.currentIndex);
            // Use setTimeout to ensure the next item shows after a small delay
            setTimeout(() => {
              this.showNext();
            }, 50);
          }
        });
        
        // Stop interval while scrolling - we'll use the animation end event instead
        if (this.interval) {
          clearInterval(this.interval);
          this.interval = null;
        }
      } else {
        // Use interval for fade mode
        this.resetInterval();
      }
    }
      // Helper method to estimate text width
    getTextWidth(text) {
      if (!this.textMeasureEl) {
        // Create a hidden element to measure text width
        this.textMeasureEl = $('<span>').css({
          'position': 'absolute',
          'visibility': 'hidden',
          'white-space': 'nowrap',
          'font-size': '0.95rem', // Match the ticker font size
          'font-family': this.$ticker.css('font-family')
        }).appendTo('body');
      }
      
      this.textMeasureEl.text(text);
      return this.textMeasureEl.width();
    }
    
    showNext() {
      let nextIndex = this.currentIndex + 1;
      
      // Loop back to beginning if at end
      if (nextIndex >= this.itemCount) {
        nextIndex = 0;
      }
      
      this.showItem(nextIndex);
    }
    
    showPrev() {
      let prevIndex = this.currentIndex - 1;
      
      // Loop to end if at beginning
      if (prevIndex < 0) {
        prevIndex = this.itemCount - 1;
      }
      
      this.showItem(prevIndex);
    }
    
    play() {
      this.isPaused = false;
      this.pausedByHover = false;
      this.resetInterval();
      // Resume scrolling animation if active
      const $activeItem = this.$ticker.find('.mynews-ticker-item.active.scrolling');
      if ($activeItem.length) {
        $activeItem.css('animation-play-state', 'running');
      }
    }

    pause(pausedByHover = false) {
      this.isPaused = true;
      this.pausedByHover = pausedByHover;
      // Pause scrolling animation if active
      const $activeItem = this.$ticker.find('.mynews-ticker-item.active.scrolling');
      if ($activeItem.length) {
        $activeItem.css('animation-play-state', 'paused');
      }      if (this.interval) {
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
  };  // Initialize when DOM is ready
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
    
    // Apply scrolling class conditionally
    if (isScrollingEnabled) {
      $ticker.addClass('scrolling-enabled');
    } else {
      $ticker.removeClass('scrolling-ticker scrolling-enabled');
    }
    
    // Debug info
    console.log('Ticker elements found:', $ticker.length);
    console.log('Ticker items:', $ticker.find('.mynews-ticker-item').length);
    
    if ($ticker.length > 0) {
      // Always ensure only the first item is active at start
      $ticker.find('.mynews-ticker-item').removeClass('active').first().addClass('active');      // Get custom settings from WordPress or use defaults
      var scrollingEnabled = (typeof myNewsTickerSettings !== 'undefined' && 'scrolling' in myNewsTickerSettings)
                            ? myNewsTickerSettings.scrolling
                            : true;
                            
      var scrollDuration = (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.scrollDuration)
                          ? myNewsTickerSettings.scrollDuration
                          : 20000;
                          
      console.log('Ticker settings:', {
        speed: speed,
        scrolling: scrollingEnabled,
        scrollDuration: scrollDuration
      });
        // Get custom font size and apply it
      if (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.fontSize) {
        document.documentElement.style.setProperty('--mynews-ticker-font-size', myNewsTickerSettings.fontSize);
      }      // Get display mode setting
      var displayMode = (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.displayMode) 
                      ? myNewsTickerSettings.displayMode 
                      : 'scroll';
      
      // Apply appropriate classes based on display mode
      if (displayMode === 'scroll' && scrollingEnabled) {
        $ticker.addClass('scrolling-enabled scrolling-ticker');
        // Force hardware acceleration for smoother scrolling
        $('.mynews-ticker-content').css({
          'transform': 'translateZ(0)',
          '-webkit-transform': 'translateZ(0)',
          '-webkit-backface-visibility': 'hidden'
        });
      } else {
        $ticker.removeClass('scrolling-ticker scrolling-enabled');
      }
      
      console.log('Initializing ticker with mode:', displayMode);

      // Initialize ticker
      $ticker.myNewsBreakingTicker({
        autoPlay: true,
        speed: speed,
        pauseOnHover: true,
        displayMode: displayMode,
        scrolling: scrollingEnabled,
        scrollDuration: scrollDuration
      });
      
      // Force recalculation after a short delay to ensure proper display
      setTimeout(function() {
        $(window).trigger('resize');
      }, 500);
    }
  });

})(jQuery);
