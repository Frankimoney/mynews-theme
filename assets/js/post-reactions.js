/**
 * Post Reactions JavaScript
 *
 * Handles AJAX requests for post reactions
 * 
 * @package My_News
 */

(function($) {
    'use strict';

    // Initialize post reactions
    function initPostReactions() {
        const $reactions = $('.mynews-post-reactions');
        
        // Only proceed if reaction container exists
        if ($reactions.length === 0) {
            return;
        }
        
        // Handle click events on reaction buttons
        $reactions.on('click', '.mynews-reaction-btn', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const $container = $button.closest('.mynews-post-reactions');
            const postId = $container.data('post-id');
            const reaction = $button.data('reaction');
            
            // Disable all buttons temporarily to prevent multiple clicks
            $container.find('.mynews-reaction-btn').addClass('disabled');
            
            // Add animation class
            $button.addClass('animate');
            
            // Send AJAX request
            $.ajax({
                url: mynewsReactions.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'mynews_handle_post_reaction',
                    post_id: postId,
                    reaction: reaction,
                    security: mynewsReactions.nonce
                },
                success: function(response) {
                    if (response.success) {
                        updateReactionUI(response.data, $container);
                    } else {
                        console.error('Error handling reaction:', response.data.message);
                        if (response.data.message === 'User not logged in') {
                            // Optionally prompt user to login
                            if (confirm(mynewsReactions.loginPrompt)) {
                                window.location.href = mynewsReactions.loginUrl;
                            }
                        }
                    }
                },
                error: function() {
                    console.error('AJAX error occurred when handling reaction');
                },
                complete: function() {
                    // Re-enable buttons after request completes
                    $container.find('.mynews-reaction-btn').removeClass('disabled');
                    
                    // Remove animation class after animation completes
                    setTimeout(function() {
                        $button.removeClass('animate');
                    }, 500);
                }
            });
        });
    }
    
    // Update the reaction UI based on server response
    function updateReactionUI(data, $container) {
        // Update reaction counts
        for (const [key, count] of Object.entries(data.reactions)) {
            const $btn = $container.find(`.mynews-reaction-btn[data-reaction="${key}"]`);
            if ($btn.length) {
                $btn.find('.mynews-reaction-count').text(count);
            }
        }
        
        // Remove 'reacted' class from all buttons
        $container.find('.mynews-reaction-btn').removeClass('reacted');
        
        // Add 'reacted' class to current reaction if user reacted
        if (data.user_reaction) {
            const $activeBtn = $container.find(`.mynews-reaction-btn[data-reaction="${data.user_reaction}"]`);
            if ($activeBtn.length) {
                $activeBtn.addClass('reacted');
            }
        }
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        initPostReactions();
    });
    
})(jQuery);
