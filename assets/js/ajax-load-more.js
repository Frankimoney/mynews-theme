/**
 * AJAX Load More functionality for My News theme
 */
(function($) {
    'use strict';
    
    // Variables to track pagination
    let currentPage = 1;
    let loading = false;
    const loadMoreBtn = $('#mynews-load-more');
    
    // Handle load more button click
    loadMoreBtn.on('click', function(e) {
        e.preventDefault();
        
        if (loading) return;
        loading = true;
        
        const button = $(this);
        const blogLayout = button.data('layout');
        const postsPerRow = button.data('posts-per-row');
        
        // Change button text to loading
        const originalText = button.text();
        button.text(mynewsLoadMore.loadingText).addClass('loading');
        
        // Make AJAX request
        $.ajax({
            url: mynewsLoadMore.ajaxurl,
            type: 'POST',
            data: {
                action: 'mynews_load_more_posts',
                nonce: mynewsLoadMore.nonce,
                page: currentPage + 1,
                layout: blogLayout,
                posts_per_row: postsPerRow
            },
            success: function(response) {
                if (response.success) {
                    currentPage++;
                    
                    // Append new posts based on layout
                    if (blogLayout === 'grid') {
                        $('.mynews-posts-grid').append(response.data.html);
                    } else {
                        $('.posts-list').append(response.data.html);
                    }
                    
                    // If we've reached the max number of pages, hide the button
                    if (currentPage >= mynewsLoadMore.maxPages) {
                        button.hide();
                    }
                    
                    // Reset button state
                    button.text(originalText).removeClass('loading');
                    loading = false;
                }
            },
            error: function() {
                // Handle error
                button.text(originalText).removeClass('loading');
                loading = false;
                console.log('Error loading more posts');
            }
        });
    });
    
})(jQuery);
