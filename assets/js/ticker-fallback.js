/**
 * Ticker animation fallback for browsers that don't support CSS animations properly
 */
(function($) {
  'use strict';

  // Check if CSS animations are supported
  function supportsAnimations() {
    const elm = document.createElement('div');
    return typeof elm.style.animationName !== 'undefined' || 
           typeof elm.style.WebkitAnimationName !== 'undefined';
  }

  $(document).ready(function() {
    // If animations aren't properly supported, use fallback scrolling
    if (!supportsAnimations()) {
      console.log('CSS animations not fully supported, using JS fallback');
      
      // Find ticker items
      const $tickerItems = $('.mynews-ticker-item.active.scrolling');
      
      if ($tickerItems.length) {
        $tickerItems.each(function() {
          const $item = $(this);
          const $tickerContent = $item.parent();
          const contentWidth = $tickerContent.width();
          
          // Reset any existing animation
          $item.css({
            'animation': 'none',
            'transform': 'none',
            'left': contentWidth + 'px'
          });
          
          // Fallback animation using jQuery
          function animateItem() {
            $item.animate(
              { left: -$item.width() },
              {
                duration: 15000,
                easing: 'linear',
                complete: function() {
                  // Reset position and start again
                  $item.css('left', contentWidth + 'px');
                  animateItem();
                }
              }
            );
          }
          
          // Start animation
          animateItem();
          
          // Pause on hover
          $item.hover(
            function() { $item.stop(); },
            function() { animateItem(); }
          );
        });
      }
    }
  });
})(jQuery);
