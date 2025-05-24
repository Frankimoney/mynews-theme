/**
 * Grid Layout JavaScript fixes
 * Ensures grid items have equal heights and properly align
 */

(function($) {
    'use strict';
    
    // Function to ensure equal heights for grid items
    function equalizeGridHeights() {
        // Reset heights first
        $('.row.g-4 .card').css('height', 'auto');
        
        // Don't do this on mobile
        if (window.innerWidth < 768) {
            return;
        }
        
        // For each row of cards
        var rows = {};
        $('.row.g-4 > [class^="col-"]').each(function() {
            // Get the card's Y position
            var y = $(this).offset().top;
            
            // If we haven't seen this row yet, create it
            if (!rows[y]) {
                rows[y] = [];
            }
            
            // Add this card to its row
            rows[y].push($(this).find('.card'));
        });
        
        // Set each row's cards to the same height
        for (var y in rows) {
            if (rows.hasOwnProperty(y)) {
                var maxHeight = 0;
                
                // Find the tallest card in this row
                $.each(rows[y], function() {
                    maxHeight = Math.max(maxHeight, $(this).outerHeight());
                });
                
                // Set all cards in this row to the tallest height
                $.each(rows[y], function() {
                    $(this).css('height', maxHeight + 'px');
                });
            }
        }
    }
    
    // Fix grid layout issues
    function fixGridLayout() {
        // Ensure there are no large gaps in the grid
        var $grid = $('.row.g-4');
        var $columns = $grid.find('> [class^="col-"]');
        
        // Add clearfix after each row
        if ($columns.length > 0) {
            var columnsPerRow = getColumnsPerRow();
            
            if (columnsPerRow > 1) {
                // Add clearfix after each row
                for (var i = columnsPerRow; i < $columns.length; i += columnsPerRow) {
                    if (!$columns.eq(i-1).next().hasClass('clearfix')) {
                        $columns.eq(i-1).after('<div class="clearfix"></div>');
                    }
                }
            }
        }
    }
    
    // Calculate how many columns are displayed per row
    function getColumnsPerRow() {
        var windowWidth = window.innerWidth;
        
        if (windowWidth >= 1200) {
            // Check for custom setting from WordPress customizer
            var postsPerRow = 3; // Default value
            
            if (typeof myNewsGridSettings !== 'undefined' && myNewsGridSettings.postsPerRow) {
                postsPerRow = parseInt(myNewsGridSettings.postsPerRow);
            }
            
            return postsPerRow;
        } else if (windowWidth >= 768) {
            return 2; // 2 columns on tablets
        } else {
            return 1; // 1 column on mobile
        }
    }
    
    // Run on document ready
    $(document).ready(function() {
        // Initial fixes
        equalizeGridHeights();
        fixGridLayout();
        
        // Fix on window resize
        $(window).on('resize', function() {
            equalizeGridHeights();
            fixGridLayout();
        });
        
        // Fix after images are loaded
        $(window).on('load', function() {
            equalizeGridHeights();
        });
    });
    
})(jQuery);
