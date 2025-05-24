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
        scrollDuration: 20000 // 20 seconds to complete one full scroll
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
      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      // Only use interval for fade mode or when scrolling is not needed
      if (!this.isPaused && (!this.options.scrolling || this.options.displayMode === 'fade')) {
        this.interval = setInterval(() => {
          this.showNext();
        }, this.options.speed);
      }
    }
      showItem(index) {
      // Always get the latest items in case DOM changes
      const $items = this.$ticker.find('.mynews-ticker-item');
      this.itemCount = $items.length; // update itemCount!
      $items.removeClass('active scrolling').css({
        'opacity': '',
        'animation-duration': '',
        'animation-play-state': ''
      });
      
      const $currentItem = $items.eq(index);
      
      // Add active class
      $currentItem.addClass('active');
      
      // Add scrolling animation if enabled
      if (this.options.scrolling) {
        // Force a reflow before adding the scrolling class to ensure animation starts properly
        void $currentItem[0].offsetWidth;
        
        $currentItem.addClass('scrolling');
        
        // Set animation duration from options
        if (this.options.scrollDuration) {
          const duration = this.options.scrollDuration / 1000;
          console.log('Setting animation duration:', duration + 's');
          
          // Calculate animation speed based on text length for more consistent reading experience
          const textLength = $currentItem.text().length;
          const containerWidth = this.$ticker.find('.mynews-ticker-content').width();
          const textWidth = this.getTextWidth($currentItem.text());
          
          // If text is short enough to fit in container without scrolling
          if (textWidth <= containerWidth * 0.8) {
            $currentItem.removeClass('scrolling');
            // Use interval for non-scrolling items
            this.resetInterval();
            return;
          }
          
          // Adjust duration based on text length AND container width
          // Longer text and smaller containers need longer duration
          const adjustedDuration = Math.max(
            duration, 
            (textLength * containerWidth / 1000) * 0.4
          );
          
          console.log('Text width:', textWidth, 'Container width:', containerWidth);
          console.log('Adjusted duration:', adjustedDuration + 's');
          
          $currentItem.css({
            'animation-duration': adjustedDuration + 's'
          });
        }
        // Listen for animation end to advance to next item
        $currentItem.one('animationend', () => {
          this.showNext();
        });
        // Stop interval while scrolling
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
      
      this.currentIndex = index;
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
      }

      // Initialize ticker
      $ticker.myNewsBreakingTicker({
        autoPlay: true,
        speed: speed,
        pauseOnHover: true,
        displayMode: (typeof myNewsTickerSettings !== 'undefined' && myNewsTickerSettings.displayMode) 
                    ? myNewsTickerSettings.displayMode 
                    : 'scroll',
        scrolling: scrollingEnabled,
        scrollDuration: scrollDuration
      });
    }
  });

})(jQuery);
