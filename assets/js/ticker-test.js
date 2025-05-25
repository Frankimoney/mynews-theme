/**
 * Breaking News Ticker Test Script
 * This script will be loaded after the main ticker script and adds monitoring
 * for animation events and ticker behavior
 */
(function($) {
  'use strict';

  // Wait until DOM and ticker are fully loaded
  $(window).on('load', function() {
    console.log('Ticker test script loaded');
    
    const $ticker = $('.mynews-breaking-news');
    const $items = $ticker.find('.mynews-ticker-item');
    
    console.log(`Found ${$items.length} ticker items`);
    
    // Track current active item
    let currentActiveItemIndex = -1;
    
    // Monitor for item changes
    const checkInterval = setInterval(function() {
      const $activeItem = $ticker.find('.mynews-ticker-item.active');
      
      if ($activeItem.length) {
        const activeIndex = $activeItem.index();
        
        if (activeIndex !== currentActiveItemIndex) {
          // Item changed
          console.log(`Ticker item changed to #${activeIndex + 1}`);
          console.log(`Item text: "${$activeItem.text().trim().substring(0, 30)}..."`);
          console.log(`Animation duration: ${$activeItem.css('animation-duration')}`);
          
          currentActiveItemIndex = activeIndex;
        }
      }
    }, 1000);
    
    // Track animation events
    $ticker.on('animationstart', '.mynews-ticker-item', function() {
      const $item = $(this);
      console.log(`Animation started for item #${$item.index() + 1}`);
    });
    
    $ticker.on('animationend', '.mynews-ticker-item', function() {
      const $item = $(this);
      console.log(`Animation ended for item #${$item.index() + 1}`);
    });
    
    // Stop checking after 2 minutes
    setTimeout(function() {
      clearInterval(checkInterval);
      console.log('Ticker monitoring stopped');
    }, 120000);
  });

})(jQuery);
